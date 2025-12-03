<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
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
            'title'       => 'required|string',
            'description' => 'required|string',
            'status'      => 'required|in:open,in_progress,completed',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'The task title is required.',
            'description.required' => 'The task description is required.',
            'status.required'      => 'The task status is required.',
            'status.in'            => 'The task status must be Open, In Progress or Completed.',
        ];
    }
}
