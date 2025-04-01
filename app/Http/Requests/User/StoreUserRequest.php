<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email'        => 'required|email|unique:users,email',
            'phone'        => 'required|string|max:20',
            'country'      => 'required|string|in:USA,Canada,UK,Australia',
            'gender'       => 'required|string|in:male,female,other',
            'password'     => 'required|string|min:6|confirmed',
            'selfie'       => 'nullable|image|max:2048',
            'introduction' => 'nullable|string',
        ];
    }
}
