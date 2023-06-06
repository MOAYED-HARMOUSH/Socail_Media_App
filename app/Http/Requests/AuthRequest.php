<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $date = now()->subYears(12);
        $specialty = ['AI', 'Software', 'Cyber Security', 'Network'];
        $section = [];
        return [
            'first_name' => 'bail|required|string|alpha',
            'last_name' => 'bail|required|string|alpha',
            'email' => 'bail|required|email',
            'password' => 'bail|required|confirmed|string|min:8',
            'image_path' => 'bail|nullable|image|mimes:jpg,bmp,png,svg,jpeg',
            'current_location' => 'bail|required|string',
            // 'gender' => 'bail|required|string|in:male,female',
            'birth_date' => "bail|date|before_or_equal:$date",
            'programming_age' => "bail|date|before_or_equal:$date",
            // 'specialty' => ['bail', 'required', Rule::in($specialty)],
            // 'specialty' => 'bail|required|exists:specialties',
            // 'section' => 'required'
        ];
    }
}
