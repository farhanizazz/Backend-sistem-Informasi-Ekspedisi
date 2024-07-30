<?php

namespace App\Http\Requests\TagihanRequest;

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
            "singkatan" => "required",
            "order_detail" => "required|array",
            "m_penyewa_id" => "required",
            "master_rekening_id" => "required"
        ];
    }
    
    public function failedValidation(Validator $validator){
        $this->validator = $validator;
    }

    public function messages(){
        return [
            "singkatan.required" => "Singkatan tidak boleh kosong",
            "order_detail.required" => "Order detail tidak boleh kosong dan harus array dari <id>transaksi_order  cth: [2,3,5]",
            "order_detail.array" => "Order detail harus array <id>transaksi_order cth: [2,3,5]",
            "m_penyewa_id.required" => "Master Penyewa tidak boleh kosong",
            "master_rekening_id.required" => "Master Rekening tidak boleh kosong"
        ];
    }
}
