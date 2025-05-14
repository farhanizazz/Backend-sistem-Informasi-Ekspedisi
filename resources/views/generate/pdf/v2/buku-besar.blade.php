@extends('generate.pdf.layouts.main', ['title' => $filename])

@section('container')
    @include('generate.pdf.v2.style')
    <div class="container" style="border: 0">
        <h3 style="text-align: center">{{ $filename }}</h3>
        <table class="table bordered">
            <tbody>
                <tr>
                    <td><strong>Rekening</strong></td>
                    <td>{{ $rekening }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal</strong></td>
                    <td>{{ $jangkaTanggal }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Cetak</strong></td>
                    <td>{{ format_date(date('Y-m-d H:i')) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="container" style="border-bottom: 0">
        <table class="table bordered table-text-left">
            <thead>
                <tr>
                    <th style="text-align: center">Nomor</th>
                    <th style="text-align: center">Tanggal</th>
                    <th style="text-align: center">No Transaksi</th>
                    <th style="text-align: center">Keterangan</th>
                    <th style="text-align: center">Debet</th>
                    <th style="text-align: center">Kredit</th>
                    <th style="text-align: center">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @if (count($data) == 0)
                    <tr>
                        <td colspan="7" style="text-align: center">Tidak ada data</td>
                    </tr>
                @endif

                @foreach ($data as $group)
                    <tr>
                        <td colspan="7" style="font-weight: bold; background-color: #f1f1f1">
                            Tanggal: {{ $group['tanggal'] }}
                        </td>
                    </tr>
                    @foreach ($group['armadas'] as $armada)
                        <tr>
                            <td colspan="7" style="font-weight: bold; padding-left: 20px;">
                                Nopol: {{ $armada['nopol'] }}
                            </td>
                        </tr>
                        @foreach ($armada['items'] as $detail)
                            <tr>
                                <td style="text-align: center">{{ $detail['no'] ?? '-' }}</td>
                                <td style="text-align: center">{{ $group['tanggal'] }}</td>
                                <td style="text-align: center">{{ $detail['no_transaksi'] }}</td>
                                <td style="text-align: left">
                                    <strong>{{ $detail['asal_transaksi'] }}</strong><br>
                                    {{ $detail['jenis_transaksi'] }} : {{ $detail['keterangan'] }}
                                </td>
                                <td style="text-align: right">{{ $detail['debet'] }}</td>
                                <td style="text-align: right">{{ $detail['kredit'] }}</td>
                                <td style="text-align: right">{{ $detail['total'] }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
