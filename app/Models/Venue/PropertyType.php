<?php

namespace App\Models\Venue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PropertyType extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $translatable = ['title'];

    public function accommodationTypes()
    {
        return $this->belongsToMany(AccommodationType::class)->where('user_id', NULL);
    }

    public function userAccommodationTypes()
    {
        $user = auth('user')->user();
        return $this->belongsToMany(AccommodationType::class)->where('user_id', $user->id)->orWhere('user_id', NULL);
    }

    public function attachAccommodationTypes($accommodationTypes)
    {
        $this->accommodationTypes()->detach($this->accommodationTypes);
        foreach ($accommodationTypes as $type) {
            $this->accommodationTypes()->attach($type['id']);
        }
    }
}
