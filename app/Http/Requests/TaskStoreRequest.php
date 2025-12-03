<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // allow anyone for now (or apply auth rules)
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
            'created_by'  => 'required|exists:users,id',
            'title'       => 'required|string',
            'description' => 'required|string',
            'status'      => 'required|in:open,in_progress,completed',
        ];
    }

    public function messages(): array
    {
        return [
            'created_by.required'  => 'The creator is required.',
            'created_by.exists'    => 'The specified creator does not exist.',
            'title.required'       => 'The task title is required.',
            'description.required' => 'The task description is required.',
            'status.required'      => 'The task status is required.',
            'status.in'            => 'The task status must be Open, In Progress or Completed.',
        ];
    }
}
