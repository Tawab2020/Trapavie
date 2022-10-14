<?php

namespace App\Http\Requests\User\Hosting\Venue\Save;

use Illuminate\Foundation\Http\FormRequest;

class AccommodationRequest extends FormRequest
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
            'id' => 'nullable',
            'venue_id' => 'required',
            'name' => 'required',
            'max_guests' => 'required|integer',
            'quantity' => 'required',
            'accommodation_type_id' => 'required',
            'accommodationType' => 'nullable',
            'bedrooms' => 'required',
            'number_of_bathrooms' => 'nullable',
            'description' => 'required',
            'amenities' => 'required'
        ];
    }
}
