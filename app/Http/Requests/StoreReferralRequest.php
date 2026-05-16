<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReferralRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'patient_id'         => 'required|exists:patients,id',
            'referred_doctor_id' => 'required|exists:doctors,id|different:referring_doctor_id',
            'reason'             => 'required|string|max:2000',
        ];
    }
}
