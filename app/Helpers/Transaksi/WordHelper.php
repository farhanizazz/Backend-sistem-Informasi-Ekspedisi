<?php

namespace App\Helpers\Transaksi;

use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

class WordHelper{
    private $total = 0; 
    public function generateWord(array $payload){
        $data = $payload['data'];
        Carbon::setLocale('id');
        $tanggal = Carbon::parse($data['created_at'])->translatedFormat('j F Y');
        $templateProcessor = new TemplateProcessor('assets/documents/template-invoice.docx');

        $templateProcessor->setValue('no_invoice', $data->no_tagihan);
        $templateProcessor->setValue('penyewa', $data->m_penyewa->nama_perusahaan);
        $templateProcessor->setValue('alamat', $data->m_penyewa->alamat);

        $values = $data['transaksi_tagihan_det']->map(function($transaksi_tagihan_det){
            $this->total += $transaksi_tagihan_det->transaksi_order->sisa_tagihan;
            return [
                'tgl_muat' => date('m/d/Y', strtotime($transaksi_tagihan_det->transaksi_order->tanggal_awal)),
                'nopol' => $transaksi_tagihan_det->transaksi_order->armada->nopol,
                'muat' => $transaksi_tagihan_det->transaksi_order->muatan,
                'asal_tujuan' => $transaksi_tagihan_det->transaksi_order->asal . "/" .$transaksi_tagihan_det->transaksi_order->tujuan,
                'lampiran' => ($transaksi_tagihan_det->transaksi_order->nomor_sj ?? '-') .",". ($transaksi_tagihan_det->transaksi_order->nomor_po ?? '-') .",". ($transaksi_tagihan_det->transaksi_order->nomor_do ?? '-'),
                'biaya' => $this->rupiah($transaksi_tagihan_det->transaksi_order->harga_order_bersih),
                'ppn' => $this->rupiah($transaksi_tagihan_det->transaksi_order->total_pajak),
                'sisa_tagihan' => $this->rupiah($transaksi_tagihan_det->transaksi_order->sisa_tagihan)
            ];
        });

        $templateProcessor->setValue('total_sisa_tagihan', $this->rupiah($this->total));
        $templateProcessor->setValue('terbilang', $this->terbilang($this->total));
        $templateProcessor->setValue('nama_rekening', $data->master_rekening->atas_nama);
        $templateProcessor->setValue('nama_bank', $data->master_rekening->nama_bank);
        $templateProcessor->setValue('no_rekening', $data->master_rekening->nomor_rekening);
        $templateProcessor->setValue('tanggal', $tanggal);


        // simpan dokumen 
        $fileName = 'Invoice Tagihan' . ' ' . $data->m_penyewa->nama_perusahaan. '.docx';

        // ubah tanda slash ke bentuk lain agar tidak bentrok path
        $fileName = str_replace("/" , " " , $fileName);

        $templateProcessor->cloneRowAndSetValues('tgl_muat', $values);
        $templateProcessor->saveAs(storage_path($fileName));

        return storage_path($fileName);
    }
    

    function rupiah($angka){
	
        $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
        return $hasil_rupiah;
    }
    
    function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = $this->penyebut($nilai - 10). " belas";
        } else if ($nilai < 100) {
            $temp = $this->penyebut($nilai/10)." puluh". $this->penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . $this->penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->penyebut($nilai/100) . " ratus" . $this->penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . $this->penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = $this->penyebut($nilai/1000) . " ribu" . $this->penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = $this->penyebut($nilai/1000000) . " juta" . $this->penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = $this->penyebut($nilai/1000000000) . " milyar" . $this->penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = $this->penyebut($nilai/1000000000000) . " trilyun" . $this->penyebut(fmod($nilai,1000000000000));
        }     
        return $temp;
    }
    
    function terbilang($nilai) {
        if($nilai<0) {
            $hasil = "minus ". trim($this->penyebut($nilai));
        } else {
            $hasil = trim($this->penyebut($nilai));
        }     		
        return $hasil;
    }
    
}