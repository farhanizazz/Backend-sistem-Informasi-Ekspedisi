<?php

namespace App\Http\Resources\Laporan;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PemasukanCVCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // check is paginated or not
        if ($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            return [
                'list' => $this->collection,
                'meta' => [
                    'links' => $this->getUrlRange(1, $this->lastPage()),
                    'total' => $this->total()
                ]
            ];
        }else{
            return parent::toArray($request);
        }
    }
}
