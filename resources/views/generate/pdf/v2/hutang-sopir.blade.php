@extends('generate.pdf.layouts.main', ['title' => $filename])
@section('container')
    @include('generate.pdf.v2.style')
    <div class="container" style="border: 0">
        <h3 style="text-align: center">{{ $filename }}</h3>
        <table class="table bordered">
            <tbody>
                <tr>
                    <td><strong>Sopir</strong></td>
                    <td>{{ $sopir }}</td>
                </tr>
                {{-- <tr>
                    <td>Sisa Uang Jalan <br/>Dalam Jangka Waktu</td>
                    <td>{{ rupiah($totalHutangRange) }}</td>
                </tr> --}}
                <tr>
                    <td><strong>Dari</strong></td>
                    <td>{{ format_date($tanggalAwal) }}</td>
                </tr>
                <tr>
                    <td><strong>Sampai</strong></td>
                    <td>{{ format_date($tanggalAkhir) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="container">
        <table class="table bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Sopir</th>
                    {{-- <th>Uang Jalan</th> --}}
                    <th>Total Hutang</th>
                    <th>Total Sisa Uang Jalan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sopirList as $sopirItem)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $sopirItem->sopir }}</td>
                        {{-- <td>{{ rupiah($sopirItem->totalSisaUangJalan) }}</td> --}}
                        <td>{{ rupiah($sopirItem->totalHutang) }}</td>
                        <td>{{ rupiah($sopirItem->sisaUangJalanRange) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    @foreach ($orders as $order)
        <div class="container">
            <table class="table bordered table-text-left">
                <tr>
                    <td style="width: 50%"><strong>Tanggal</strong>: {{ format_date($order['tanggal']) }}</td>
                    <td colspan="3"><strong>Uang Jalan</strong>: {{ rupiah($order['uang_jalan']) }}</td>
                </tr>
                <tr>
                    <td style="width: 50%"><strong>No Transaksi</strong>: {{ $order['no_transaksi'] }}</td>
                    <td colspan="3"><strong>Potongan THR</strong>: {{ rupiah($order['pot_thr']) }}</td>
                </tr>
                <tr>
                    <td style="width: 50%"><strong>Asal/Tujuan</strong>: {{ $order['asal'] }} /
                        {{ $order['tujuan'] }}</td>
                    <td colspan="3"><strong>Biaya Lain-lain</strong>:
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%;vertical-align: top"><strong>Penyewa/muat</strong>:
                        {{ $order['penyewa'] }} /
                        {{ $order['muatan'] }}</td>
                    <td colspan="3">
                        @if (count($order['rincian_lain']) != 0)
                            <table class="table bordered">
                                <tr>
                                    <th>Ket</th>
                                    <th>Jml</th>
                                </tr>
                                @foreach ($order['rincian_lain'] as $item)
                                    <tr>
                                        <td>{{ $item['nama'] }}</td>
                                        <td>{{ rupiah($item['nominal']) }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Total Uang Jalan</strong></td>
                    <td colspan="2"> {{ rupiah($order['total_uang_jalan']) }}</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <strong>Rincian</strong>
                    </td>
                </tr>
                <tr>
                    <th>Tgl Bayar</th>
                    <th>Keterangan</th>
                    <th>Nominal</th>
                    <th>Sisa Uang Jalan</th>
                </tr>
                @if (count($order['rincian']) == 0)
                    <tr>
                        <td style="text-align: center" colspan="3">Rincian tidak tersedia</td>
                        <td rowspan="{{ count($order['rincian']) }}">{{ rupiah($order['sisa_uang_jalan']) }}</td>
                    </tr>
                @endif
                @foreach ($order['rincian'] as $index => $detail)
                    <tr>
                        <td>{{ format_date($detail['tanggal']) }}</td>
                        <td>{{ $detail['keterangan'] }}</td>
                        <td>{{ rupiah($detail['nominal']) }}</td>
                        @if ($index == 0)
                            <td rowspan="{{ count($order['rincian']) + 1}}">{{ rupiah($order['sisa_uang_jalan']) }}</td>
                        @endif
                    </tr>
                @endforeach
                <tr>
                    <td colspan="1"></td>
                    <td>Jumlah Pembayaran</td>
                    <td>{{rupiah(collect($order['rincian'])->sum('nominal'))}}</td>
                </tr>
            </table>

        </div>
        @php
            $i = $loop->iteration;
        @endphp

        @if ($i == 2)
            <div class="page_break"></div>

        @elseif ($i > 2 && ($i - 2) % 3 === 0 && !$loop->last)
            <div class="page_break"></div>
        @endif
    @endforeach
@endsection
