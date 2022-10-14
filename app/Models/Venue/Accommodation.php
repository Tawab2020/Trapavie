<?php

namespace App\Models\Venue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Accommodation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function bedrooms()
    {
        return $this->hasMany(Bedroom::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(VenueData::class)->where('venue_data_category_id', 12);
    }

    public function attachAmenities($amenities)
    {
        $this->amenities()->detach();
        foreach ($amenities as $amenity) {
            $this->amenities()->attach($amenity['id']);
        }
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    // Upload images of accommodations
    public function uploadImage($image)
    {
        if ($pre_img = $this->images->where('id', $image->id)->first()) {
            Storage::delete('public/venue/' . $pre_img->image);
            $image_name = uploadBase64Image($image->result, 'public/venue/');
            $this->images->where('id', $image->id)->first()->update(['image' => $image_name, 'caption' => $image->caption]);
            return;
        }
        $image_name = uploadBase64Image($image->result, 'public/venue/');
        $this->images()->create(['image' => $image_name, 'caption' => $image->caption]);
    }

    public function deleteImage($imgId)
    {
        $image = Image::find($imgId);
        Storage::delete('public/venue/' . $image->image);
        $image->delete();
    }

    public function delete()
    {
        foreach ($this->images as $image) {
            $this->deleteImage($image->id);
        }
        $this->amenities()->detach();
        Parent::delete();
    }
}
