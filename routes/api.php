<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Verify email handled in web.php

// Resend link to verify email
Route::post('/email/verify/resend', 'User\AuthController@sendEmailVerification')->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');

// ADMIN ROUTES

Route::post('admin/auth/login', 'Admin\AuthController@login')->name('admin.auth.login');

Route::group(
    [
        'middleware' => 'auth:admin',
        'prefix' => 'admin',
        'name' => 'admin'
    ],

    // Auth
    function ($router) {
        Route::group([
            'prefix' => 'auth',
            'name' => 'auth'
        ], function ($router) {
            Route::post('/logout', 'Admin\AuthController@logout')->name('logout');
            Route::post('/refresh', 'Admin\AuthController@refresh')->name('refresh');
            Route::post('/adminProfile', 'Admin\AuthController@adminProfile')->name('adminProfile');
        });


        route::get('users',"Admin\UserController@index");
        route::patch('block-unblock/{id}',"Admin\UserController@blockUnblock");
        route::patch('verify/user/{id}',"Admin\UserController@verifyId");
        // INITIALIZE
        Route::get('initialize', 'Admin\MasterController@initialize');

        // Currencies
        Route::apiResource('currency', 'Admin\CurrencyController');
        Route::put('currency/{currency}/uploadSymbol', 'Admin\CurrencyController@uploadSymbol')->name('currency.uploadSymbol');


        // Profile
        Route::put('update/{admin}', 'Admin\AdminController@updateProfile')->name('profile.update');
        Route::put('update/{admin}/uploadImage', 'Admin\AdminController@uploadImage')->name('user.uploadImage');
        Route::put('changePassword/{admin}', 'Admin\AdminController@changePassword')->name('changePassword');

        // About
        Route::get('about', 'Admin\AboutController@get')->name('about.get');
        Route::put('about/update', 'Admin\AboutController@update')->name('about.update');
        Route::put('about/uploadImages', 'Admin\AboutController@uploadImages')->name('about.uploadImages');

        // Team Members
        Route::apiResource('teamMember', 'Admin\TeamMemberController');
        Route::put('teamMember/{teamMember}/uploadImage', 'Admin\TeamMemberController@uploadImage')->name('teamMember.uploadImage');

        // Language Routes
        Route::apiResource('language', 'Admin\LanguageController');
        Route::put('language/{language}/uploadIcon', 'Admin\LanguageController@uploadIcon')->name('language.uploadIcon');
        Route::put('language/{language}/deactivate', 'Admin\LanguageController@deactivate')->name('language.deactivate');
        Route::put('language/{language}/activate', 'Admin\LanguageController@activate')->name('language.activate');
        // Translation Routes
        Route::get('language/{lang}/loadTranslations', 'Admin\TranslationController@loadTranslations')->name('language.loadTranslations');
        Route::post('language/{lang}/update', 'Admin\TranslationController@changeLangFileContent')->name("translation.updateTranslationFile");

        // FAQ Routes
        Route::apiResource('faqCat', 'Admin\FaqCatController');
        Route::apiResource('faq', 'Admin\FaqController');


        // retreats
        route::group(['prefix'=>'retreats','namespace'=>'Admin\Retreat'],function (){
            route::get('/',"RetreatController@index");
            route::get('categories/bare',"RetreatController@bareCategories");
            route::get('category/show/{id}',"RetreatController@showCategory");
            route::get('show/{id}',"RetreatController@show");
            route::post("category/store",'RetreatController@storeCategory');
            route::post("store",'RetreatController@store');
            route::post('category/{id}','RetreatController@updateCategory');
            route::post('update/{id}','RetreatController@updateRetreat');
            route::post('change-status/{id}','RetreatController@changeStatus');
            route::delete('delete/{id}','RetreatController@destroy');
        });

        //retreats end

        // Venues
        Route::get('venues', 'Admin\Venue\VenueController@index')->name('venues.index');
        Route::get('venues/pending', 'Admin\Venue\VenueController@pendingVenues')->name('venues.pending');
        Route::get('venues/live', 'Admin\Venue\VenueController@liveVenues')->name('venues.live');
        Route::get('venues/rejected', 'Admin\Venue\VenueController@rejectedVenues')->name('venues.rejected');
        Route::post('venue/{venue}/approve', 'Admin\Venue\VenueController@approve')->name('venue.approve');
        Route::post('venue/{venue}/reject', 'Admin\Venue\VenueController@reject')->name('venue.reject');
        Route::post('venue/{venue}/unApprove', 'Admin\Venue\VenueController@unApprove')->name('venue.unApprove');
        route::get('bookings/{id}','Admin\Venue\VenueController@fetchBookings');
        // Venue Dynamic Data
        Route::apiResource('venueDataCategory', 'Admin\Venue\VenueDataCategoryController');
        Route::put('venueDataCategory/{venueDataCategory}/uploadIcon', 'Admin\Venue\VenueDataCategoryController@uploadIcon')->name('venueDataCategory.uploadIcon');
        Route::apiResource('venueData', 'Admin\Venue\VenueDataController');
        Route::put('venueData/{venueData}/uploadIcon', 'Admin\Venue\VenueDataController@uploadIcon')->name('venueData.uploadIcon');

        // Venue Property Type
        Route::apiResource('propertyType', 'Admin\Venue\PropertyTypeController');

        // Accommodation Type
        Route::apiResource('accommodationType', 'Admin\Venue\AccommodationTypeController');
        Route::put('accommodationType/{accommodationType}/uploadIcon', 'Admin\Venue\AccommodationTypeController@uploadIcon')->name('accommodationType.uploadIcon');

        // Cancellation Policy Data
        Route::apiResource('cancellationPolicyData', 'Admin\Venue\CancellationPolicyDataController');

        // Transaction and Admin fees
        Route::get('fee', 'Admin\FeeController@get')->name('fee.get');
        Route::put('fee/update', 'Admin\FeeController@update')->name('fee.update');
    }
);


