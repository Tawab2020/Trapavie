<?php

namespace App\Models\Venue;

use App\Models\User;
use App\Models\VenueBooking;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Venue extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function airports()
    {
        return $this->belongsToMany(Airport::class)->select(['airports.id', 'name'])->withPivot('type', 'charge', 'distance')->orderBy('distance');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function bookings()
    {
        return $this->hasMany(VenueBooking::class);
    }

    public function updateListingBanner($image)
    {
        //Delete Previous Image
        if ($pre_img = $this->images->where('type', 1)->first()) {
            Storage::delete('public/venue/' . $pre_img->image);
            $pre_img->delete();
        }
        $base64_image = $image;
        $image_name = uploadBase64Image($base64_image, 'public/venue/');
        $this->images()->create(['image' => $image_name, 'type' => 1]);
    }

    // Upload images of general amenities and facilities
    public function uploadImage($image, $type)
    {
        if ($pre_img = $this->images->where('id', $image->id)->first()) {
            Storage::delete('public/venue/' . $pre_img->image);
            $image_name = uploadBase64Image($image->result, 'public/venue/');
            $this->images->where('id', $image->id)->first()->update(['image' => $image_name, 'type' => $type, 'caption' => $image->caption]);
            return;
        }
        $image_name = uploadBase64Image($image->result, 'public/venue/');
        $this->images()->create(['image' => $image_name, 'type' => $type, 'caption' => $image->caption]);
    }

    public function deleteImage($imgId)
    {
        $image = Image::find($imgId);
        Storage::delete('public/venue/' . $image->image);
        $image->delete();
    }

    public function attachVenueData($venueData, $venue_data_category_id)
    {
        $this->venueData()->detach($this->venueData->where('venue_data_category_id', $venue_data_category_id));
        foreach ($venueData as $venueDatum) {
            if ($venueDatum->id == 0) {
                //If venueData is added by user, the language will by default be "en", so when showing any venueData, we either show the translated one, if that does not exist, we show the english one
                $newVenueData = VenueData::create(["title" => $venueDatum->title, "user_id" => $venueDatum->user_id, "venue_data_category_id" => $venue_data_category_id]);
                $this->venueData()->attach($newVenueData->id);
            } else {
                $this->venueData()->attach($venueDatum->id);
            }
        }
    }

    public function accommodations()
    {
        return $this->hasMany(Accommodation::class);
    }

    public function discounts()
    {
        return $this->hasMany(VenueDiscount::class)->orderBy('from');
    }

    public function lengthOfStayDiscounts()
    {
        return $this->discounts()->where('type', 0);
    }

    public function calculateLengthOfStayDiscounts($numberOfNights, $venuePrice)
    {
        $venuePricingDiscountPercentage = 0;
        foreach ($this->lengthOfStayDiscounts as $discount) {
            if ($numberOfNights >= $discount->from && $numberOfNights <= $discount->to) {
                $venuePricingDiscountPercentage = $discount->percentage;
                break;
            }
        }
        $discountTotal = ($venuePricingDiscountPercentage * $venuePrice) / 100;
        $venuePrice = $venuePrice - $discountTotal;
        return ['venuePrice' => $venuePrice, 'discountTotal' => $discountTotal, 'discountPercentage' => $venuePricingDiscountPercentage];
    }

    public function mealDiscounts()
    {
        return $this->discounts()->where('type', 1);
    }

    public function calculateMealDiscounts($numberOfGuests, $mealsPrice)
    {
        $mealsPricingDiscountPercentage = 0;
        foreach ($this->mealDiscounts as $discount) {
            if ($numberOfGuests >= $discount->from && $numberOfGuests <= $discount->to) {
                $mealsPricingDiscountPercentage = $discount->percentage;
                break;
            }
        }
        $discountTotal = ($mealsPricingDiscountPercentage * $mealsPrice) / 100;
        $mealsPrice = $mealsPrice - $discountTotal;
        return ['mealsPrice' => $mealsPrice, 'discountTotal' => $discountTotal, 'discountPercentage' => $mealsPricingDiscountPercentage];
    }

    // This will load all the venue data assigned to this venue, from the pivot model.
    public function venueData()
    {
        // return $this->belongsToMany(VenueData::class, 'venue_venue_datum');
        $user = auth('user')->user();
        return $this->belongsToMany(VenueData::class, 'venue_venue_datum');
    }

    public function keyPropertyFeatures()
    {
        return $this->venueData()->where('venue_data_category_id', 1);
    }

    public function generalFacilities()
    {

        return $this->venueData()->where('venue_data_category_id', 2);
    }

    public function indoorFacilities()
    {
        return $this->venueData()->where('venue_data_category_id', 3);
    }

    public function outdoorFacilities()
    {
        return $this->venueData()->where('venue_data_category_id', 4);
    }

    public function indoorYogaEquipments()
    {
        return $this->venueData()->where('venue_data_category_id', 5);
    }

    public function outdoorYogaEquipments()
    {
        return $this->venueData()->where('venue_data_category_id', 6);
    }

    public function surroundings()
    {
        return $this->venueData()->where('venue_data_category_id', 7);
    }

    public function thingsToDo()
    {
        return $this->venueData()->where('venue_data_category_id', 8);
    }

    public function rentals()
    {
        return $this->venueData()->where('venue_data_category_id', 9);
    }

    public function food()
    {
        return $this->venueData()->where('venue_data_category_id', 10);
    }

    public function kitchen()
    {
        return $this->venueData()->where('venue_data_category_id', 11);
    }

    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public function deleteMeals()
    {
        foreach ($this->meals as $meal) {
            $meal->delete();
        }
    }

    public function calculateMealsPrice($numberOfNights, $numberOfGuests)
    {
        $mealsPricePerGuest = 0;
        foreach ($this->meals as $meal) {
            $mealsPricePerGuest += $meal->price;
        }
        $mealsPrice = $mealsPricePerGuest * $numberOfNights * $numberOfGuests;
        return $mealsPrice;
    }

    public function cancellationPolicy()
    {
        return $this->hasOne(CancellationPolicy::class);
    }

    public function blockedDates()
    {
        return $this->hasMany(BlockedDates::class);
    }

    public function seasonPricings()
    {
        return $this->hasMany(SeasonPricing::class);
    }

    public function calculateSeasonPricings($startDate, $endDate, $venuePrice)
    {
        $pricePerNight = $venuePrice / ($endDate->diff($startDate)->days);
        $seasonPriceDifference = 0;
        $totalDays = 0;
        foreach ($this->seasonPricings as $seasonPricing) {
            $seasonStartDate = new DateTime($seasonPricing->start);
            $seasonEndDate = new DateTime($seasonPricing->end);

            // make the years same for both dates, since the seasonPricing date year is always 2022.
            if ($seasonStartDate->format('Y') != $startDate->format('Y')) {
                $seasonStartDate =  date_create($seasonStartDate->format($startDate->format('Y') . "-m-d"));
                $seasonEndDate =  date_create($seasonEndDate->format($startDate->format('Y') . "-m-d"));
            }

            $days = 0;
            if ($startDate > $seasonEndDate || $endDate < $seasonStartDate) {
                // in this case the range does not overlap with the season at all
                $days = 0;
            } else {
                $days = (max($seasonStartDate, $startDate))->diff(min($seasonEndDate, $endDate))->days;
                $totalDays += $days;
            }
            $seasonedPrice = $days * $pricePerNight;
            $seasonPriceAfterPercentage = $seasonedPrice + ($seasonedPrice * $seasonPricing->percentage) / 100;
            $seasonPriceDifference += $seasonPriceAfterPercentage - $seasonedPrice;
            $venuePrice = $venuePrice - $seasonedPrice + $seasonPriceAfterPercentage;
        }
        return ['venuePrice' => $venuePrice, 'seasonPriceDifference' => $seasonPriceDifference, 'totalDays' => $totalDays];
    }

    public function calculateVenuePricing($numberOfGuests, $numberOfNights)
    {
        // calculate for flexible venue pricing
        if ($this->guests_included > 0 && $numberOfGuests > $this->guests_included) {
            $venuePrice = $numberOfNights * $this->additional_guest_price;
        }
        // calculate for fixed venue pricing
        else {
            $venuePrice = $numberOfNights * $this->price_per_night;
        }
        return $venuePrice;
    }
}
