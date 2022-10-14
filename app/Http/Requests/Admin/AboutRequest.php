<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
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
            'main_title' => 'required',
            'secondary_title' => 'required',
            'excerpt' => 'required',
            'description' => 'required',
            'btn_text' => 'required',
            'btn_url' => 'required',
            'team_title' => 'required',
            'team_excerpt' => 'required'
        ];
    }
}
