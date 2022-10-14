<?php

namespace App\Http\Controllers\User\Hosting\Venue;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Hosting\Venue\Save\AccommodationRequest;
use App\Http\Requests\User\Hosting\Venue\Save\AccommodationsRequest;
use App\Http\Requests\User\Hosting\Venue\Save\ArrivalRequest;
use App\Http\Requests\User\Hosting\Venue\Save\CancellationPolicyRequest;
use App\Http\Requests\User\Hosting\Venue\Save\CheckInRequest;
use App\Http\Requests\User\Hosting\Venue\Save\DescriptionFeaturesRequest;
use App\Http\Requests\User\Hosting\Venue\Save\FacilitiesRequest;
use App\Http\Requests\User\Hosting\Venue\Save\KitchenRequest;
use App\Http\Requests\User\Hosting\Venue\Save\MealsRequest;
use App\Http\Requests\User\Hosting\Venue\Save\NameAddressRequest;
use App\Http\Requests\User\Hosting\Venue\Save\PricingRequest;
use App\Http\Requests\User\Hosting\Venue\Save\RetreatOrganizerRequest;
use App\Http\Requests\User\Hosting\Venue\Save\SurroundingsRequest;
use App\Http\Requests\User\Hosting\Venue\Save\YogaRequest;
use App\Models\User;
use App\Models\Venue\Accommodation;
use App\Models\Venue\AccommodationType;
use App\Models\Venue\Airport;
use App\Models\Venue\Bedroom;
use App\Models\Venue\CancellationPolicy;
use App\Models\Venue\Meal;
use App\Models\Venue\PropertyType;
use App\Models\Venue\Venue;
use App\Models\Venue\VenueDiscount;


class SaveVenueController extends Controller
{
    public function show(Venue $venue)
    {
        // dd($venue->user_id);
        if ($venue->user_id != auth('user')->user()->id) {
            return response()->json('Forbidden', 403);
        }
        $venue->load(['airports', 'keyPropertyFeatures', 'images', 'generalFacilities', 'indoorFacilities', 'outdoorFacilities', 'indoorYogaEquipments', 'outdoorYogaEquipments', 'surroundings', 'thingsToDo', 'rentals', 'food', 'kitchen', 'accommodations.bedrooms.bedroomArrangements', 'accommodations.amenities', 'accommodations.images', 'discounts', 'meals', 'cancellationPolicy']);
        // dd($venue->generalFacilities);
        return response()->json($venue, 200);
    }

    public function storeNameAddress(NameAddressRequest $request)
    {
        $venue_data = $request->validated();
        $venue_data['step'] = 1;
        $venue = Venue::create($venue_data);

        $user = User::find($venue_data['user_id'])->first();
        if ($user->type == 0) {
            $user->type = 1;
            $user->save();
        }

        $venue->load('airports');
        return response()->json([
            'message' => 'Name and address of the venue were stored.',
            'user_type' => $user->type,
            'venue' => $venue,
        ], 200);
    }

    public function UpdateNameAddress(NameAddressRequest $request, Venue $venue)
    {
        $nameAddress = $request->validated();
        $venue->fill($nameAddress);
        if ($venue->step < 1) {
            $venue->step = 1;
        } else {
            $venue->status = 1;
        }
        $venue->save();
        $venue->load('airports');
        return response()->json([
            'message' => 'Name and address of the venue were updated.',
            'venue' => $venue,
        ], 200);
    }

    public function getAirports(Venue $venue)
    {
        $airports = Airport::near($venue->lat, $venue->lng, 200)->limit(8)->get();
        return response()->json($airports, 200);
    }

