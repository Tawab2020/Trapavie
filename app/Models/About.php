<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class About extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $translatable = ['main_title', 'secondary_title', 'excerpt', 'description', 'btn_text', 'team_title', 'team_excerpt'];


    public function updateAbout($request)
    {
        //NOT USED YET
        $about_data = $request->validated();
        if ($request->hasFile('bg_image')) {
            $image_path = $request->file('bg_image')->store('public/about');
            $bg_image = basename($image_path);
            $about_data['bg_image'] = $bg_image;
        }
        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('public/about');
            $image = basename($image_path);
            $about_data['image'] = $image;
        }
        $this->update($about_data);
    }
}
