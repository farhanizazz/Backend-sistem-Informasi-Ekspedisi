<?php

namespace App\Http\Requests\ServisRequest;

use App\Models\Transaksi\NotaBeliModel;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Replace with your own logic
        return true;
    }

    public function rules()
{
    return [
        'master_armada_id' => 'required|exists:master_armada,id',
        'nama_barang' => 'required|string',
        'harga' => 'required|numeric',
        'jumlah' => 'required|integer',
        'nama_toko' => 'required|string',
    ];
}

    public function messages()
    {
        // Replace with your own messages
        return [
            // 'field.rule' => 'message'
            'nama_toko.required' => 'Nama toko wajib diisi',
            'nota_beli_id.exists' => 'Nota beli tidak ditemukan',
            'master_armada_id.required' => 'Armada wajib diisi',

        ];
    }


    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

   
}
