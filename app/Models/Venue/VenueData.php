<?php

namespace App\Models\Venue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class VenueData extends Model
{
    use HasFactory, HasTranslations;
    protected $translatable = ['title'];
    protected $guarded = [];

    public function venueDataCategory()
    {
        return $this->belongsTo(VenueDataCategory::class);
    }

    public function venues()
    {
        return $this->belongsToMany(Venue::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
