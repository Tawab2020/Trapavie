<?php

namespace App\Models\Venue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class VenueDataCategory extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $translatable = ['title'];

    public function venueData()
    {
        return $this->hasMany(VenueData::class)->where('user_id', NULL);
    }

    public function userVenueData()
    {
        $user = auth('user')->user();
        return $this->hasMany(VenueData::class)->where('user_id', $user->id)->orWhere('user_id', NULL);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
