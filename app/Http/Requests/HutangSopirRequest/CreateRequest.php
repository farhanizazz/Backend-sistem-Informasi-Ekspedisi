<?php

namespace App\Http\Requests\HutangSopirRequest;

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
            'tgl_transaksi' => 'required',
            'master_sopir_id' => 'required',
            'nominal_trans' => 'required',
            'ket_trans' => 'nullable',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
    public function messages()
    {
        return [
            "tgl_transaksi.required" => "Tanggal Transaksi tidak boleh kosong",
            "master_sopir_id.required" => "Sopir tidak boleh kosong",
            "nominal_trasn" => "nominal transasksi tidak boleh kosong",

        ];
    }
}
