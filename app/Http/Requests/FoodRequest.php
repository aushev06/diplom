<?php

namespace App\Http\Requests;

use App\Models\Food;
use Illuminate\Foundation\Http\FormRequest;

class FoodRequest extends FormRequest
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
            Food::ATTR_NAME  => 'required',
            Food::ATTR_PRICE => 'required'
        ];
    }

    public function messages()
    {
        return [
            implode('.', [Food::ATTR_NAME, 'required'])  => "Заполните название",
            implode('.', [Food::ATTR_PRICE, 'required']) => "Укажите цену",
        ];
    }
}
