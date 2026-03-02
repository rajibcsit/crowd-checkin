<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'max_capacity' => 'required|integer|min:1'
        ];
    }

     public function messages(): array
        {
            return [
                'name.required' => 'Event name is required.',
                'max_capacity.required' => 'Maximum capacity is required.',
                'max_capacity.integer' => 'Capacity must be a number.',
                'max_capacity.min' => 'Capacity must be at least 1.'
            ];
        }
}
