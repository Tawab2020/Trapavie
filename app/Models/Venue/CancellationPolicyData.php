<?php

namespace App\Models\Venue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class CancellationPolicyData extends Model
{
    use HasFactory, HasTranslations;
    protected $translatable = ['title'];

    protected $guarded = [];

    public function cancelationPolicy()
    {
        return $this->hasMany(cancelationPolicy::class);
    }
}
