<?php

namespace App\Models\Venue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bedroom extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    //modify
    public function bedroomArrangements()
    {
        return $this->belongsToMany(VenueData::class)->where('venue_data_category_id', 13)->withPivot(['quantity']);
    }

    public function attachBedroomArrangements($arrangements)
    {
        $this->bedroomArrangements()->detach();
        foreach ($arrangements as $arrangement) {
            //take quantity from pivot
            $this->bedroomArrangements()->attach($arrangement['id'], ['quantity' => $arrangement['pivot']['quantity']]);
        }
    }

    public function delete()
    {
        $this->bedroomArrangements()->detach();
        Parent::delete();
    }
}
