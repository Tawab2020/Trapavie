<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeeRequest;
use App\Models\Fee;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function get()
    {
        $fee = Fee::where('id', 1)->first();
        return response()->json($fee, 200);
    }

    public function update(FeeRequest $request)
    {
        $fee_data = $request->validated();
        $fee = Fee::where('id', 1);
        $fee->update($fee_data);

        return response()->json($fee, 200);
    }
}
