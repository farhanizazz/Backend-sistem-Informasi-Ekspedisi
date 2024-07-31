<?php

namespace App\Helpers\Transaksi;

use App\Models\Transaksi\TransaksiTagihanDetModel;
use App\Models\Transaksi\TransaksiTagihanModel;
use Illuminate\Support\Facades\DB;
use TransaksiTagihan;
use TransaksiTagihanDet;

class TagihanHelper
{
    private $transaksiTagihanModel, $transaksiTagihanDetModel;
    public function __construct()
    {
        $this->transaksiTagihanModel = new TransaksiTagihanModel();
    }

    private function generateInvoice($payload)
    {
        // cek data no_invoice terakhir di tabel transaksi_tagihan
        $getLastTransaksi = TransaksiTagihanModel::orderByRaw(DB::raw("SUBSTRING(no_tagihan, 1, 4) DESC"))->first();

        // date now format DDMMYYYY
        $dateNow = date("dmy");

        $no_tagihan = "001/IPL/" . $payload['singkatan'] . $dateNow;
        // generate no_invoice baru
        if (!empty($getLastTransaksi) && !is_null($getLastTransaksi)) {
            $number_inc = substr($getLastTransaksi->no_tagihan, 0, 3);
            $number_inc++;
            $number_inc = str_pad($number_inc,3,0, STR_PAD_LEFT);
            $format_no_tagihan = substr($getLastTransaksi->no_tagihan, 3,5);
            $no_tagihan = $number_inc . $format_no_tagihan .$payload['singkatan']. $dateNow;
        }

        return $no_tagihan;
    }

    public function getDataPaginate($payload){
        try {
            $result = $this->transaksiTagihanModel->getDataWithPagination([]);
            return [
                "status" => true,
                "data" => $result
            ];
        } catch (\Throwable $th) {
            return [
                "status" => false,
                "message" => "Gagal mengambil data",
                "dev" => $th->getMessage()
            ];
        }
    }

    public function create($payload)
    {
        try {
            DB::beginTransaction();
            $payload['no_tagihan'] = $this->generateInvoice($payload);

            $transaksi_tagihan = TransaksiTagihanModel::create($payload);

            foreach ($payload['order_detail'] as $key => $value) {
                $transaksi_tagihan_det = TransaksiTagihanDetModel::create(array('transaksi_tagihan_id' => $transaksi_tagihan->id, 'transaksi_order_id' => $value));
                $transaksi_tagihan->transaksi_tagihan_det[$key] = $transaksi_tagihan_det;
            }

            DB::commit();

            return [
                "status" => true,
                "message" => "Berhasil membuat tagihan",
                "data" => $transaksi_tagihan
            ];
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return [
                "status" => false,
                "message" => "Gagal membuat tagihan",
                "dev" => $th->getMessage()
            ];
        }
    }

    public function delete(int | string  $id)
    {
        try {
            DB::beginTransaction();
            $transaksi_tagihan = TransaksiTagihanModel::with('transaksi_tagihan_det')->where('id', $id)->first();
            if (is_null($transaksi_tagihan)) {
                DB::rollBack();
                return [
                    "status" => false,
                    "message" => "Transaksi tagihan tidak ditemukan",
                    "dev" => "Transaksi tagihan tidak ditemukan"
                ];
            }
            $transaksi_tagihan_det = $transaksi_tagihan->transaksi_tagihan_det;
            foreach ($transaksi_tagihan_det as $key => $value) {
                $value->delete();
            }
            $transaksi_tagihan->delete();
            DB::commit();

            return [
                "status" => true,
                "message" => "Berhasil menghapus tagihan",
            ];
        } catch (\Throwable $th) {

            DB::rollBack();

            return [
                "status" => false,
                "message" => "Gagal menghapus tagihan",
                "dev" => $th->getMessage()
            ];
        }
    }

    public function getById(int | string  $id)
    {
        try {
            $transaksi_tagihan = TransaksiTagihanModel::with(['transaksi_tagihan_det.transaksi_order.armada', 'm_penyewa',  'master_rekening'])->where('id', $id)->first();
            if (is_null($transaksi_tagihan)) {
                return [
                    "status" => false,
                    "message" => "Data tidak ditemukan",
                    "dev" => "Data tidak ditemukan"
                ];
            }

            return [
                "status" => true,
                "message" => "Data berhasil ditemukan",
                "data"  => $transaksi_tagihan
            ];
        } catch (\Throwable $th) {

            DB::rollBack();

            return [
                "status" => false,
                "message" => "Gagal mengambil data",
                "dev" => $th->getMessage()
            ];
        }
    }
}
