<?php

namespace App\Http\Resources\LaporanV2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class HutangPiutangCustomerCollection extends ResourceCollection
{

    protected $totalHutangPiutang = 0;
    public function __construct($collection, $totalHutang)
    {
        parent::__construct($collection);
        $this->totalHutangPiutang = $totalHutang;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'list' => HutangPiutangCustomerResource::collection($this->collection),
            'meta' => [
                'links' => $this->getUrlRange(1, $this->lastPage()),
                'total' => $this->total(),
            ],
            'tanggal' => [
                'start' => $request->get('tanggalAwal'),
                'end' => $request->get('tanggalAkhir'),
            ],
            'total_hutang' => $this->totalHutangPiutang,
        ];
    }
}
