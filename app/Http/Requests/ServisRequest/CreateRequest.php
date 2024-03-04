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
        // Replace with your own rules
        return [
            // 'field' => 'rule'
            'nama_toko' => 'required|string',
            'nota_beli_id' => 'nullable|exists:nota_beli,id',
            'nopol' => 'required|exists:master_armada,nopol',
            

        ];
    }

    public function messages()
    {
        // Replace with your own messages
        return [
            // 'field.rule' => 'message'
            'nama_toko.required' => 'Nama toko wajib diisi',
            'nama_toko.string' => 'Nama toko harus berupa string',
            'nota_beli_id.exists' => 'Nota beli tidak ditemukan',
            'nopol.required' => 'Nopol wajib diisi',

        ];
    }


    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function validated()
    {
        $validated = parent::validated();

        // Assuming you have a NotaBeliModel and you want to get its data
        $notaBeliModel = NotaBeliModel::find($validated['notaBeli_id']);
        $notaBeli = $notaBeliModel->toArray();

        return array_merge($validated, ['notaBeli' => $notaBeli]);
    }
}
