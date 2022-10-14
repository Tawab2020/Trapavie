<?php

namespace App\Http\Requests\User\Hosting\Venue\Save;

use Illuminate\Foundation\Http\FormRequest;

class NameAddressRequest extends FormRequest
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
        // All fields are from STEP 1 of the save venue wizard
        return [
            'user_id' => 'required',
            'name' => 'required',
            'currency_id' => 'required|exists:currencies,id',
            'property_type_id' => 'required|exists:property_types,id',
            'is_shared' => 'required',
            'country' => 'required',
            'street' => 'required',
            'street2' => 'nullable',
            'state' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'lat' => 'required',
            'lng' => 'required'

        ];
    }
}
