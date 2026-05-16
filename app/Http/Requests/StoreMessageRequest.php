<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'receiver_id' => 'required|exists:users,id',
            'content'     => 'required|string|max:5000',
        ];
    }
}