    public function updateArrival(ArrivalRequest $request, Venue $venue)
    {
        $arrival_data = $request->validated();
        $venue_data = ['arrival_info' => $arrival_data['arrival_info']];
        $venue->airports()->detach();
        foreach ($arrival_data['airports'] as $airport) {
            $venue->airports()->attach($airport['id'], ['distance' => $airport['pivot']['distance'], 'type' => $airport['pivot']['type'], 'charge' => $airport['pivot']['charge']]);
        }
        $venue->fill($venue_data);
        // Update venue step if it is the first time this step is being updated, else change the status to pending approval
        if ($venue->step < 2) {
            $venue->step = 2;
        } else {
            $venue->status = 1;
        }
        $venue->save();
        $venue->load('airports');
        return response()->json([
            'message' => 'Venue arrival data was updated.',
            'venue' => $venue,
        ], 200);
    }

    public function updateDescriptionFeatures(DescriptionFeaturesRequest $request, Venue $venue)
    {
        $descriptionFeature_data = $request->validated();

        //Upload the image
        if (isset($descriptionFeature_data['image'])) {
            $venue->updateListingBanner($descriptionFeature_data['image']);
        }

        // Sync Key Property Features
        $keyPropertyFeatures = json_decode($descriptionFeature_data['key_property_features']);
        $venue->attachVenueData($keyPropertyFeatures, 1);

        // Save venue Description
        $venue_data = ['description' => $descriptionFeature_data['description']];
        $venue->fill($venue_data);
        if ($venue->step < 3) {
            $venue->step = 3;
        } else {
            $venue->status = 1;
        }
        $venue->save();
        $venue->load('images', 'keyPropertyFeatures');
        return response()->json([
            'message' => 'Venue description and key property features were updated.',
            'venue' => $venue,
        ], 200);
    }

    public function updateFacilities(FacilitiesRequest $request, Venue $venue)
    {
        $facilities_data = $request->validated();

        $generalFacilities = json_decode($facilities_data['general_facilities']);
        $venue->attachVenueData($generalFacilities, 2);

        $indoorFacilities = json_decode($facilities_data['indoor_facilities']);
        $venue->attachVenueData($indoorFacilities, 3);

        $outdoorFacilities = json_decode($facilities_data['outdoor_facilities']);
        $venue->attachVenueData($outdoorFacilities, 4);

        if (isset($facilities_data['images'])) {
            $images = json_decode($facilities_data['images']);
            foreach ($images as $image) {
                $venue->uploadImage($image, $image->type);
            }
        }

        //Delete images that were chosen to be deleted
        if (isset($facilities_data['imagesToBeDeleted'])) {
            $imagesToBeDeleted = json_decode($facilities_data['imagesToBeDeleted']);
            foreach ($imagesToBeDeleted as $img) {
                $venue->deleteImage($img);
            }
        }
        if ($venue->step < 4) {
            $venue->step = 4;
        } else {
            $venue->status = 1;
        }
        $venue->save();
        $venue->load('images', 'generalFacilities', 'indoorFacilities', 'outdoorFacilities');
        return response()->json([
            'message' => 'Venue facilities were updated.',
            'venue' => $venue,
        ], 200);
    }

    public function updateYoga(YogaRequest $request, Venue $venue)
    {
        $yoga_data = $request->validated();

        $indoor_yoga_equipments = json_decode($yoga_data['indoor_yoga_equipments']);
        $venue->attachVenueData($indoor_yoga_equipments, 5);

        $outdoor_yoga_equipments = json_decode($yoga_data['outdoor_yoga_equipments']);
        $venue->attachVenueData($outdoor_yoga_equipments, 6);

        if (isset($yoga_data['images'])) {
            $images = json_decode($yoga_data['images']);
            foreach ($images as $image) {
                $venue->uploadImage($image, $image->type);
            }
        }

        if (isset($yoga_data['imagesToBeDeleted'])) {
            $imagesToBeDeleted = json_decode($yoga_data['imagesToBeDeleted']);
            // dd($imagesToBeDeleted);
            foreach ($imagesToBeDeleted as $image) {
                $venue->deleteImage($image);
            }
        }

        $venue_data = ['yoga_spaces' => $yoga_data['yoga_spaces'], 'indoor_capacity' => $yoga_data['indoor_capacity'], 'outdoor_capacity' => $yoga_data['outdoor_capacity']];
        $venue->fill($venue_data);
        if ($venue->step < 5) {
            $venue->step = 5;
        } else {
            $venue->status = 1;
        }
        $venue->save();
        $venue->load('images', 'indoorYogaEquipments', 'outdoorYogaEquipments');
        return response()->json([
            'message' => 'Venue yoga section was updated.',
            'venue' => $venue,
        ], 200);
    }

