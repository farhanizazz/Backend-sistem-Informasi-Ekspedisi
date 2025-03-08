<?php
$total = 0; 

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
    <table border="1" style="border: solid 1px black;border-collapse: collapse;">
        <thead style="margin-top: 1rem">
            <tr  style="font-size:.9rem">
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">No</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Tgl</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">No Transaksi</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Status</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Nopol / Sopir</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Penyewa</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Muatan</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Asal - Tujuan</th>
                <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Setor</th>
            </tr>
        </thead>
        <tbody>
          @foreach($data as $dt)
              <tr style="font-size:.7rem">
                  <td style="text-align : center;">{{$loop->iteration}}</td>
                  <td>{{date('d/m/Y', strtotime($dt['tanggal']))}}</td>
                  <td style="text-align: center;">{{$dt['no_transaksi']}}</td>
                  <td style="text-align: center;">{{ucwords($dt['status'])}}</td>
                  <td style="text-align: center;">{{($dt['armada']['nopol'] ?? $dt['nopol_subkon'])}} / {{($dt['sopir']['nama'] ?? $dt['sopir_subkon'])}}</td>
                  <td>{{($dt['penyewa']['nama_perusahaan'] ?? $dt['subkon']['nama_perusahaan']) ?? '' }}</td>
                  <td>{{$dt['muatan']}}</td>
                  <td>{{$dt['asal']}} - {{$dt['tujuan']}}</td>
                  <td>{{rupiah($dt['setor'])}}</td>
              </tr>
          @endforeach
      </tbody>
    </table>
</div>
@endsection