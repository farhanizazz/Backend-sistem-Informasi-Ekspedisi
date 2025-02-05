@extends('generate.pdf.layouts.main', ['title' => $filename])
@section('container')
    @include('generate.pdf.v2.style')
    <div class="container" style="border: 0">
        <h3 style="text-align: center">{{ $filename }}</h3>
        <table class="table bordered">
            <tbody>
                <tr>
                    <td><strong>Nama Sopir</strong></td>
                    <td>{{ $sopir->nama }}</td>
                </tr>
                <tr>
                    <td><strong>No HP</strong></td>
                    <td>{{ $sopir->nomor_hp }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal</strong></td>
                    <td>{{ $jangkaTanggal }}</td>
                </tr>
                <tr>
                    <td><strong>Total THR</strong></td>
                    <td>{{ rupiah($total) }}</td>
                </tr>
            </tbody>
        </table>
    </div>


    {{-- @foreach ($orders as $order) --}}
        <div class="container" style="border-bottom: 0">
            <table class="table bordered table-text-left">
                <thead>
                    <tr>
                        <th style="text-align: center">Tanggal</th>
                        <th style="text-align: center">No Transaksi</th>
                        <th style="text-align: center">Status</th>
                        <th style="text-align: center">Nopol / Sopir</th>
                        <th style="text-align: center">Penyewa</th>
                        <th style="text-align: center">Muatan</th>
                        <th style="text-align: center">Asal / Tujuan</th>
                        <th style="text-align: center">Pot THR</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($data) == 0)
                        <tr>
                            <td style="text-align: center" colspan="8">Rincian tidak tersedia</td>
                        </tr>
                    @endif
                    @foreach ($data as $index => $detail)
                        <tr>
                            <td>{{ format_date($detail->tanggal_awal) }}</td>
                            <td>{{ $detail->no_transaksi }}</td>
                            <td>{{ $detail->status_kendaraan_sendiri }}</td>
                            <td>{{ ($detail->armada?->nopol ?? $detail->nopol_subkon) . " / ".  ($detail->sopir->nopol ?? $detail->sopir_subkon) }}</td>
                            <td>{{ $detail->penyewa?->nama_perusahaan ?? '-' }}</td>
                            <td>{{ $detail->muatan }}</td>
                            <td>{{ $detail->asal . " / " . $detail->tujuan }}</td>
                            <td>{{ rupiah($detail->potongan_wajib) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    {{-- @endforeach --}}
@endsection
