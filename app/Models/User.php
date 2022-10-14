<?php

namespace App\Models;

use App\Models\Venue\Venue;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Cashier\Billable;

use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            Mail::raw('block and unblock', function ($message) use ($model) {
                $message->to($model->email, 'Tutorials Point')
                    ->subject('Venue Booking Cancelled - No Deposit')
                    ->from('support@tripavie.com', 'Tripavie Support');
            });
        });
    }

    protected $guarded = [];

    protected $guard = 'user';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class)->withPivot('type');;
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function venues()
    {
        return $this->hasMany(Venue::class);
    }

    public function venueBookings()
    {
        return $this->hasManyThrough(VenueBooking::class, Venue::class)->orderBy('created_at', 'DESC');
    }
}
