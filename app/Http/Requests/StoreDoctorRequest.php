<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Thực tế bạn có thể áp dụng Policies/Gate cho admin tại đây
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'specialty_id' => ['required', 'exists:specialties,id'],
            'qualification'       => ['required', 'string', 'max:255'],
            'years_of_experience' => ['required', 'integer', 'min:0'],
            'consultation_fee'    => ['required', 'numeric', 'min:0'],
            'bio'          => ['nullable', 'string'],
        ];
    }
}
