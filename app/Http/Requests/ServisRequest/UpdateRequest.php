<?php

namespace App\Http\Requests\ServisRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
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
            'nama_toko' => 'required|string',
            'nota_beli_id' => 'nullable|exists:nota_beli,id',
            'nopol' => 'required|exists:master_armada,nopol',

            
        ];
    }
    public function messages()
    {
        return [
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
    }   
}
