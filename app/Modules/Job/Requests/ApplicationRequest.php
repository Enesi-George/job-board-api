<?php

namespace App\Modules\Job\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'regex:/^(?:\+234|0)[789][01]\d{8}$/',
            'location' => ['required', 'string', 'max:255'],
            'document' => ['required', 'file', 'mimes:pdf,docx', 'max:2048']
        ];
    }
}
