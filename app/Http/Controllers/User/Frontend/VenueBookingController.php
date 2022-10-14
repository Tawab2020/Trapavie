<?php

namespace App\Http\Controllers\User\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\VenueBookingRequest;
use App\Models\Conversation;
use App\Models\Currency;
use App\Models\Fee;
use App\Models\Message;
use App\Models\User;
use App\Models\Venue\Venue;
use App\Models\VenueBooking;
use App\Notifications\RetreatOrganizer\DepositReceived;
use App\Notifications\RetreatOrganizer\RemainingBalanceRecieved;
use App\Notifications\VenueHost\BookingRequested;
use App\Notifications\VenueHost\DepositPaid;
use App\Notifications\VenueHost\RemainingBalancePaid;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class VenueBookingController extends Controller
{
    public function create(Venue $venue, VenueBookingRequest $request)
    {
        $booking_data = $request->validated();

        $startDate = new DateTime($booking_data["start_date"]);
        $endDate = new DateTime($booking_data["end_date"]);
        $numberOfGuests = $booking_data['guests'];

        // store venue log
        $disclude = ['id', 'user_id', 'status', 'is_paused', 'is_deleted', 'step', 'created_at', 'updated_at'];
        $booking_data['venue_log'] = copyModelElementsToArray($venue, $disclude);

        // store cancellation policy log
        $cancellationPolicy = $venue->cancellationPolicy;
        if ($cancellationPolicy->cancellation_policy_data_id > 0) {
            $cancellationPolicy = $cancellationPolicy->cancellationPolicyData;
        }
        $disclude = ['id', 'venue_id', 'cancellation_policy_data_id', 'created_at', 'updated_at'];
        $booking_data['cancellation_policy_log'] = copyModelElementsToArray($cancellationPolicy, $disclude);

        // fill the fields
        $booking_data['venue_id'] = $venue->id;
        $booking_data['user_id'] = auth('user')->user()->id;

        // calculate Venue pricing
        $booking_data['price'] = $this->calculateVenuePrice($venue, $startDate, $endDate, $numberOfGuests);

        // store the booking
        $booking = VenueBooking::create($booking_data);

        // add relationships between booking and venue_data
        $booking->venueData()->attach($venue->venueData);

        // email venue host
        $user = User::find($venue->user_id);
        $user->notify(new BookingRequested($booking));

        return response()->json($booking, 200);
    }

    public function sendMessage(Venue $venue, Request $request)
    {
        $mutualConv = false;
        $newUsers = [$venue->user_id, auth('user')->user()->id];
        $hostConvs = $venue->user->conversations; //conversations of the venue host

        foreach ($hostConvs as $conv) {
            $convUsers = [$conv->users->pluck('id')[0], $conv->users->pluck('id')[1]];
            if (array_intersect($convUsers, $newUsers) > 0) {
                $mutualConv = $conv;
                break;
            }
        }

        if ($mutualConv) {
            $message = ["body" => $request->input('message'), "conversation_id" => $mutualConv->id, "user_id" => auth('user')->user()->id];
            Message::create($message);
        } else {
            $conversation = Conversation::create();
            $conversation->users()->sync([auth('user')->user()->id => ['type' => 0], $venue->user_id => ['type' => 1]]);
            $message = ["body" => $request->input('message'), "conversation_id" => $conversation->id, "user_id" => auth('user')->user()->id];
            Message::create($message);
        }

        return response()->json(["messsage" => "sent"], 200);
    }

    public function show(VenueBooking $venueBooking)
    {
        if ($venueBooking->status == 1 || $venueBooking->status == 2) {
            $venueBooking->load(['venue.images', 'venue.accommodations.bedrooms']);
            return response()->json($venueBooking, 200);
        } else {
            return response()->json(["Venue Booking does not have a confirmed or deposit paid status."], 500);
        }
    }

    public function getPrice(Venue $venue, VenueBookingRequest $request)
    {
        $booking_data = $request->validated();

        $startDate = new DateTime($booking_data["start_date"]);
        $endDate = new DateTime($booking_data["end_date"]);
        $numberOfGuests = $booking_data['guests'];

        $price = $this->calculateVenuePrice($venue, $startDate, $endDate, $numberOfGuests);

        return response()->json($price, 200);
    }

    public function calculateVenuePrice(Venue $venue, $startDate, $endDate, $numberOfGuests)
    {
        $numberOfNights = $endDate->diff($startDate)->days;

        $cancellationPolicy = $venue->cancellationPolicy;
        if ($cancellationPolicy->cancellation_policy_data_id > 0) {
            $cancellationPolicy = $cancellationPolicy->cancellationPolicyData;
        }

        // calculate Venue pricing
        $venuePrice = $venue->calculateVenuePricing($numberOfGuests, $numberOfNights);

        // calculate high/low season pricings
        $seasonPricing = $venue->calculateSeasonPricings($startDate, $endDate, $venuePrice);
        $venuePrice = $seasonPricing['venuePrice'];
        $seasonPricingTotal = $seasonPricing['seasonPriceDifference'];
        $totalDays = $seasonPricing['totalDays'];

        // calculate venue pricing discounts
        $venueDiscount = $venue->calculateLengthOfStayDiscounts($numberOfNights, $venuePrice);
        $venuePrice = $venueDiscount['venuePrice'];
        $venuePriceDiscountTotal = $venueDiscount['discountTotal'];
        $venuePriceDiscountPercentage = $venueDiscount['discountPercentage'];

        // calculate meals price
        $mealsPrice = $venue->calculateMealsPrice($numberOfNights, $numberOfGuests);

        // calculate meal discounts
        $mealsDiscount = $venue->calculateMealDiscounts($numberOfGuests, $mealsPrice);
        $mealsPrice = $mealsDiscount['mealsPrice'];
        $mealsPriceDiscountTotal = $mealsDiscount['discountTotal'];
        $mealsPriceDiscountPercentage = $mealsDiscount['discountPercentage'];

        $totalVenuePrice = $venuePrice + $mealsPrice;


        //store fees percentages, since the admin might change them before the booking deposit is paid
        $fees = Fee::find(1);
        $adminFees = $totalVenuePrice * ($fees->admin_fees_percentage / 100);
        $transactionFees = $totalVenuePrice * ($fees->transaction_fees_percentage / 100);


        // total price for the whole venue including the admina and transaction fees
        $totalPrice = $totalVenuePrice + $adminFees + $transactionFees;

        $priceToSecureBooking = $totalPrice * ($cancellationPolicy->deposit / 100);
        $remainingBalance = $totalPrice - $priceToSecureBooking;

        // Store the currency
        $currency = Currency::where('id', $venue->currency_id)->first();

        $price = [
            'fees' => [
                'admin_fees_percentage' => $fees->admin_fees_percentage,
                'admin_fees' => round($adminFees, 2),
                'transaction_fees_percentage' => $fees->transaction_fees_percentage,
                'transaction_fees' => round($transactionFees, 2)
            ],
            'price_to_secure_booking' => round($priceToSecureBooking, 2),
            'remaining_balance' => round($remainingBalance, 2),
            'total_price' => round($totalPrice, 2),
            'season_pricing' => [
                'total_price' => round($seasonPricingTotal, 2),
                'total_days' => $totalDays,
            ],
            'venue_price' => [
                'total_price' => round($venuePrice, 2),
                'total_discount' => round($venuePriceDiscountTotal, 2),
                'discount_percentage' => $venuePriceDiscountPercentage,
            ],
            'meals_price' => [
                'total_price' => round($mealsPrice, 2),
                'total_discount' => round($mealsPriceDiscountTotal),
                'discount_percentage' => $mealsPriceDiscountPercentage
            ],
            'currency' => $currency
        ];

        return $price;
    }

    public function changeBookingStatusToDepositPaid(VenueBooking $venueBooking)
    {
        //TODO: make sure the payment is done

        // Change venue status to Deposit Paid (means confirmed by retreat organizer)
        $venueBooking->status = 2;
        $venueBooking->deposit_paid_at = Carbon::now();

        //TODO: Make these in event
        // Notify retreat organizer (confirmation)
        $venueBooking->user->notify(new DepositReceived($venueBooking));

        // Notify Host
        $venueBooking->venue->user->notify(new DepositPaid($venueBooking));

        $venueBooking->save();
        return response()->json($venueBooking, 200);
    }

    public function changeBookingStatusToFullyPaid(VenueBooking $venueBooking)
    {
        //TODO: make sure the payment is done

        // Change venue status to Fully Paid
        $venueBooking->status = 3;
        $venueBooking->fully_paid_at = Carbon::now();

        //TODO: Make these in event
        // Notify retreat organizer (confirmation)
        $venueBooking->user->notify(new RemainingBalanceRecieved($venueBooking));

        // Notify Host
        $venueBooking->venue->user->notify(new RemainingBalancePaid($venueBooking));

        $venueBooking->save();
        return response()->json($venueBooking, 200);
    }
}
