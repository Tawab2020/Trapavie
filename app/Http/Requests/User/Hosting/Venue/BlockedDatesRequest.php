<?php

namespace App\Http\Requests\User\Hosting\Venue;

use Illuminate\Foundation\Http\FormRequest;

class BlockedDatesRequest extends FormRequest
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
            'start' => 'required|date',
            'end' => 'required|date',
            'venue_id' => 'required'
        ];
    }
}
