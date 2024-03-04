<?php

namespace App\Helpers\Transaksi;

use Illuminate\Http\Request;
use App\Models\Transaksi\ServisModel;
use App\Models\Transaksi\NotaBeliModel;

class ServisHelper
{
    public function create(Request $payload)
{
    try {
        $notaBeli = new NotaBeliModel($payload->all());
        $notaBeli->save();

        $servis = new ServisModel($payload->all());
        $servis->save();

        return [
            "status" => true,
            "message" => "Berhasil membuat NotaBeli dan Servis",
            "data" => [
                "notaBeli" => $notaBeli,
                "servis" => $servis
            ]
        ];
    } catch (\Throwable $th) {
        return [
            "status" => false,
            "message" => "Gagal membuat NotaBeli dan Servis",
            "dev" => $th->getMessage()
        ];
    }
}
}