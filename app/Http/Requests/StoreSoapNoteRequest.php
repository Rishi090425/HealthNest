<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSoapNoteRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'appointment_id' => 'nullable|exists:appointments,id',
            'subjective'     => 'nullable|string|max:5000',
            'objective'      => 'nullable|string|max:5000',
            'assessment'     => 'nullable|string|max:5000',
            'plan'           => 'nullable|string|max:5000',
        ];
    }
}
