<?php

namespace App\Models;

use App\Models\Venue\Accommodation;
use App\Models\Venue\Venue;
use App\Models\Venue\VenueData;
use App\Models\Venue\VenueDiscount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueBooking extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'venue_log' => 'array',
        'cancellation_policy_log' => 'array',
        'price' => 'array',
        'confirmed_at' => 'datetime',
        'deposit_paid_at' => 'datetime',
        'fully_paid_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refund_paid_at' => 'datetime'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function venueData()
    {
        return $this->belongsToMany(VenueData::class, 'venue_booking_venue_datum');
    }
}
