@extends('generate.pdf.layouts.main', ['title' => $filename])
@section('container')
    @include('generate.pdf.v2.style')
    <div class="container" style="border: 0">
        <h3 style="text-align: center">{{ $filename }}</h3>
        <table class="table bordered">
            <tbody>
                <tr>
                    <td><strong>Armada</strong></td>
                    <td>{{ $armada }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal</strong></td>
                    <td>{{ $jangkaTanggal }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Cetak</strong></td>
                    <td>{{ format_date(date('Y-m-d H:i')) }}</td>
                </tr>
                <tr>
                    <td><strong>Total Setor</strong></td>
                    <td>{{ rupiah($totalSetor) }}</td>
                </tr>
                <tr>
                    <td><strong>Total Servis</strong></td>
                    <td>{{ rupiah($totalServis) }}</td>
                </tr>
                <tr>
                    <td><strong>Total Akhir</strong></td>
                    <td>{{ rupiah($totalAkhir) }}</td>
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
                    <th style="text-align: center">Nopol</th>
                    <th style="text-align: center">Jenis Transaksi</th>
                    <th style="text-align: center">Nota / No Transaksi</th>
                    <th style="text-align: center">keterangan</th>

                    <th style="text-align: center">Harga</th>
                    <th style="text-align: center">Jumlah Satuan</th>
                    <th style="text-align: center">Sub Total</th>
                    {{-- <th style="text-align: center">Total</th> --}}
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
                    @php $isNewRow = true; @endphp
                    @foreach ($perTanggal as $detail)
                        @php
                            if ($detail['jenis_transaksi'] == 'Pemasukan') {
                                $total += $detail['sub_total'];
                            } else {
                                $total -= $detail['sub_total'];
                            }
                        @endphp
                        <tr
                            @if (!$isNewRow) style="border-bottom: 0;border-top:0" @else style="border-bottom: 0" @endif>
                            @if ($isNewRow)
                                <td style="border-bottom: 0">{{ format_date($detail['tanggal']) }}</td>
                                <td style="border-bottom: 0">{{ $detail['nopol'] }}</td>
                                <td style="border-bottom: 0">{{ $detail['jenis_transaksi'] }}</td>
                                <td style="border-bottom: 0">{{ $detail['nota'] }}</td>
                            @else
                                <td style="border-bottom: 0; border-top: 0"></td>
                                <td style="border-bottom: 0; border-top: 0"></td>
                                <td style="border-bottom: 0; border-top: 0"></td>
                                <td style="border-bottom: 0; border-top: 0"></td>
                            @endif
                            <td>{{ $detail['keterangan'] }}</td>
                            <td>{{ rupiah($detail['harga']) }}</td>
                            <td>{{ $detail['jumlah_satuan'] }}</td>
                            <td>
                                @if (count($perTanggal) > 1)
                                    {{ rupiah($detail['sub_total']) }}
                                @else
                                    <strong>{{ rupiah($detail['sub_total']) }}</strong>
                                @endif
                            </td>
                            {{-- <td>
                                @if (count($perTanggal) > 1 == false)
                                    <strong>{{ rupiah($total) }}</strong>
                                @endif
                            </td> --}}
                        </tr>
                        @php $isNewRow = false; @endphp
                    @endforeach
                    {{-- @if (count($perTanggal) > 1)
                        <tr>
                            <td colspan="4" style="text-align: right"><strong>Total</strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <strong>
                                    {{ rupiah(array_sum(array_column($perTanggal, 'sub_total'))) }}
                                </strong>
                            </td>
                            <td>
                                <strong>
                                    {{ rupiah($total) }}
                                </strong>
                            </td>
                        </tr>
                    @endif --}}
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- @endforeach --}}
@endsection
