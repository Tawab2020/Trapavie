<?php

namespace App\Http\Requests\User\Hosting\Venue\Save;

use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
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
            'min_nights' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
            'extra_time_between_bookings' => 'required',
        ];
    }
}
