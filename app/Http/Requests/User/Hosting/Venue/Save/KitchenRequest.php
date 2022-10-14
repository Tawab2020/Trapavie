<?php

namespace App\Http\Requests\User\Hosting\Venue\Save;

use Illuminate\Foundation\Http\FormRequest;

class KitchenRequest extends FormRequest
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
            'food' => 'nullable',
            'kitchen' => 'nullable',
            'dining_capacity' => 'nullable|integer',
            'images' => 'nullable',
            'imagesToBeDeleted' => 'nullable'
        ];
    }
}
