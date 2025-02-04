<?php

namespace App\Modules\Job\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
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
            'title' => ['sometimes', 'string', 'max:255'],
            'company' => ['sometimes', 'string', 'max:255'],
            'company_logo' => ['file', 'sometimes', 'mimes:png,jpg,jpeg', 'max:5120'],
            'location' => ['sometimes', 'string', 'max:255'],
            'category' => ['sometimes', 'string', 'max:255'],
            'salary' => ['sometimes', 'string', 'max:255'],
            'qualifications' => ['sometimes', 'array'],
            'descriptions' => ['sometimes', 'array'],
            'benefit' => ['nullable', 'string'],
            'type' => ['sometimes', 'string', 'max:255'],
            'work_condition' => ['nullable', 'string'],
        ];
    }
}
