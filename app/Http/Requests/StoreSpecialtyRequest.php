<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpecialtyRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'name'          => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description'   => 'nullable|string|max:1000',
        ];
    }
}
