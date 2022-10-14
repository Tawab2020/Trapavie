<?php

namespace App\Models\Venue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Meal extends Model
{
    use HasFactory, HasTranslations;
    protected $translatable = ['title'];
    protected $guarded = [];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
