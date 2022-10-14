<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AboutRequest;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    public function get()
    {
        $about = About::where('id', 1)->first();
        return response()->json($about, 200);
    }

    public function update(AboutRequest $request)
    {
        $about_data = $request->validated();
        $about = About::where('id', 1);
        $about->update($about_data);

        return response()->json($about, 200);
    }
    public function uploadImages()
    {
        $about = About::where('id', 1)->first();
        $images = request()->validate([
            'bg_image' => 'image',
            'image' => 'image',
        ]);
        if (isset($images['bg_image'])) {
            $pre_image = $about->bg_image;
            if ($pre_image) {
                Storage::delete('public/about/' . $pre_image);
            }
            $image_path = request()->file('bg_image')->store('public/about');
            $about->update([
                'bg_image' => basename($image_path)
            ]);
        }
        if (isset($images['image'])) {
            $pre_image = $about->image;
            if ($pre_image) {
                Storage::delete('public/about/' . $pre_image);
            }
            $image_path = request()->file('image')->store('public/about');
            $about->update([
                'image' => basename($image_path)
            ]);
        }
        return response()->json(["data" => $about], 200);
    }
}
