<?php

namespace App\Http\Requests\ArmadaRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

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
            'nopol' => 'required',
            'merk' => 'required',
            'jenis' => 'required',
            'tgl_stnk' => 'required',
            'tgl_uji_kir' => 'required',
            'tgl_stnk' => 'required|date|after_or_equal:now',
            'tgl_uji_kir' => 'required|date|after_or_equal:now',
        ];
    }

    public function messages(){
        return [
            'nopol.required' => 'Nomor Polisi harus diisi',
            'merk.required' => 'Merk harus diisi',
            'jenis.required' => 'Jenis harus diisi',
            'tgl_stnk.required' => 'Tanggal STNK harus diisi',
            'tgl_uji_kir.required' => 'Tanggal Uji Kir harus diisi',
            'tgl_stnk.after_or_equal' => 'Tanggal STNK tidak boleh kurang dari hari ini',
            'tgl_uji_kir.after_or_equal' => 'Tanggal Uji Kir tidak boleh kurang dari hari ini',
            'tgl_uji_kir.date' => 'Tanggal Uji Kir harus berupa tanggal',
            'tgl_stnk.date' => 'Tanggal STNK harus berupa tanggal',
        ];
    }
    
    
    public function failedValidation(Validator $validator){
        $this->validator = $validator;
    }
}
