<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CurrencyRequest;
use App\Models\Currency;
use Illuminate\Support\Facades\Storage;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        return response()->json($currencies, 200);
    }

    public function store(CurrencyRequest $request)
    {
        $currency_data = $request->validated();
        $currency = Currency::create($currency_data);
        return response()->json($currency, 200);
    }

    public function show(Currency $currency)
    {
        return response()->json($currency, 200);
    }

    public function update(CurrencyRequest $request, Currency $currency)
    {
        $currency_data = $request->validated();
        $currency->update($currency_data);
        return response()->json($currency, 200);
    }

    public function destroy(Currency $currency)
    {
        if ($currency->is_used) {
            return response()->json(['message' => 'Can not delete a currency that is already used.'], 500);
        }
        $currency->delete();
        return response()->json(['message' => 'Currency Deleted'], 200);
    }
}
