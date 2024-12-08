<?php

namespace App\Http\Requests\LaporanRequest\V2;

use Illuminate\Foundation\Http\FormRequest;

class HutangSopirRequest extends FormRequest
{
    public function rules()
    {

        return [
            'sopirId' => 'nullable',
            'tanggalAwal' => 'date|required',
            'tanggalAkhir' => 'date|required',
        ];
    }
}
