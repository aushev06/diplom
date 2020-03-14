<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            Order::ATTR_DATE_DELIVERY => 'required',
            Order::ATTR_CLIENT_ID     => 'required',
            'cart'                    => 'required',
            'cart.*.count'            => ['required', 'min:1'],
            'cart.*.unit'             => 'required',
        ];
    }

    public function messages()
    {
        return [
            implode('.', [Order::ATTR_DATE_DELIVERY, 'required']) => 'Установите дату и время',
            implode('.', [Order::ATTR_CLIENT_ID, 'required'])     => 'Выберите клиента',
            'cart.required'                                       => 'Выберите блюда',
            'cart.*.count.required'                               => 'Установите везде количество',
            'cart.*.unit.required'                                => 'Установите везде единицу измерения',
            'cart.*.count.min'                                    => 'Выберите количество больше 0',
        ];
    }
}
