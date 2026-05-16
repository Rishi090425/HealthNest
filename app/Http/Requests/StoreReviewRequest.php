<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'doctor_id'      => 'required|exists:doctors,id',
            'appointment_id' => 'required|exists:appointments,id',
            'rating'         => 'required|integer|between:1,5',
            'comment'        => 'nullable|string|max:1000',
        ];
    }
}
