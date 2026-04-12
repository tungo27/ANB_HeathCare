<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $doctor = $this->route('doctor');
        $userId = $doctor instanceof \App\Models\Doctor ? $doctor->user_id : \App\Models\Doctor::find($doctor)?->user_id;

        return [
            'full_name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'specialty_id' => ['required', 'exists:specialties,id'],
            'qualification'       => ['required', 'string', 'max:255'],
            'years_of_experience' => ['required', 'integer', 'min:0'],
            'consultation_fee'    => ['required', 'numeric', 'min:0'],
            'bio'          => ['nullable', 'string'],
        ];
    }
}
