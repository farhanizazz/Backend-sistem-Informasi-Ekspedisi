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
                        <th style="text-align: center">Nopol</th>
                        <th style="text-align: center">Pemasukan Setor</th>
                        <th style="text-align: center">Pengeluaran Servis</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($data) == 0)
                        <tr>
                            <td style="text-align: center" colspan="3">Rincian tidak tersedia</td>
                        </tr>
                    @endif
                    @foreach ($data as $index => $detail)
                        <tr>
                            <td>{{ $detail->nopol }}</td>
                            <td>{{ rupiah($detail->pemasukan_setor) }}</td>
                            <td>{{ rupiah($detail->pengeluaran_servis) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    {{-- @endforeach --}}
@endsection
