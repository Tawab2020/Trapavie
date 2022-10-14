<?php

namespace App\Models\Venue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CancellationPolicy extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function cancellationPolicyData()
    {
        return $this->belongsTo(CancellationPolicyData::class);
    }
}
