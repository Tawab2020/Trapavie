<?php

namespace App\Models\Venue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

class AccommodationType extends Model
{
    use HasFactory, HasTranslations;
    protected $translatable = ['title'];
    protected $guarded = [];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function uploadImage($image)
    {
        $image_path = $image->store('public/venue/data/accommodationTypes');
        $pre_image = $this->image;
        if ($pre_image) {
            Storage::delete('public/venue/data/accommodationTypes/' . $pre_image->image);
            $this->image()->first()->update(['image' => basename($image_path)]);
        } else {
            $this->image()->create(['image' => basename($image_path)]);
        }
    }
}
