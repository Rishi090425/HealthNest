<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrescriptionRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'patient_id'          => 'required|exists:patients,id',
            'appointment_id'      => 'nullable|exists:appointments,id',
            'prescription_date'   => 'required|date',
            'notes'               => 'nullable|string|max:2000',
            'items'               => 'required|array|min:1',
            'items.*.medication_name' => 'required|string|max:255',
            'items.*.dosage'      => 'required|string|max:100',
            'items.*.frequency'   => 'required|string|max:100',
            'items.*.duration'    => 'required|string|max:100',
            'items.*.instructions'=> 'nullable|string|max:500',
        ];
    }
}
