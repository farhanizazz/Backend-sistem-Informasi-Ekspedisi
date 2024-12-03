<?php

namespace App\Http\Resources\LaporanV2;

use Illuminate\Http\Resources\Json\ResourceCollection;

class HutangPiutangSopirCollection extends ResourceCollection
{
    protected $items = [];
    public function __construct($collection, $items)
    {
        parent::__construct($collection);
        $this->items = $items;
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
            'list' => HutangPiutangSopirResource::collection($this->collection),
            'meta' => [
                'links' => $this->getUrlRange(1, $this->lastPage()),
                'total' => $this->total(),
            ],
            'tanggal' => [
                'start' => $request->get('tanggalAwal'),
                'end' => $request->get('tanggalAkhir'),
            ],
            'sopir' => $this->items
        ];
    }
}
