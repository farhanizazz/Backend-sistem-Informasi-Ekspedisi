@extends('generate.pdf.layouts.main', ['title' => $filename])
@section('container')
    @include('generate.pdf.v2.style')
    <div class="container" style="border: 0">
        <h3 style="text-align: center">{{ $filename }}</h3>
        <table class="table bordered">
            <tbody>
                <tr>
                    <td><strong>No Rekening</strong></td>
                    <td>{{ $rekening->nomor_rekening }}</td>
                </tr>
                <tr>
                    <td><strong>Atas Nama</strong></td>
                    <td>{{ $rekening->atas_nama }}</td>
                </tr>
                <tr>
                    <td><strong>Nama Bank</strong></td>
                    <td>{{ $rekening->nama_bank }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal</strong></td>
                    <td>{{ $jangkaTanggal }}</td>
                </tr>
            </tbody>
        </table>
    </div>


    {{-- @foreach ($orders as $order) --}}
        <div class="container" style="border-bottom: 0">
            <table class="table bordered table-text-left">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Ref Id</th>
                        <th>Jenis Transaksi</th>
                        <th>Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($data) == 0)
                        <tr>
                            <td style="text-align: center" colspan="3">Rincian tidak tersedia</td>
                            <td rowspan="{{ count($data) }}">{{ rupiah($order['sisa_hutang']) }}</td>
                        </tr>
                    @endif
                    @foreach ($data as $index => $detail)
                        <tr>
                            <td>{{ format_date($detail['tanggal_pembayaran']) }}</td>
                            <td>{{ $detail['transaksi_order'] ? $detail['transaksi_order']['no_transaksi'] : '-' }}</td>
                            <td>{{ $detail['jenis_transaksi']->value }}</td>
                            <td>{{ rupiah($detail['nominal']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    {{-- @endforeach --}}
@endsection
