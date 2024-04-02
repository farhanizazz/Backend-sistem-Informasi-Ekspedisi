<?php

namespace App\Console\Commands;

use App\Models\Master\ArmadaModel;
use App\Models\Transaksi\OrderModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetNoTransaksi extends Command
{
    public $armadaModel, $orderModel;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'no_transaksi:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atur ulang nomor transaksi mulai dari 1';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->armadaModel = new ArmadaModel();
        $this->orderModel = new OrderModel();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            //code...
            DB::beginTransaction();
            $no_transaksi = 0;
            $dataOrder = OrderModel::whereYear('tanggal_awal', '2024')->orderBy('tanggal_awal', 'asc')->get();
            foreach ($dataOrder as $key => $value) {
                $resultNoTransaksi = $this->setNoTransaksi($value, $no_transaksi);
                if($resultNoTransaksi['status']){
                    $value->update([
                        'no_transaksi' => $resultNoTransaksi['data']
                    ]);
                    $no_transaksi++;
                }else{
                    $this->error($resultNoTransaksi['message']);
                    return false;
                }
            }
    
            $no_transaksi = 0;
            $dataOrder = OrderModel::whereYear('tanggal_awal', '2023')->orderBy('tanggal_awal', 'asc')->get();
            foreach ($dataOrder as $key => $value) {
                $resultNoTransaksi = $this->setNoTransaksi($value, $no_transaksi);
                if($resultNoTransaksi['status']){
                    $value->update([
                        'no_transaksi' => $resultNoTransaksi['data']
                    ]);
                    $no_transaksi++;
                }else{
                    $this->error($resultNoTransaksi['message']);
                    return false;
                }
            }
            DB::commit();
            $this->info('Berhasil reset no transaksi');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $this->error('Gagal reset no transaksi');
        }
    }

    public function setNoTransaksi($payload, $no_transaksi = 0){
        switch ($payload['status_kendaraan']) {
            case 'Sendiri':
              $armada = $this->armadaModel->where('id', $payload['m_armada_id'])->first();
              break;
            default:
              $armada = (object) ["nopol"=> $payload['nopol_subkon']];
              break;
            }
          $tahun = date("Y", strtotime($payload['tanggal_awal']));
          $tanggal = date("Ymd", strtotime($payload['tanggal_awal']));
   
            $no_transaksi = str_replace(" ", "", $armada->nopol) . '.' . $tanggal . "." . str_pad((1 + $no_transaksi), 3, "0", STR_PAD_LEFT);
          
          return [
            "status" => true,
            "message" => "Berhasil generate no transaksi",
            "data" => $no_transaksi
          ];
    }


}
