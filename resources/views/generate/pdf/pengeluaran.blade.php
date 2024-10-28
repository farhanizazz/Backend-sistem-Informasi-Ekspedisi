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
?>

@extends('generate.pdf.layouts.main', ['title' => $filename])
@section('container')

<style>
      html {
            margin-top: 10;
        }
</style>

<h3 style="text-align: center;margin-bottom:0px">{{$title}}</h3>
<p style="margin-top: 0px;text-align:center;font-size:.8rem">
  ({{$periode}})
</p>
<div class="" style="margin-top: 1rem;margin-left:1rem;margin-right:1rem;">
    <table border="1" style="border: solid 1px black;border-collapse: collapse;width:100%">
        <thead style="margin-top: 1rem">
            <tr  style="font-size:.9rem">
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">No</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Tgl</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Nopol</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Nama / No. Nota</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Keterangan</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Harga</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Jumlah</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
          @foreach($data as $dt)
              <tr style="font-size:.7rem">
                  <td style="text-align : center;">{{$loop->iteration}}</td>
                  <td>{{date('d/m/Y', strtotime($dt['tanggal']))}}</td>
                  <td style="text-align: center;">{{$dt['nopol']}}</td>
                  <td style="text-align: left;">{{ucwords($dt['nama_barang'])}} / {{$dt['nomor_nota']}} </td>
                  <td style="text-align: center;">{{$dt['keterangan'] ?? '-'}}</td>
                  <td>{{rupiah($dt['harga'])}}</td>
                  <td style="text-align: center">{{$dt['jumlah']}}</td>
                  <td>{{rupiah($dt['subtotal'])}}</td>
              </tr>
          @endforeach
      </tbody>
    </table>
</div>
@endsection