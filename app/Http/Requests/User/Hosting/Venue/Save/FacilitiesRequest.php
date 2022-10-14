<?php

namespace App\Http\Requests\User\Hosting\Venue\Save;

use Illuminate\Foundation\Http\FormRequest;

class FacilitiesRequest extends FormRequest
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
            'general_facilities' => 'required',
            'indoor_facilities' => 'required',
            'outdoor_facilities' => 'required',
            'images' => 'nullable',
            'imagesToBeDeleted' => 'nullable'
        ];
    }
}
