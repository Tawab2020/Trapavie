<?php

namespace App\Http\Requests\User\Hosting\Venue\Save;

use Illuminate\Foundation\Http\FormRequest;

class RetreatOrganizerRequest extends FormRequest
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
            'free_accommodation_for_retreat_organizer' => 'required',
            'min_guests_for_free_accommodation' => 'nullable',
            'min_nights_for_free_accommodation' => 'nullable',
            'free_meal_for_retreat_organizer' => 'required',
            'min_guests_for_free_meal' => 'nullable',
            'min_nights_for_free_meal' => 'nullable',

        ];
    }
}