    public function updateSurroundings(SurroundingsRequest $request, Venue $venue)
    {
        $surroundings_data = $request->validated();

        $surroundings = json_decode($surroundings_data['surroundings']);
        $venue->attachVenueData($surroundings, 7);

        $thingsToDo = json_decode($surroundings_data['things_to_do']);
        $venue->attachVenueData($thingsToDo, 8);

        $rentals = json_decode($surroundings_data['rentals']);
        $venue->attachVenueData($rentals, 9);

        if (isset($surroundings_data['images'])) {
            $images = json_decode($surroundings_data['images']);
            foreach ($images as $image) {
                $venue->uploadImage($image, $image->type);
            }
        }

        //Delete images that were chosen to be deleted
        if (isset($surroundings_data['imagesToBeDeleted'])) {
            $imagesToBeDeleted = json_decode($surroundings_data['imagesToBeDeleted']);
            foreach ($imagesToBeDeleted as $img) {
                $venue->deleteImage($img);
            }
        }

        if ($venue->step < 6) {
            $venue->step = 6;
        } else {
            $venue->status = 1;
        }
        $venue->save();

        $venue->load('images', 'surroundings', 'thingsToDo', 'rentals');
        return response()->json([
            'message' => 'Venue surroundings section was updated.',
            'venue' => $venue,
        ], 200);
    }

    public function updateKitchen(KitchenRequest $request, Venue $venue)
    {
        $kitchen_data = $request->validated();

        // kitchen propertys
        $kitchen = json_decode($kitchen_data['kitchen']);
        $venue->attachVenueData($kitchen, 11);

        // food diets
        $food = json_decode($kitchen_data['food']);
        $venue->attachVenueData($food, 10);

        if (isset($kitchen_data['images'])) {
            $images = json_decode($kitchen_data['images']);
            foreach ($images as $image) {
                $venue->uploadImage($image, $image->type);
            }
        }

        if (isset($kitchen_data['imagesToBeDeleted'])) {
            $imagesToBeDeleted = json_decode($kitchen_data['imagesToBeDeleted']);
            foreach ($imagesToBeDeleted as $image) {
                $venue->deleteImage($image);
            }
        }

        $venue_data = ['dining_capacity' => $kitchen_data['dining_capacity']];
        $venue->fill($venue_data);
        if ($venue->step < 7) {
            $venue->step = 7;
        } else {
            $venue->status = 1;
        }
        $venue->save();

        $venue->load('images', 'kitchen', 'food');
        return response()->json([
            'message' => 'Venue kitchen and food section was updated.',
            'venue' => $venue,
        ], 200);
    }

