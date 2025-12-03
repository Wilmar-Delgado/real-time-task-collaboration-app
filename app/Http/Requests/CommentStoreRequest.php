<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentStoreRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'task_id' => 'required|exists:tasks,id',
            'body'    => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The user is required.',
            'user_id.exists'   => 'The specified user does not exist.',
            'task_id.required' => 'The task is required.',
            'task_id.exists'   => 'The specified task does not exist.',
            'body.required'    => 'The comment body is required.',
        ];
    }
}
