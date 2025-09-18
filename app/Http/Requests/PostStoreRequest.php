<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
                'filial' => 'required',
                'lavozim' => 'required',
                'name' => 'required|min:10|max:100',
                'email' => 'required|email||max:100',
                'password' => 'required|min:8|max:25',
            ];
    }
    public function messages()
    {
        return[
            'filial.required'=>'Филиал танланг.!',
            'lavozim.required'=>'Лавозимини танланг.!',
            'name.required'=>'ФИО ни киритинг.!',
            'email.required'=>'Emailни киритинг.!',
            'password.required'=>'Паролни киритинг.!',
        ];
    }
}
