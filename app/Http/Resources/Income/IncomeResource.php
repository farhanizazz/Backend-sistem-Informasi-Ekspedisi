<?php

namespace App\Http\Resources\Income;

use Illuminate\Http\Resources\Json\JsonResource;

class IncomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'no_transaksi' => $this->no_transaksi,
            'tanggal_awal' => $this->tanggal_awal,
            'tanggal_akhir' => $this->tanggal_akhir,
            'status_surat_jalan' => $this->status_surat_jalan,
            'm_sopir_id' => $this->m_sopir_id,
            'm_penyewa_id' => $this->m_penyewa_id,
            'muatan' => $this->muatan,
            'asal' => $this->asal,
            'tujuan' => $this->tujuan,
            'uang_jalan' => $this->uang_jalan,
            'biaya_lain_uang_jalan' => $this->biaya_lain_uang_jalan ?? [],
            'harga_order' => $this->harga_order,
            'biaya_lain_harga_order' => $this->biaya_lain_harga_order ?? [],
            'total_pajak' => $this->total_pajak,
            'setor' => $this->setor,
            'harga_order_bersih' => $this->harga_order_bersih,
            'uang_jalan_bersih' => $this->uang_jalan_bersih,
            'income' => $this->calculateIncome()
        ];
    }

    public function calculateIncome()
    {
        // foreach ($this->biaya_lain_harga_order as $biaya) {
        //     $this->harga_order += $biaya['harga'];
        // };
        return $this->harga_order_bersih - $this->setor  -$this->uang_jalan_bersih;
    }
}
