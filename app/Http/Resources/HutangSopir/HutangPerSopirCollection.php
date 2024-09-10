<?php

namespace App\Http\Resources\HutangSopir;

use Illuminate\Http\Resources\Json\ResourceCollection;

class HutangPerSopirCollection extends ResourceCollection
{
    private $sopir;
    public function __construct($resource, $sopir)
    {
        parent::__construct($resource);
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
        return [
            'list' => $this->collection,
            'sopir'=> $this->sopir,
            'meta' => [
                'links' => $this->getUrlRange(1, $this->lastPage()),
                'total' => $this->total()
            ]
        ];
    }
}
