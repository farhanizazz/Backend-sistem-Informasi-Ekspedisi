@extends('generate.pdf.layouts.main', ['title' => $filename])
@section('container')
    @include('generate.pdf.v2.style')
    <div class="container" style="border: 0">
        <h3 style="text-align: center">{{ $filename }}</h3>
        <table class="table bordered">
            <tbody>
                @if ($customer)
                    <tr>
                        <td><strong>Pelanggan</strong></td>
                        <td>{{ $customer->nama_perusahaan }}</td>
                    </tr>
                @endif
                {{-- <tr>
                    <td><strong>Total</strong> Hutang Realtime</td>
                    <td>{{ rupiah($totalHutang) }}</td>
                </tr> --}}
                <tr>
                    <td><strong>Total Hutang <br />Dalam Jangka Waktu</strong></td>
                    <td>{{ rupiah($totalHutangRange) }}</td>
                </tr>
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
        <div class="header">
            <table class="table">
            </table>
        </div>
        <table class="table table-text-left bordered">
            <thead>
                <tr>
                    <th>Tgl Bayar / Nomor Transaksi</th>
                    <th>Nopol / Sopir / Muatan</th>
                    <th>Asal / Tujuan</th>
                    <th>Harga Order</th>
                    <th>Biaya Tambah / Kurang</th>
                    <th>PPH / PPN</th>
                    <th>Sisa Tagihan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>
                        {{ format_date($order['tanggal']) }} / {{ $order['no_transaksi'] }}
                    </td>
                    <td>
                        {{ $order['nopol'] }} / {{ $order['sopir'] }} / {{ $order['muatan'] }}
                    </td>
                    <td>
                        {{ $order['asal'] }} / {{ $order['tujuan'] }}
                    </td>
                    <td>
                        {{ rupiah($order['harga_order']) }}
                    </td>
                    <td>
                        {{ rupiah($order['biaya_tambah_kurang']) }}
                    </td>
                    <td>
                        {{ rupiah($order['pph']) }}
                    </td>
                    <td>
                        {{ rupiah($order['sisa_tagihan']) }}
                    </td>
                    
                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
