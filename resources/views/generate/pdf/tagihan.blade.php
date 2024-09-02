<?php
$total = 0; 
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
        $temp = penyebut($nilai - 10). " belas";
    } else if ($nilai < 100) {
        $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
    }     
    return $temp;
}

function terbilang($nilai) {
    if($nilai<0) {
        $hasil = "minus ". trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }     		
    return $hasil;
}


?>

@extends('generate.pdf.layouts.main')
@section('container')
<div class="" style="margin-top: 1rem;margin-left: 3rem;margin-right: 3rem;">
    <table>
        <tr>
            <td>No. Invoice</td>
            <td>:</td>
            <td>{{$data->no_tagihan}}</td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>:</td>
            <td> Penagihan Biaya Jasa Ekspedisi Angkutan Barang</td>
        </tr>
        <tr>
            <td rowspan="2" style="vertical-align: top;">Kepada Yth</td>
            <td rowspan="2" style="vertical-align: top;">:</td>
            <td style="vertical-align: bottom;"> {{$data->m_penyewa->nama_perusahaan}}</td>
        </tr>
        <tr>
            <td style="height:2rem;vertical-align: top;">{{$data->m_penyewa->alamat}}</td>
        </tr>
        <tr>
            <td colspan="3">Dengan Hormat</td>
        </tr>
        <tr>
            <td colspan="3">Berikut adalah data-data invoice yang belum terbayarkan</td>
        </tr>
    </table>
</div>
<div class="" style="margin-top: 1rem;margin-left:3rem;margin-right:3rem;">
    <table border="1" style="border: solid 1px black;border-collapse: collapse;">
        <thead>
            <tr>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">TGL MUAT</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">NOPOL</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">MUAT</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">ASAL/TUJUAN</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">LAMPIRAN</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">BIAYA</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">PPH/PPN</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">SISA TAGIHAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data["transaksi_tagihan_det"] as $transaksi_tagihan_det)
                <tr style="font-size:.8rem">
                    <td>{{date('m/d/Y', strtotime($transaksi_tagihan_det->transaksi_order->tanggal_awal))}}</td>
                    <td>{{$transaksi_tagihan_det->transaksi_order->armada->nopol}}</td>
                    <td style="text-align: center;">{{$transaksi_tagihan_det->transaksi_order->muatan}}</td>
                    <td style="text-align: center;">{{$transaksi_tagihan_det->transaksi_order->asal}}/{{$transaksi_tagihan_det->transaksi_order->tujuan}}</td>
                    <td style="text-align: center;">{{($transaksi_tagihan_det->transaksi_order->nomor_sj ?? '-')}},{{($transaksi_tagihan_det->transaksi_order->nomor_po ?? '-')}},{{($transaksi_tagihan_det->transaksi_order->nomor_do ?? '-')}}</td>
                    <td>{{rupiah($transaksi_tagihan_det->transaksi_order->harga_order)}}</td>
                    <td>{{rupiah($transaksi_tagihan_det->transaksi_order->ppn)}}</td>
                    <td>{{rupiah($transaksi_tagihan_det->transaksi_order->sisa_tagihan)}}</td>
                </tr>
               @php $total += $transaksi_tagihan_det->transaksi_order->sisa_tagihan; @endphp
            @endforeach
                <tr style="font-size:.8rem">
                    <td colspan="6" style="border-left: solid 1px white;border-bottom: solid 1px white;"><span style="font-style: italic;font-weight: bold;font-size: .8rem;">Terbilang : {{terbilang($total)}}</span></td>
                    <td style="text-align: left;">TOTAL TAGIHAN</td>
                    <td>{{rupiah($total)}}</td>
                </tr>
        </tbody>
    </table>
</div>
<div class="" style="margin-top: 2rem;margin-left:4rem;margin-right:4rem;">
    <table style="width:100%">
        <tr style="font-size: .8rem;">
            <td style="vertical-align: top;">
                <table>
                    <tr>
                        <td colspan=2>Pembayaran harap ditransfer ke rekening :</td>
                    </tr>
                    <tr>
                        <td style="width: 3rem;">Nama</td>
                        <td style="width: 18rem;">: {{$data->master_rekening->atas_nama}}</td>
                    </tr>
                    <tr>
                        <td>Bank</td>
                        <td>: {{$data->master_rekening->nama_bank}}</td>
                    </tr>
                    <tr>
                        <td>No Rek</td>
                        <td>: {{$data->master_rekening->nomor_rekening}}</td>
                    </tr>
                </table>
            </td>
            <td>
                <table style="width:100%">
                    <tr>
                        <td colspan="2" style="font-size: 1rem;font-weight: bold;text-align: center;vertical-align: top;">Malang, {{$tanggal}}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="height: 7rem;"></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-weight: bold;font-size: 1rem;text-align: center;vertical-align: bottom;">Purwianto</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
@endsection