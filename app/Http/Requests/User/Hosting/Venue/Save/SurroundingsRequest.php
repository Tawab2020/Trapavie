<?php

namespace App\Http\Requests\User\Hosting\Venue\Save;

use Illuminate\Foundation\Http\FormRequest;

class SurroundingsRequest extends FormRequest
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
            'surroundings' => 'required',
            'things_to_do' => 'required',
            'rentals' => 'required',
            'images' => 'nullable',
            'imagesToBeDeleted' => 'nullable'
        ];
    }
}
