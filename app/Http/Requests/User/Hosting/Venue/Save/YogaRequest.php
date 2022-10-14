<?php

namespace App\Http\Requests\User\Hosting\Venue\Save;

use Illuminate\Foundation\Http\FormRequest;

class YogaRequest extends FormRequest
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
            'yoga_spaces' => 'required',
            'indoor_capacity' => 'nullable|integer',
            'indoor_yoga_equipments' => 'nullable',
            'outdoor_capacity' => 'nullable|integer',
            'outdoor_yoga_equipments' => 'nullable',
            'images' => 'nullable',
            'imagesToBeDeleted' => 'nullable'
        ];
    }
}
