<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $payload = [];

        // Ensure mobile number has +63 prefix
        if ($this->mobile_number && !str_starts_with($this->mobile_number, '+63')) {
            $payload['mobile_number'] = '+63' . $this->mobile_number;
        }

        if ($this->has('admin_registration_key')) {
            $payload['admin_registration_key'] = trim((string) $this->input('admin_registration_key'));
        }

        if (!empty($payload)) {
            $this->merge($payload);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', 'unique:users', 'alpha_dash'],
            'name' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'role' => ['required', 'in:admin,user'],
            'admin_registration_key' => ['nullable', 'string', 'required_if:role,admin'],
            'mobile_number' => ['required', 'string', 'regex:/^\+63[0-9]{10}$/'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes and underscores.',
            'username.unique' => 'This username is already taken.',
            'birthdate.before' => 'Birthdate must be before today.',
            'birthdate.after' => 'Please enter a valid birthdate.',
            'admin_registration_key.required_if' => 'Admin registration key is required for admin accounts.',
            'mobile_number.regex' => 'Please enter a valid Philippine mobile number (10 digits after +63).',
            'password.min' => 'Password must be at least 8-12 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    /**
     * Configure additional validation logic.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->input('role') !== 'admin') {
                return;
            }

            $expectedKey = (string) config('auth.admin_registration_key', '');
            $providedKey = (string) $this->input('admin_registration_key', '');

            if ($expectedKey === '') {
                $validator->errors()->add(
                    'admin_registration_key',
                    'Admin registration is currently disabled. Please contact the system administrator.'
                );
                return;
            }

            if (!hash_equals($expectedKey, $providedKey)) {
                $validator->errors()->add('admin_registration_key', 'Invalid admin registration key.');
            }
        });
    }
}
