<?php

namespace App\Http\Requests\TambahanRequest;

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
            //
            'nama' => ['required'],
            // 'nominal' => ['required'],
            'sifat' => 'required|in:Menambahkan,Mengurangi',

        ];
    }
    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
    public function messages()
    {
        return [
            'nama.required' => 'Nama Tambahan tidak boleh kosong',
            // 'nominal.required' => 'Nominal tidak boleh kosong',
            'sifat.required' => 'Sifat tidak boleh kosong',
        ];
    }
}
