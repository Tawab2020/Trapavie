<?php

namespace App\Http\Requests\Admin;

use Elegant\Sanitizer\Laravel\SanitizesInput;
use Illuminate\Foundation\Http\FormRequest;

class CurrencyRequest extends FormRequest
{
    use SanitizesInput;

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
            'name' => 'required',
            'code' => 'required|unique:currencies,code,' . $this->id,
            'symbol' => 'required'
        ];
    }

    public function filters()
    {
        return [
            'code' => 'trim|uppercase',
        ];
    }
}
