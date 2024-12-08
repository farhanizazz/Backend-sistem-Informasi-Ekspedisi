<?php

namespace App\Http\Requests\LaporanRequest\V2;

use Illuminate\Foundation\Http\FormRequest;

class HutangCustomerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'tanggalAwal' => 'required|date',
            'tanggalAkhir' => 'required|date',
            'subkon' => 'required',
            'status' => 'required',
            'penyewaId' => 'required',
        ];
    }
}
