<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // hitung total
        $this->collection->map(function($item){
            $total = 0;
            if ($item->nota_beli_items) {
                $item->nota_beli_items->map(function($item) use(&$total){
                    $total_sub = $item->harga * $item->jumlah;
                    $total += $total_sub;
                    return $item;
                });
            }
            $item->total = $total;
        });

        // hitung total mutasi
        $this->collection->map(function($item){
            $total = 0;
            if ($item->servis_mutasi) {
                $item->servis_mutasi->map(function($item) use(&$total){
                    $total += ($item->master_mutasi->nominal ?? 0);
                    return $item;
                });
            }
            $item->total_mutasi = $total;
        });

        return [
            'list' => $this->collection,
            'meta' => [
                'links' => $this->getUrlRange(1, $this->lastPage()),
                'total' => $this->total()
            ]
        ];
    }
}
