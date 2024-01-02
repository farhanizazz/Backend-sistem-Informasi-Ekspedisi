<?php

namespace App\Http\Requests\RekeningRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

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
            'nomor_rekening' => 'required',
            'atas_nama' => 'required',
            'nama_bank' => 'required',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
    public function message()
    {
        return [

            'nomor_rekening.required' => 'Nomor Rekening tidak boleh kosong',
            'atas_nama.required' => 'Atas Nama tidak boleh kosong',
            'nama_bank.required' => 'Nama Bank tidak boleh kosong',
        ];
    }
}
