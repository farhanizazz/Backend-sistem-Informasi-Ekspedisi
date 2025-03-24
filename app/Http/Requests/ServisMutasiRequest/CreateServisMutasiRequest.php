<?php

namespace App\Http\Requests\ServisMutasiRequest;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CreateServisMutasiRequest extends FormRequest
{
    public $validator = null;
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
        'servis_id' => 'required|exists:servis,id',
        'master_rekening_id' => 'required|exists:master_rekening,id',
        'nominal' => 'required|integer',
        'tanggal_pembayaran' => 'required|date'
    ];
}

    public function messages()
    {
        // Replace with your own messages
        return [
            // 'field.rule' => 'message'
            'servis_id.required' => 'Servis is required',
            'master_rekening_id.required' => 'Rekening is required',
            'nominal.required' => 'Nominal is required',
            'nominal.integer' => 'Nominal must be an integer',
            'tanggal_pembayaran.required' => 'Tanggal Pembayaran is required',
            'tanggal_pembayaran.date' => 'Tanggal Pembayaran must be a date'
        ];
    }


    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
}
