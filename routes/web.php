<?php

use App\Jobs\SendReminderEmails;
use App\Models\VenueBooking;
use App\Notifications\RetreatOrganizer\BookingCancelledNoDeposit;
use App\Notifications\RetreatOrganizer\DepositReminder;
use App\Notifications\RetreatOrganizer\PayRemainingBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/test', function () {
    return view('test');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Verify Email
Route::get('/email/verify/{id}/{hash}', 'User\AuthController@verifyUserEmail')
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Google Auth
Route::get('/auth/redirect/google', 'User\AuthController@handleGoogleRedirect');
Route::get('/auth/callback/google', 'User\AuthController@handleGoogleCallback');

// Facebook Auth ???
Route::get('/auth/redirect/facebook', 'User\AuthController@handleFacebookRedirect');
Route::get('/auth/callback/facebook', 'User\AuthController@handleFacebookCallback');


// ADMIN ROUTES
Route::get('admin/{any?}', 'Admin\MasterController@index')->where('any', '.*');

// USER ROUTES
Route::get('{any?}', 'User\Frontend\MasterController@index')->where('any', '.*')->name('home');
