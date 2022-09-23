<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username_id' => 'required|integer',
            'message' => 'required|string|max:148',
            'date' => 'required|string|max:10',
            'hour' => 'required|string|max:8',
            'token' => 'required|string'
        ];
    }
}
