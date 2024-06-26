<?php

namespace App\Http\Requests\MutasiRequest;

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
            'transaksi_order_id' => 'required',
            'master_rekening_id' => 'required',
            'tanggal_pembayaran' => 'required|date',
            'jenis_transaksi' => 'required|in:order,jual,uang_jalan', // 'order' or 'jual'
            'nominal' => 'required|numeric',
        ];
    }

    public function messages(){
        return [
            'transaksi_order_id.required' => 'Transaksi Order harus diisi',
            'master_rekening_id.required' => 'Rekening harus diisi',
            'tanggal_pembayaran.required' => 'Tanggal Pembayaran harus diisi',
            'nominal.required' => 'Nominal harus diisi',
            'tanggal_pembayaran.date' => 'Tanggal Pembayaran harus berupa tanggal',
            'nominal.numeric' => 'Nominal harus berupa angka',
            'jenis_transaksi.required' => 'Jenis Transaksi harus diisi',
            'jenis_transaksi.in' => 'Jenis Transaksi harus berupa order, uang_jalan atau jual'
        ];
    }
    
    
    public function failedValidation(Validator $validator){
        $this->validator = $validator;
    }
}