    public function saveAccommodation(AccommodationRequest $request, Accommodation $accommodation = null)
    {
        $accommodation_data = $request->validated();

        if (isset($accommodation_data['accommodationType'])) {
            $accommodationType = $accommodation_data['accommodationType'];
            //create new accommodation type
            $newAccommodationType = AccommodationType::create(["title" => $accommodationType['title'], "user_id" => $accommodationType['user_id'], "is_used" => 1]);
            //attach it to the property type
            $propertyType = PropertyType::find($accommodationType['property_type_id']);
            $propertyType->userAccommodationTypes()->attach($newAccommodationType->id);
            $accommodation_data['accommodation_type_id'] = $newAccommodationType->id;
        }

        //save the accommodation
        $accommodation_row = ['venue_id' => $accommodation_data['venue_id'], 'name' => $accommodation_data['name'], 'accommodation_type_id' => $accommodation_data['accommodation_type_id'], 'max_guests' => $accommodation_data['max_guests'], 'number_of_bathrooms' => $accommodation_data['number_of_bathrooms'], 'description' => $accommodation_data['description'], 'quantity' => $accommodation_data['quantity']];
        if ($accommodation) {
            $accommodation->update($accommodation_row);
        } else {
            $accommodation = Accommodation::create($accommodation_row);
        }

        //save the bedroooms
        $bedrooms = $accommodation_data['bedrooms'];
        foreach ($bedrooms as $bedroom) {
            if (isset($bedroom['id'])) {
                $bedroom_row = Bedroom::find($bedroom['id']);
                $bedroom_row->update(["bathroom_type" => $bedroom['bathroom_type']]);
            } else {
                $bedroom_row = Bedroom::create(["accommodation_id" => $accommodation->id, "bathroom_type" => $bedroom['bathroom_type']]);
            }
            $bedroom_row->attachBedroomArrangements($bedroom['bedroom_arrangements']);
        }

        //save the amenities
        $amenities = $accommodation_data['amenities'];
        $accommodation->attachAmenities($amenities);
        $accommodation->load('images', 'bedrooms.bedroomArrangements', 'amenities');

        return response()->json(['message' => 'Accommodation Deleted.', 'accommodation' => $accommodation], 200);
    }

    public function saveAccommodationImages(Accommodation $accommodation)
    {
        $images_data = request()->validate([
            'images' => 'nullable',
            'imagesToBeDeleted' => 'nullable'
        ]);


        // save the images
        if (isset($images_data['images'])) {
            $images = json_decode($images_data['images']);
            foreach ($images as $image) {
                $accommodation->uploadImage($image);
            }
        }

        // delete the images that were deleted by the user
        if (isset($images_data['imagesToBeDeleted'])) {
            $imagesToBeDeleted = json_decode($images_data['imagesToBeDeleted']);
            foreach ($imagesToBeDeleted as $image) {
                $accommodation->deleteImage($image->id);
            }
        }

        return response()->json(['message' => 'Images saved.'], 200);
    }

    public function deleteAccommodation(Accommodation $accommodation)
    {
        $accommodation->delete();
        return response()->json(['message' => 'Accommodation Deleted.'], 200);
    }

    // This function stores the step data, which for now is just min_guests
    public function updateAccommodations(AccommodationsRequest $request, Venue $venue)
    {
        $accommodations_data = $request->validated();
        $venue_data = ['min_guests' => $accommodations_data['min_guests']];
        $venue->fill($venue_data);
        if ($venue->step < 8) {
            $venue->step = 8;
        } else {
            $venue->status = 1;
        }
        $venue->save();
        return response()->json([
            'message' => 'Venue accommodations were updated.',
            'venue' => $venue,
        ], 200);
    }

    public function updatePricing(PricingRequest $request, Venue $venue)
    {
        $pricing_data = $request->validated();

        if (isset($pricing_data['discounts'])) {
            $discounts = json_decode($pricing_data['discounts']);
            foreach ($discounts as $discount) {
                VenueDiscount::create(['venue_id' => $venue->id, 'from' => $discount->from, 'to' => $discount->to, 'percentage' => $discount->percentage]);
            }
        }

        if (isset($pricing_data['discounts_to_be_deleted'])) {
            $discountsToBeDeleted = json_decode($pricing_data['discounts_to_be_deleted']);
            foreach ($discountsToBeDeleted as $discount) {
                $discount = VenueDiscount::find($discount)->first();
                $discount->delete();
            }
        }

        $venue_data = ['price_per_night' => $pricing_data['price_per_night'], 'guests_included' => $pricing_data['guests_included'], 'additional_guest_price' => $pricing_data['additional_guest_price'], 'has_pricing_discounts' => $pricing_data['has_pricing_discounts']];
        $venue->fill($venue_data);
        if ($venue->step < 9) {
            $venue->step = 9;
        } else {
            $venue->status = 1;
        }
        $venue->save();
        $venue->load('discounts');
        return response()->json([
            'message' => 'Venue pricing was updated.',
            'venue' => $venue,
        ], 200);
    }

