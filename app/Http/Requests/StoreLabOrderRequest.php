<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLabOrderRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'patient_id'  => 'required|exists:patients,id',
            'order_date'  => 'required|date',
            'test_type'   => 'required|string|max:255',
            'notes'       => 'nullable|string|max:1000',
        ];
    }
}
