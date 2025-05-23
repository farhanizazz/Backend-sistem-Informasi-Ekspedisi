<?php
$total = 0;

// order by tanggal
$data = collect($data)->sortBy('tanggal')->values()->all();

// group by tanggal dan nopol
$data = collect($data)
    ->groupBy(function ($item) {
        return $item['tanggal'] . '.' . $item['nopol'] . '.' . $item['nomor_nota'];
    })
    ->values()
    ->all();

function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
    $temp = '';
    if ($nilai < 12) {
        $temp = ' ' . $huruf[$nilai];
    } elseif ($nilai < 20) {
        $temp = penyebut($nilai - 10) . ' belas';
    } elseif ($nilai < 100) {
        $temp = penyebut($nilai / 10) . ' puluh' . penyebut($nilai % 10);
    } elseif ($nilai < 200) {
        $temp = ' seratus' . penyebut($nilai - 100);
    } elseif ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . ' ratus' . penyebut($nilai % 100);
    } elseif ($nilai < 2000) {
        $temp = ' seribu' . penyebut($nilai - 1000);
    } elseif ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . ' ribu' . penyebut($nilai % 1000);
    } elseif ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . ' juta' . penyebut($nilai % 1000000);
    } elseif ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . ' milyar' . penyebut(fmod($nilai, 1000000000));
    } elseif ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . ' trilyun' . penyebut(fmod($nilai, 1000000000000));
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
    @include('generate.pdf.v2.style')

    <h3 style="text-align: center;margin-bottom:0px">{{ $title }}</h3>
    <p style="margin-top: 0px;text-align:center;font-size:.8rem">
        ({{ $periode }})
    </p>
    <div class="" style="margin-top: 1rem;margin-left:1rem;margin-right:1rem;">
        <table border="1" style="border: solid 1px black;border-collapse: collapse;width:100%">
            <thead style="margin-top: 1rem">
                <tr style="font-size:.9rem">
                    {{-- <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">No</th> --}}
                    <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Tgl</th>
                    <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Nopol</th>
                    <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Nama Toko</th>
                    <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Nama / No. Nota</th>
                    <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Keterangan</th>
                    <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Harga</th>
                    <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Jumlah</th>
                    <th style="padding-left: .5rem;padding-right:.5rem;background-color: #97befc;">Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @if (count($data) == 0)
                    <tr>
                        <td style="text-align: center" colspan="3">Rincian tidak tersedia</td>
                    </tr>
                @endif
                @php
                    $total = 0;
                @endphp
                @foreach ($data as $index => $perTanggal)
                    @php
                        $isNewRow = true;
                    @endphp
                    @foreach ($perTanggal as $indexDetail => $detail)
                        @php
                            $total += $detail['subtotal'];
                            $isLastRecord = $indexDetail == count($perTanggal) - 1 && $index == count($data) - 1;
                        @endphp
                        <tr
                            @if (!$isNewRow) style="border-bottom: 0;border-top:0" @else style="border-bottom: 0" @endif>
                            @if ($isNewRow)
                                <td style="border-bottom: 0">{{ format_date($detail['tanggal']) }}</td>
                                <td style="border-bottom: 0">{{ $detail['nopol'] }}</td>
                                <td style="border-bottom: 0">{{ $detail['nama_toko'] }}</td>
                                <td style="border-bottom: 0">{{ $detail['nomor_nota'] }}</td>
                            @elseif($isLastRecord)
                                <td style="border-top: 0"></td>
                                <td style="border-top: 0"></td>
                                <td style="border-top: 0"></td>
                                <td style="border-top: 0"></td>
                            @else
                                <td style="border-bottom: 0; border-top: 0"></td>
                                <td style="border-bottom: 0; border-top: 0"></td>
                                <td style="border-bottom: 0; border-top: 0"></td>
                                <td style="border-bottom: 0; border-top: 0"></td>
                            @endif
                            <td style="text-align: left;">{{ ucwords($detail['nama_barang']) }} /
                                {{ $detail['nomor_nota'] }} </td>
                            <td>{{ rupiah($detail['harga']) }}</td>
                            <td>{{ $detail['jumlah'] }}</td>
                            <td>
                                {{-- @if (count($perTanggal) > 1) --}}
                                    {{ rupiah($detail['subtotal']) }}
                                {{-- @else
                                    <strong>{{ rupiah($detail['subtotal']) }}</strong>
                                @endif --}}
                            </td>
                            {{-- <td>
                                @if (count($perTanggal) > 1 == false)
                                    <strong>{{ rupiah($total) }}</strong>
                                @endif
                            </td> --}}
                        </tr>
                        @php $isNewRow = false; @endphp
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-size:.7rem; font-weight:bold">
                    <td colspan="7" style="border: none; text-align: right;">Total:</td>
                    <td style="border: 1px solid black;">{{ rupiah($total) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
