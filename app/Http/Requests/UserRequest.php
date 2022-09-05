<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|max:100',
            'username_color' => 'required|string|max:100',
        ];
    }
}
