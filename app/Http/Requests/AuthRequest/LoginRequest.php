<?php

namespace App\Http\Requests\AuthRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required',
            'password' => 'required',
        ];
    }

    public function messages(){
        return [
            'email.required' => 'Email harus diisi',
            'password.required' => 'Password harus diisi',
        ];
    }
    
    
    public function failedValidation(Validator $validator){
        $this->validator = $validator;
    }
}
