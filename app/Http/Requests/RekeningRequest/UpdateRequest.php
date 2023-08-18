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
            'biaya_kuli' => 'required|integer',
            'biaya akomodasi' => 'required|integer',
            'claim' => 'required|integer',
            'brg_rusak' => 'required|integer',
            'biaya_tol' => 'required|integer',
        ];
    }
    public function failedValidation(Validator $validator){
        $this->validator = $validator;
    }
    public function message()
    {
        return [
            'biaya_kuli.required' => 'Biaya Kuli harus diisi',
            'biaya akomodasi.required' => 'Biaya Inap harus diisi',
            'claim.required' => 'Claim harus diisi',
            'brg_rusak.required' => 'Barang Rusak harus diisi',
            'biaya_tol.required' => 'Biaya Tol harus diisi',
        ];

    }
}
