<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FaqCat extends Model
{
    use HasFactory, HasTranslations;
    protected $guarded = [];
    protected $translatable = ["title"];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class);
    }
}
