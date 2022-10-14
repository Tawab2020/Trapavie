<?php

namespace App\Models\Venue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accommodation_VenueData extends Model
{
    use HasFactory;
    protected $guarded = [];

    // This will relate a venuedata row to a accommodation_venuedata pivot model row which is accessed in the Accommodation model.
    public function venueData()
    {
        return $this->hasOne(VenueData::class);
    }
}
