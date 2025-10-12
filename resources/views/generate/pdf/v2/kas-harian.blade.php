@extends('generate.pdf.layouts.main', ['title' => $filename])
@php
    $totalPositive = 0;
    $totalNegative = 0;
@endphp
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
                        <td style="text-align: center" colspan="4">Rincian tidak tersedia</td>
                    </tr>
                @endif
                @foreach ($data as $index => $detail)
                    @php
                        $isNegative = in_array($detail['jenis_transaksi']->value, ['uang_jalan', 'pengeluaran']);
                        $value = $detail['nominal'];
                        if ($isNegative) {
                            $totalNegative += $value;
                        } else {
                            $totalPositive += $value;
                        }
                    @endphp
                    <tr>
                        <td>{{ format_date($detail['tanggal_pembayaran']) }}</td>
                        <td>{{ $detail['transaksi_order'] ? $detail['transaksi_order']['no_transaksi'] : '-' }}</td>
                        <td>{{ $detail['jenis_transaksi']->value }}</td>
                        @if ($isNegative)
                            <td style="color:red">{{ rupiah($value * -1) }}</td>
                        @else
                            <td>{{ rupiah($value) }}</td>
                        @endif
                    </tr>
                @endforeach
                @php
                    $totalAll = $totalPositive - $totalNegative;
                @endphp
                <tr>
                    <td colspan="2"><strong>Totals</strong></td>
                    <td><strong>Positive:</strong></td>
                    <td>{{ rupiah($totalPositive) }}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td><strong>Negative:</strong></td>
                    <td style="color:red">{{ rupiah($totalNegative * -1) }}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td><strong>Net Total:</strong></td>
                    <td>{{ rupiah($totalAll) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    {{-- @endforeach --}}
@endsection
