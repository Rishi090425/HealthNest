<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVitalRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'recorded_at'                => 'required|date',
            'blood_pressure_systolic'    => 'nullable|integer|between:50,300',
            'blood_pressure_diastolic'   => 'nullable|integer|between:30,200',
            'heart_rate'                 => 'nullable|integer|between:20,300',
            'temperature'                => 'nullable|numeric|between:30,45',
            'respiratory_rate'           => 'nullable|integer|between:5,60',
            'weight'                     => 'nullable|numeric|between:1,500',
            'height'                     => 'nullable|numeric|between:30,250',
            'oxygen_saturation'          => 'nullable|integer|between:50,100',
            'blood_glucose'              => 'nullable|integer|between:20,600',
        ];
    }
}
