<?php

namespace App\Services;

use App\Repositories\Contracts\LaporanInterface;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;

class LaporanPengeluaranService
{
  private $laporanPengeluaran;

  public function __construct(LaporanInterface $laporanPengeluaran)
  {
    $this->laporanPengeluaran = $laporanPengeluaran;
  }

  public function getLaporanPengeluaranServis(Request $request)
  {
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;
    $itemPerPage = $request->itemPerPage;
    $all = $request->is_all ?? false;
    $m_armada_id = isset($request->m_armada_id) ? json_decode($request->m_armada_id) : [];
    return $this->laporanPengeluaran->getLaporanPengeluaranServis($tanggal_awal, $tanggal_akhir, $m_armada_id, $itemPerPage, $all);
  }

  public function getLaporanPengeluaranLain(Request $request)
  {
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;
    $itemPerPage = $request->itemPerPage;
    $all = $request->is_all ?? false;
    $m_armada_id = isset($request->m_armada_id) ? json_decode($request->m_armada_id) : [];
    return $this->laporanPengeluaran->getLaporanPengeluaranLain($tanggal_awal, $tanggal_akhir, $m_armada_id, $itemPerPage, $all);
  }

  public function getLaporanPengeluaranSemua(Request $request)
  {
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;
    $itemPerPage = $request->itemPerPage;
    $all = $request->is_all ?? false;
    $m_armada_id = isset($request->m_armada_id) ? json_decode($request->m_armada_id) : [];
    return $this->laporanPengeluaran->getLaporanPengeluaranServis($tanggal_awal, $tanggal_akhir, $m_armada_id, $itemPerPage, $all);
  }

  public function generateWord($data)
  {

    $templateProcessor = new TemplateProcessor('assets/documents/template-laporan-pengeluaran.docx');

    $templateProcessor->setValue('title', $data['title']);
    $templateProcessor->setValue('periode', $data['periode']);

    $total = 0;
    $increment = 0;
    $values = array_map(function ($item) use (&$increment, &$total) {
      $increment++;
      $total += (int)$item['subtotal'];
      return [
        'no' => $increment,
        'tanggal' => htmlspecialchars($item['tanggal']),
        'nopol' => htmlspecialchars($item['nopol']),
        'nama_barang' => htmlspecialchars($item['nama_barang']),
        'nomor_nota' => htmlspecialchars($item['nomor_nota']),
        'nama_toko' => htmlspecialchars($item['nama_toko']),
        'keterangan' => htmlspecialchars($item['keterangan']),
        'harga' => $this->rupiah(htmlspecialchars($item['harga'])),
        'jumlah' => htmlspecialchars($item['jumlah']),
        'subtotal' => $this->rupiah(htmlspecialchars($item['subtotal']))
      ];
    }, $data['data']);

    $fileName = $data['filename'] . '.docx';

    $templateProcessor->setValue('total', $this->rupiah(htmlspecialchars($total)));
    $templateProcessor->cloneRowAndSetValues('no', $values);
    $templateProcessor->saveAs(storage_path($fileName));

    return storage_path($fileName);
  }

  function rupiah($angka)
  {
    $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
    return $hasil_rupiah;
  }

  function penyebut($nilai)
  {
    $nilai = abs($nilai);
    $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    if ($nilai < 12) {
      $temp = " " . $huruf[$nilai];
    } else if ($nilai < 20) {
      $temp = penyebut($nilai - 10) . " belas";
    } else if ($nilai < 100) {
      $temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
    } else if ($nilai < 200) {
      $temp = " seratus" . penyebut($nilai - 100);
    } else if ($nilai < 1000) {
      $temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
    } else if ($nilai < 2000) {
      $temp = " seribu" . penyebut($nilai - 1000);
    } else if ($nilai < 1000000) {
      $temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
    } else if ($nilai < 1000000000) {
      $temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
      $temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
    } else if ($nilai < 1000000000000000) {
      $temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
  }
}
