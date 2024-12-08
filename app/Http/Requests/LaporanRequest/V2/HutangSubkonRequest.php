<?php

namespace App\Http\Requests\LaporanRequest\V2;

use Illuminate\Foundation\Http\FormRequest;

class HutangSubkonRequest extends FormRequest
{
    public function rules()
    {
        return [
            'subkonId' => 'required',
            'tanggalAwal' => 'required',
            'tanggalAkhir' => 'required',
        ];
    }
}