    public function updateMeals(MealsRequest $request, Venue $venue)
    {
        $meals_data = $request->validated();

        if (isset($meals_data['discounts'])) {
            $discounts = json_decode($meals_data['discounts']);
            foreach ($discounts as $discount) {
                VenueDiscount::create(['venue_id' => $venue->id, 'from' => $discount->from, 'to' => $discount->to, 'percentage' => $discount->percentage, 'type' => 1]);
            }
        }

        if (isset($meals_data['discounts_to_be_deleted'])) {
            $discountsToBeDeleted = json_decode($meals_data['discounts_to_be_deleted']);
            foreach ($discountsToBeDeleted as $discount) {
                $discount = VenueDiscount::find($discount)->first();
                $discount->delete();
            }
        }

        if (isset($meals_data['meals'])) {
            $venue->deleteMeals();
            $meals = json_decode($meals_data['meals']);
            foreach ($meals as $meal) {
                // dd($meal->title);
                $title = ["en" => $meal->title->en];
                // dd($title);
                Meal::create(['venue_id' => $venue->id, 'title' => $title, 'price' => $meal->price]);
            }
        }

        $venue_data = ['has_meal_discounts' => $meals_data['has_meal_discounts']];
        $venue->fill($venue_data);
        if ($venue->step < 10) {
            $venue->step = 10;
        } else {
            $venue->status = 1;
        }
        $venue->save();
        $venue->load('meals', 'discounts');
        return response()->json([
            'message' => 'Venue retreat organizer section was updated.',
            'venue' => $venue,
        ], 200);
    }

    public function updateRetreatOrganizer(RetreatOrganizerRequest $request, Venue $venue)
    {
        $retreatOrganizer_data = $request->validated();
        $venue->fill($retreatOrganizer_data);
        if ($venue->step < 11) {
            $venue->step = 11;
        } else {
            $venue->status = 1;
        }
        $venue->save();
        return response()->json([
            'message' => 'Venue retreat organizer section was updated.',
            'venue' => $venue,
        ], 200);
    }

    public function updateCheckIn(CheckInRequest $request, Venue $venue)
    {
        $checkIn_data = $request->validated();
        $venue->fill($checkIn_data);
        if ($venue->step < 12) {
            $venue->step = 12;
        } else {
            $venue->status = 1;
        }
        $venue->save();
        return response()->json([
            'message' => 'Venue retreat organizer section was updated.',
            'venue' => $venue,
        ], 200);
    }

    public function updateCancellationPolicy(CancellationPolicyRequest $request, Venue $venue)
    {
        $policy_data = $request->validated();
        if (isset($policy_data['id'])) {
            CancellationPolicy::updateOrCreate(['venue_id' => $venue->id], ['cancellation_policy_data_id' => $policy_data['id'], 'deposit' => Null, 'refund_percentage' => Null, 'days_before_arrival' => Null, 'remaining_balance_in_days' => Null]);
        } else {
            $policy_data['cancellation_policy_data_id'] = Null;
            CancellationPolicy::updateOrCreate(['venue_id' => $venue->id], $policy_data);
        }
        if ($venue->step < 13) {
            $venue->step = 13;
        } else {
            $venue->status = 1;
        }
        $venue->save();
        $venue->save();
        $venue->load('cancellationPolicy');
        return response()->json([
            'message' => 'Venue retreat organizer section was updated.',
            'venue' => $venue,
        ], 200);
    }
}
