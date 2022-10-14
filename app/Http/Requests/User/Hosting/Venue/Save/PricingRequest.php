<?php

namespace App\Http\Requests\User\Hosting\Venue\Save;

use Illuminate\Foundation\Http\FormRequest;

class PricingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'price_per_night' => 'required',
            'guests_included' => 'nullable',
            'additional_guest_price' => 'nullable',
            'has_pricing_discounts' => 'required',
            'discounts' => 'nullable',
            'discounts_to_be_deleted' => 'nullable'
        ];
    }
}