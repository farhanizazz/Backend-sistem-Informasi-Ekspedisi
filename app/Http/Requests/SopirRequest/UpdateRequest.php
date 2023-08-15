<?php

namespace App\Http\Requests\SopirRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            "nama" => "required",
            "alamat" => "required",
            "ktp" => "required",
            "sim" => "required",
            "nomor_hp" => "required",
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
            "ktp.required" => "ktp harus diisi",
            "sim.required" => "SIM harus diisi",
            "nomor_hp.required" => "Nomer HP harus diisi",
            "tanggal_gabung.required" => "Tanggal Gabung harus diisi",
        ];
    }
}
