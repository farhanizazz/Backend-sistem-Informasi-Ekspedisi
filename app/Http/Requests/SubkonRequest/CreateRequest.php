<?php

namespace App\Http\Requests\SubkonRequest;

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
            "nama_perusahaan" =>"required",
            "alamat" => "required",
            "ket" => "required",
            "penanggung jawab" => "required"

        ];
    }
    public function failedValidation(Validator $validator){
        $this->validator = $validator;
    }
    public function messages(){
        return [
            "nama_perusahaan.required" => "Nama Perusahaan tidak boleh kosong",
            "alamat.required" => "Alamat tidak boleh kosong",
            "ket.required" => "Keterangan tidak boleh kosong",
            "penanggung jawab.required" => "Penanggung Jawab tidak boleh kosong"
            
        ];
    }

}
