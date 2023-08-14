<?php

namespace App\Http\Requests\SopirRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
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
            "nama" => "required",
            "alamat" => "required",
            "KTP" => "required",
            "SIM" => "required",
            "phone_number" => "required",
            "tanggal_gabung" => "required",

        ];
    }
    public function failedValidation(Validator $validator){
        $this->validator = $validator;
    }
    public function messages(){
        return [
            "nama.required" => "Nama harus diisi",
            "alamat.required" => "Alamat harus diisi",
            "KTP.required" => "KTP harus diisi",
            "SIM.required" => "SIM harus diisi",
            "phone_number.required" => "Nomer HP harus diisi",
            "tanggal_gabung.required" => "Tanggal Gabung harus diisi",
        ];
    }
}
