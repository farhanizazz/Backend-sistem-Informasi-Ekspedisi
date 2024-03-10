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
            'master_armada_id.required' => 'Master armada is required',
            'nama_barang.required' => 'Nama barang is required',
            'nama_barang.string' => 'Nama barang must be a string',
            'harga.required' => 'Harga is required',
            'jumlah.required' => 'Jumlah is required',
            'jumlah.integer' => 'Jumlah must be an integer',
            'nama_toko.required' => 'Nama toko is required',

        ];
    }


    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

   
}
