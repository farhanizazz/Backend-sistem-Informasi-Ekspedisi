<?php

namespace App\Http\Requests\PenyewaRequest;

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
            "nama_perusahaan" => "required",
            "alamat" => "required",
            "penanggung_jawab" => "required",
            "kontak" => "required",
        ];
    }

    public function messages()
    {
        return [
            "nama_perusahaan.required" => "Nama Perusahaan harus diisi",
            "alamat.required" => "Alamat harus diisi",
            "penanggung_jawab.required" => "Penanggung Jawab harus diisi",
            "kontak.required" => "Kontak harus diisi",
        ];
    }

    public function failedValidation(Validator $validator){
        $this->validator = $validator;
    }
}
