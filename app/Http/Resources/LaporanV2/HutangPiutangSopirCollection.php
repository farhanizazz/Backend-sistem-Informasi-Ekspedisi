<?php

namespace App\Http\Resources\LaporanV2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class HutangPiutangSopirCollection extends ResourceCollection
{
    protected $totalSisaUangJalan = 0;
    protected $totalHutang = 0;
    protected $sopir = [];
    public function __construct($collection, $totalSisaUangJalan, $totalHutang, $sopir)
    {
        parent::__construct($collection);
        $this->totalSisaUangJalan = $totalSisaUangJalan;
        $this->totalHutang = $totalHutang;
        $this->sopir = $sopir;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $namaSopir = "Semua Sopir";
        if ($this->sopir) {
            $namaSopir = [];
            foreach ($this->sopir as $sopir) {
                $namaSopir[] = $sopir->nama;
            }

            $namaSopir = implode(", ", $namaSopir);
        }

        return [
            'list' => HutangPiutangSopirResource::collection($this->collection),
            'meta' => [
                'links' => $this->getUrlRange(1, $this->lastPage()),
                'total' => $this->total(),
            ],
            'tanggal' => [
                'start' => $request->get('tanggalAwal'),
                'end' => $request->get('tanggalAkhir'),
            ],
            'nama_sopir' => $namaSopir,
            'total_hutang' => $this->totalHutang,
            'total_sisa_uang_jalan' => $this->totalSisaUangJalan,
        ];
    }
}
