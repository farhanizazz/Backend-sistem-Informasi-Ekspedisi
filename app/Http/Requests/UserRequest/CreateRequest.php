<?php

namespace App\Http\Requests\UserRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public $validator = null;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|unique:users|regex:/^[a-zA-Z0-9]+$/',
            'name'     => 'required',
            'email'    => 'required|unique:users|email',
            'password' => 'required|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ];
    }

    public function messages(){
        return [
            'username.unique'   => 'Username sudah terdaftar',
            'username.required' => 'Username harus diisi',
            'username.regex'    => 'Username hanya boleh mengandung huruf dan angka',
            'email.unique'      => 'Email sudah terdaftar',
            'email.email'       => 'Email tidak valid',
            'email.required'    => 'Email harus diisi',
            'password.required' => 'Password harus diisi',
            'password.min'      => 'Password minimal 6 karakter',
            'password.regex'    => 'Password harus mengandung huruf besar, huruf kecil, dan angka',
        ];
    }
    
    
    public function failedValidation(Validator $validator){
        $this->validator = $validator;
    }
}
