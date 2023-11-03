<?php

namespace App\Http\Resources\Order;

use App\Models\Master\RekeningModel;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'tanggal_awal' => $this->tanggal_awal,
            'tanggal_akhir' => $this->tanggal_akhir,
            'status_kendaraan' => $this->status_kendaraan,
            'status_kendaraan_sendiri' => $this->status_kendaraan_sendiri,
            'status_surat_jalan' => $this->status_surat_jalan,
            'm_penyewa_id' => $this->m_penyewa_id,
            'muatan' => $this->muatan,
            'm_armada_id' => $this->m_armada_id,
            'm_sopir_id' => $this->m_sopir_id,
            'asal' => $this->asal,
            'tujuan' => $this->tujuan,
            'harga_order' => $this->harga_order,
            'status_harga_order' => $this->status_harga_order,
            'biaya_lain_harga_order' => $this->biaya_lain_harga_order,
            'status_pajak' => $this->status_pajak,
            'setor' => $this->setor,
            'uang_jalan' => $this->uang_jalan,
            'potongan_wajib' => $this->potongan_wajib,
            'biaya_lain_uang_jalan' => $this->biaya_lain_uang_jalan,
            'm_subkon_id' => $this->m_subkon_id,
            'harga_jual' => $this->harga_jual,
            'status_harga_jual' => $this->status_harga_jual,
            'biaya_lain_harga_jual' => $this->biaya_lain_harga_jual,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'penyewa' => $this->penyewa,
            'armada' => $this->armada,
            'sopir' => $this->sopir,
            'subkon' => $this->subkon,
            'sisa_tagihan' => $this->sisa_tagihan,
            'sisa_hutang_ke_subkon' => $this->sisa_hutang_ke_subkon,
            'total_pajak' => $this->total_pajak,
            'biaya_lain_harga_order_arr' => $this->biaya_lain_harga_order_arr,
            'biaya_lain_uang_jalan_arr' => $this->biaya_lain_uang_jalan_arr,
            'biaya_lain_harga_jual_arr' => $this->biaya_lain_harga_jual_arr,
        ];
    }
}
