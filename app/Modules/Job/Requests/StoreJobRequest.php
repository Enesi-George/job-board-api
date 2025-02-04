<?php

namespace App\Modules\Job\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'company_logo' => ['file', 'sometimes', 'mimes:png,jpg,jpeg', 'max:5120'],
            'location' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'salary' => ['required', 'string', 'max:255'],
            'qualifications' => ['required', 'array'],
            'descriptions' => ['required', 'array'],
            'benefit' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:255'],
            'work_condition' => ['nullable', 'string'],
        ];
    }
}
