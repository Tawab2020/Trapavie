<?php

namespace App\Models\Venue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueDiscount extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