// USER ROUTES

// INITIALIZE
Route::get('initialize', 'User\Frontend\MasterController@initialize');

// Load Translations
Route::get('loadTranslations/{lang}', 'User\Frontend\MasterController@loadTranslations')->name('loadTranslations');

// Auth
Route::post('auth/login', 'User\AuthController@login')->name('auth.login');
Route::post('auth/register', 'User\AuthController@register')->name('auth.register');
Route::post('auth/logout', 'User\AuthController@logout')->name('auth.logout');

Route::post('auth/loginWithGoogleId', 'User\AuthController@loginWithGoogleId');

// Profile Routes
Route::get('userDetail/{user}', 'User\UserDetailController@show')->name('userDetail.show');
Route::put('userDetail/{user}', 'User\UserDetailController@update')->name('userDetail.update');
Route::put('userDetail/{userDetail}/uploadId', 'User\UserDetailController@uploadId')->name('userDetail.uploadId');
Route::put('user/{user}', 'User\UserDetailController@updateUser')->name('user.update');
Route::put('user/{user}/uploadImage', 'User\UserDetailController@uploadImage')->name('user.uploadImage');
// Countries
Route::get('getCountries', 'User\CountryController@getCountries')->name('countries');
Route::post('country/{country}/getShortname', 'User\CountryController@getShortName')->name('country.getShortName');
Route::get('country/{country}/getTwoCharacterName', 'User\CountryController@getTwoCharacterName')->name('country.getTwoCharacterName');
Route::post('getStates', 'User\CountryController@getStates')->name('country.states');
Route::post('getCurrency', 'User\CountryController@getCurrency')->name('country.currency');
Route::post('getCities', 'User\CountryController@getCities')->name('state.cities');

// About Routes
Route::get('about', 'User\Frontend\AboutController@index')->name('about.index');

