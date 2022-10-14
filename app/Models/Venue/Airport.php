<?php

namespace App\Models\Venue;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Airport extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopeNear($query, $lat, $lng, $distance)
    {
        return $query->select('id', 'name', DB::raw('ROUND(6371 * acos(cos(radians(' . $lat . '))
        * cos(radians(lat)) * cos(radians(lng) - radians(' . $lng . '))
        + sin(radians(' . $lat . ')) * sin(radians(lat))),2) AS distance'))
            ->having('distance', '<=', $distance)
            ->orderBy('distance', 'ASC');
    }
}
