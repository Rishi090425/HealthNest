<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'name'        => 'required|string|max:255|unique:departments,name,' . $this->route('department')?->id,
            'description' => 'nullable|string|max:1000',
        ];
    }
}
