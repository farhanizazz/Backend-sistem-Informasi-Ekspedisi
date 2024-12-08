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

    @foreach ($orders as $order)
        <div class="container">
            <div class="header">
                <table class="table">
                    <tbody>
                        <tr>
                            <td style="width: 50%"><strong>Tanggal</strong>: {{ format_date($order['tanggal']) }}</td>
                            <td><strong>Uang Jalan</strong>: {{ rupiah($order['uang_jalan']) }}</td>
                        </tr>
                        <tr>
                            <td style="width: 50%"><strong>No Transaksi</strong>: {{ $order['no_transaksi'] }}</td>
                            <td><strong>Biaya Tambah / Kurang</strong>: {{ rupiah($order['biaya_tambahan']) }} /
                                {{ rupiah($order['biaya_kurang']) }}</td>
                        </tr>
                        <tr>
                            <td style="width: 50%"><strong>Penyewa/muat</strong>: {{ $order['penyewa'] }} /
                                {{ $order['muatan'] }}</td>
                            <td><strong>Potongan THR</strong>: {{ rupiah($order['pot_thr']) }}</td>
                        </tr>
                        <tr>
                            <td style="width: 50%"><strong>Asal/Tujuan</strong>: {{ $order['asal'] }} /
                                {{ $order['tujuan'] }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>

                <table class="table bordering-bottom" style="margin-top: 1rem;margin-bottom:1rem">
                    <tbody>
                        <tr>
                            <td><strong>Total Uang Jalan</strong></td>
                            <td> {{ rupiah($order['total_uang_jalan']) }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Rincian</strong>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table class="table table-text-left">
                <thead>
                    <tr>
                        <th>Tgl Bayar</th>
                        <th>Keterangan</th>
                        <th>Nominal</th>
                        <th>Sisa Uang Jalan</th>
                    </tr>
                </thead>
                <tbody>
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
                                <td rowspan="{{ count($order['rincian']) }}">{{ rupiah($order['sisa_uang_jalan']) }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="page_break"></div>
    @endforeach
@endsection
