<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'         => 'required|string|max:255',
            'surname'      => 'required|string|max:255',
            'email'        => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->route('id')),
            ],
            'phone'        => 'required|string|max:20',
            'country'      => 'required|string',
            'gender'       => 'required|string|in:male,female',
            'password'     => 'nullable|string|min:6|confirmed',
            'selfie'       => 'nullable|image|max:2048|mimes:jpeg,png,jpg',
            'introduction' => 'nullable|string',
        ];
    }
}