// Venue Routes
Route::get('venues/homepageVenues', 'User\Frontend\VenueController@homepageVenues')->name('venue.homepageVenues');
Route::post('venues/search', 'User\Frontend\VenueController@search')->name('venue.search');
Route::get('venue/show/{venue}', 'User\Frontend\VenueController@show')->name('venue.show');
Route::get('cancellationPolicies', 'User\Frontend\MasterController@cancellationPolicies');

Route::group(
    [
        'middleware' => 'auth:user',
    ],

    // Auth
    function () {
        // User
        Route::get('userInfo/{user}', 'User\Frontend\UserController@userInfo')->name('user.userInfo');
        // Notifications
        Route::get('unreadNotifications', 'User\Frontend\UserController@unreadNotifications')->name('user.unreadNotifications');
        Route::get('markNotificationAsRead/{notification}', 'User\Frontend\UserController@markNotificationAsRead')->name('user.markNotificationAsRead');
        // Stripe Charging
        Route::post('user/chargeUser', 'User\Frontend\UserController@chargeUser')->name('user.chargeUser');

        // Message / Chat Routes
        Route::post('createConversation', 'User\Frontend\MessageController@createConversation')->name('chat.conversation.create');
        Route::get('conversations', 'User\Frontend\MessageController@conversations')->name('chat.conversations');
        Route::get('conversation/{conversation}/messages', 'User\Frontend\MessageController@messages')->name('chat.conversation.messages');
        Route::post('sendMessage', 'User\Frontend\MessageController@storeMessage')->name('chat.sendMessage');

        // Booking
        Route::post('venue/{venue}/booking/getPrice', 'User\Frontend\VenueBookingController@getPrice')->name('venue.booking.getPrice');
        Route::post('venue/{venue}/book', 'User\Frontend\VenueBookingController@create')->name('venue.booking.create');
        Route::post('venue/{venue}/sendMessage', 'User\Frontend\VenueBookingController@sendMessage')->name('venue.booking.sendMessage');
        Route::get('venue/booking/{venueBooking}', 'User\Frontend\VenueBookingController@show')->name('venue.booking.show');
        Route::get('venue/booking/{venueBooking}/changeBookingStatusToDepositPaid', 'User\Frontend\VenueBookingController@changeBookingStatusToDepositPaid')->name('venue.booking.changeBookingStatusToDepositPaid'); // Here the user confirms it after paying. TODO: Make this secure
        Route::get('venue/booking/{venueBooking}/changeBookingStatusToFullyPaid', 'User\Frontend\VenueBookingController@changeBookingStatusToFullyPaid')->name('venue.booking.changeBookingStatusToFullyPaid'); // Here the user confirms it after paying. TODO: Make this secure



        // Hosting
        //Venues
        Route::get('hosting/venues/listUserVenues', 'User\Hosting\Venue\VenueController@listUserVenues')->name('hosting.venue.listUserVenues');
        Route::put('hosting/venues/{venue}/pauseVenue', 'User\Hosting\Venue\VenueController@pauseVenue')->name('hosting.venue.pause');
        Route::put('hosting/venues/{venue}/activateVenue', 'User\Hosting\Venue\VenueController@activateVenue')->name('hosting.venue.activate');
        Route::put('hosting/venues/{venue}/deleteVenue', 'User\Hosting\Venue\VenueController@deleteVenue')->name('hosting.venue.delete');

        // Venue Save
        Route::get('venue/{venue}', 'User\Hosting\Venue\SaveVenueController@show')->name('venue.show');
        Route::post('venue/store/nameAddress', 'User\Hosting\Venue\SaveVenueController@storeNameAddress')->name('venue.storeNameAddress');
        Route::put('venue/{venue}/updateNameAddress', 'User\Hosting\Venue\SaveVenueController@updateNameAddress')->name('venue.updateNameAddress');
        Route::get('venue/{venue}/getAirports', 'User\Hosting\Venue\SaveVenueController@getAirports')->name('venue.getAirports');
        Route::put('venue/{venue}/arrival', 'User\Hosting\Venue\SaveVenueController@updateArrival')->name('venue.updateArrival');
        Route::put('venue/{venue}/descriptionFeatures', 'User\Hosting\Venue\SaveVenueController@updateDescriptionFeatures')->name('venue.updateDescriptionFeatures');
        Route::put('venue/{venue}/updateFacilities', 'User\Hosting\Venue\SaveVenueController@updateFacilities')->name('venue.updateFacilities');
        Route::put('venue/{venue}/updateYoga', 'User\Hosting\Venue\SaveVenueController@updateYoga')->name('venue.updateYoga');
        Route::put('venue/{venue}/updateSurroundings', 'User\Hosting\Venue\SaveVenueController@updateSurroundings')->name('venue.updateSurroundings');
        Route::put('venue/{venue}/updateKitchen', 'User\Hosting\Venue\SaveVenueController@updateKitchen')->name('venue.updateKitchen');
        Route::put('venue/{venue}/updateAccommodations', 'User\Hosting\Venue\SaveVenueController@updateAccommodations')->name('venue.updateAccommodations');
        Route::put('venue/saveAccommodation/{accommodation?}', 'User\Hosting\Venue\SaveVenueController@saveAccommodation')->name('venue.saveAccommodation');
        Route::put('venue/saveAccommodationImages/{accommodation}', 'User\Hosting\Venue\SaveVenueController@saveAccommodationImages')->name('venue.saveAccommodationImages');
        Route::delete('venue/deleteAccommodation/{accommodation}', 'User\Hosting\Venue\SaveVenueController@deleteAccommodation')->name('venue.deleteAccommodation');
        Route::put('venue/{venue}/updatePricing', 'User\Hosting\Venue\SaveVenueController@updatePricing')->name('venue.updatePricing');
        Route::put('venue/{venue}/updateMeals', 'User\Hosting\Venue\SaveVenueController@updateMeals')->name('venue.updateMeals');
        Route::put('venue/{venue}/updateRetreatOrganizer', 'User\Hosting\Venue\SaveVenueController@updateRetreatOrganizer')->name('venue.updateRetreatOrganizer');
        Route::put('venue/{venue}/updateCheckIn', 'User\Hosting\Venue\SaveVenueController@updateCheckIn')->name('venue.updateCheckIn');
        Route::put('venue/{venue}/updateCancellationPolicy', 'User\Hosting\Venue\SaveVenueController@updateCancellationPolicy')->name('venue.updateCancellationPolicy');


        //Calendar
        Route::post('hosting/venues/blockDates', 'User\Hosting\Venue\VenueController@blockDates')->name('hosting.venue.blockDates');
        Route::put('hosting/venues/deleteBlockedDates/{blockedDates}', 'User\Hosting\Venue\VenueController@deleteBlockedDates')->name('hosting.venue.deleteBlockedDates');

        Route::post('hosting/venues/addSeasonPricing', 'User\Hosting\Venue\VenueController@addSeasonPricing')->name('hosting.venue.addSeasonPricing');
        Route::put('hosting/venues/deleteSeasonPricing/{seasonPricing}', 'User\Hosting\Venue\VenueController@deleteSeasonPricing')->name('hosting.venue.deleteSeasonPricing');

        //Bookings
        Route::get('hosting/bookings/listVenueBookings', 'User\Hosting\BookingController@listVenueBookings')->name('hosting.booking.listVenueBookings');
        Route::get('hosting/booking/{venueBooking}', 'User\Hosting\BookingController@show')->name('hosting.booking.show');
        Route::get('hosting/bookings/confirmVenueBooking/{venueBooking}', 'User\Hosting\BookingController@confirmVenueBooking')->name('hosting.booking.confirmVenueBooking'); // Here the hosts confirm it
        Route::get('hosting/bookings/rejectVenueBooking/{venueBooking}', 'User\Hosting\BookingController@rejectVenueBooking')->name('hosting.booking.rejectVenueBooking');
    }
);
