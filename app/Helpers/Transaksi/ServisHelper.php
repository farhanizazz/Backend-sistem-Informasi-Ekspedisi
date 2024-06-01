<?php

namespace App\Helpers\Transaksi;

use Illuminate\Http\Request;
use App\Models\Transaksi\ServisModel;
use App\Models\Transaksi\NotaBeliModel;
use App\Models\Master\ArmadaModel;
use App\Models\Master\MutasiModel;
use App\Models\Transaksi\ServisMutasiModel;
use Illuminate\Support\Facades\DB;

class ServisHelper
{
    private $servisModel,$notaBeliModel, $masterMutasiModel, $servisMutasiModel;
    public function __construct()
    {
        $this->servisModel= new ServisModel();
        $this->notaBeliModel = new NotaBeliModel();
        $this->masterMutasiModel = new MutasiModel();
        $this->servisMutasiModel = new ServisMutasiModel();
    }
 
    /**
     * Create a new Servis record
     */
    public function create(Request $payload)
    {
        try {
            DB::beginTransaction(); 
            $dataSave= $payload->only([
                "master_armada_id",
                "nama_toko",
                "nota_beli_items", // This should be an array of objects
                "tanggal_servis",
                'kategori_servis',
                'nama_tujuan_lain',
                'keterangan_lain',
                'nominal_lain',
                'jumlah_lain',
                'total_lain',
            ]);
            
            $result = $this->servisModel->create($dataSave);
            
            foreach (($dataSave['nota_beli_items'] ?? []) as $item) {
                $return = $this->createNotaBeliItem($item, $result);
            }
            DB::commit();
            return [
                'status' => true,
                'message' => 'Servis created successfully',
                "data" => $return,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Failed to create Servis',
                'dev' => $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile(),
            ];
        }
    }
    
    /**
     * Create a new NotaBeli record
     */
    public function createNotaBeliItem(array $nota_beli, $servis)
    {
        $notaBeliData = [
            'servis_id' => $servis->id,
            "nama_barang" => $nota_beli['nama_barang'],
            "harga" => $nota_beli['harga'],
            "jumlah" => $nota_beli['jumlah'],
        ];
        
        $this->notaBeliModel->create($notaBeliData);

    }

    /**
     * Update an existing NotaBeli record
     */
    public function updateNotaBeliItem(array $nota_beli, $servis){
        $notaBeliData = [
            "nama_barang" => $nota_beli['nama_barang'],
            "harga" => $nota_beli['harga'],
            "jumlah" => $nota_beli['jumlah'],
            'servis_id' => $servis->id,
        ];

        // Update the existing NotaBeli record if it exists, otherwise create a new one
        $notaBeli = $this->notaBeliModel->updateOrCreate(['id' => ($item['id'] ?? -1)], $notaBeliData);
    }

    public function update(Request $payload,$id)
    {
    try {
        $dataSave = $payload->only([
            "master_armada_id",
            "nama_toko",
            "nota_beli_items",
            "tanggal_servis",
            'kategori_servis',
            'nama_tujuan_lain',
            'keterangan_lain',
            'nominal_lain',
            'jumlah_lain',
            'total_lain',
        ]);

        // Fetch the Servis model
        $servis = $this->servisModel->find($id);

        // Check if the Servis model was found
        if (!$servis) {
            throw new \Exception('Servis not found');
        }

        $notaBeliItems = $dataSave['nota_beli_items'] ?? [];

        // delete data nota beli yang tidak ada di request
        $nota_beli_ids = array_column($notaBeliItems, 'id');
        $nota_beli_delete = $this->notaBeliModel->where('servis_id', $servis->id)->whereNotIn('id', $nota_beli_ids)->get();
        $nota_beli_delete_ids = $nota_beli_delete->pluck('id')->toArray();
        $this->masterMutasiModel->whereIn('model_id', $nota_beli_delete_ids)->where('asal_transaksi','nota_beli')->delete();
        $this->notaBeliModel->where('servis_id', $servis->id)->whereNotIn('id', $nota_beli_ids)->delete();

        foreach ($notaBeliItems as $item) {
            $this->updateNotaBeliItem($item, $servis);
        }

        // Update Servis sesuai dengan kategori_servis yang dipilih
        switch ($dataSave['kategori_servis']) {
            case 'servis':
                $servisData = [ 
                    "master_armada_id" => $dataSave['master_armada_id'],
                    "nama_toko" => $dataSave['nama_toko'],
                    "tanggal_servis" => $dataSave['tanggal_servis'],
                    'kategori_servis' => $dataSave['kategori_servis'], // Add the 'kategori_servis' key to the 'servisData' array
                ];
                // Update the Servis record
                $servis->update($servisData);
                break;
            case 'lain':
                $servisData = [ 
                    "master_armada_id" => $dataSave['master_armada_id'],
                    "nama_toko" => $dataSave['nama_toko'],
                    "tanggal_servis" => $dataSave['tanggal_servis'],
                    'kategori_servis' => $dataSave['kategori_servis'], // Add the 'kategori_servis' key to the 'servisData' array
                    "nama_tujuan_lain" => $dataSave['nama_tujuan_lain'],
                    "keterangan_lain" => $dataSave['keterangan_lain'],
                    "nominal_lain" => $dataSave['nominal_lain'],
                    "jumlah_lain" => $dataSave['jumlah_lain'],
                    "total_lain" => $dataSave['total_lain'],
                ];
                
                // Update the Servis record
                $servis->update($servisData);
                break;
            
            default:
                # code...
                break;
        }

        return [
            'status' => true,
            'message' => 'Servis updated successfully',
            "data" => $servis,
        ];
    } catch (\Exception $e) {
        return [
            'status' => false,
            'message' => 'Failed to update Servis',
            'dev' => $e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile(),
        ];
    }
    }

    public function createServisMutasi($payload){
        try {
            DB::beginTransaction();

            $payload = $payload->only([
                'servis_id',
                'master_rekening_id',
                'nominal',
            ]);

            // Buat Mutasi baru
            $payload_mutasi = $this->masterMutasiModel->create([
                'asal_transaksi' => 'servis',
                'model_type' => 'App\\\Models\\\Transaksi\\\ServisModel',
                'model_id' => $payload['servis_id'],
                'tanggal_pembayaran' => date('Y-m-d'),
                'nominal' => $payload['nominal'],
                'master_rekening_id' => $payload['master_rekening_id'],
                'jenis_transaksi' => 'jual'
            ]);

            // ambil id dari mutasi yang baru dibuat
            $payload['master_mutasi_id'] = $payload_mutasi->id;

            $result = $this->servisMutasiModel->create($payload);

            DB::commit();
            return [
                'status' => true,
                'message' => 'Servis mutasi created successfully',
                'data' => $result
            ];
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Failed to create Servis mutasi',
                'dev' => $th->getMessage() . ' ' . $th->getLine() . ' ' . $th->getFile(),
            ];
        }
    }

    public function hapusServisMutasi($id){
        try {
            $result = $this->servisMutasiModel->find($id);
            if (!$result) {
                return [
                    'status' => false,
                    'message' => 'Servis mutasi not found',
                ];
            }
            $result->delete();
            $result->master_mutasi->delete();
            return [
                'status' => true,
                'message' => 'Servis mutasi deleted successfully',
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'message' => 'Failed to delete Servis mutasi',
                'dev' => $th->getMessage() . ' ' . $th->getLine() . ' ' . $th->getFile(),
            ];
        }
    }
}