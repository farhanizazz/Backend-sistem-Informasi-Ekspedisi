<?php

namespace App\Http\Requests\NotaBeliRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
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
            'nama_barang' => 'required',
            'jumlah' => 'required',
            'harga' => 'required',

        ];
    }
    public function messages(){
        return [
            'nama_barang.required' => 'Nama Barang harus diisi',
            'jumlah.required' => 'Jumlah harus diisi',
            'harga.required' => 'Harga harus diisi',
        ];
    }
    public function failedValidation(Validator $validator){
        $this->validator = $validator;
    }

}
