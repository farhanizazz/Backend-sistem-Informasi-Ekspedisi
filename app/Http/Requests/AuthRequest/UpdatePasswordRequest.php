<?php

namespace App\Http\Requests\AuthRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends FormRequest
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
            // cek password sama dengan password lama
            'password_lama' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, auth()->user()->password)) {
                    $fail('Password lama tidak sesuai');
                }
            }],
            'password_baru' => 'required',
        ];
    }

    public function messages(){
        return [
            'password_lama.required' => 'Password lama harus diisi',
            'password_baru.required' => 'Password baru harus diisi',
        ];
    }
    
    
    public function failedValidation(Validator $validator){
        $this->validator = $validator;
    }
}
