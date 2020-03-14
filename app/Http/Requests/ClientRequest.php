<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            Client::ATTR_NAME => 'required',
        ];
    }

    public function messages()
    {
        return [
            Client::ATTR_NAME . '.required' => 'Введите имя клиента'
        ];
    }
}
