<?php

namespace App\Helpers\Transaksi;

use Illuminate\Http\Request;
use App\Models\Transaksi\ServisModel;
use App\Models\Transaksi\NotaBeliModel;
use App\Models\Master\ArmadaModel;
use Illuminate\Support\Facades\DB;

class ServisHelper
{
    private $servisModel,$notaBeliModel,$masterArmadaModel;
    public function __construct()
    {
        $this->servisModel= new ServisModel();
        $this->notaBeliModel = new NotaBeliModel();
        $this->masterArmadaModel = new ArmadaModel();
    }
 
    public function create(Request $payload)
    {
        try {
            DB::beginTransaction(); 
            $dataSave= $payload->only([
                "master_armada_id",
                "nama_toko",
                "nota_beli_items", // This should be an array of objects
                "tanggal_servis",
                "kategori_servis"
            ]);
            
            // Fetch the MasterArmada model
            $masterArmada = $this->masterArmadaModel->find($dataSave['master_armada_id']);
            
            // Check if the MasterArmada model was found
            if (!$masterArmada) {
                throw new \Exception('MasterArmada not found');
            }
            
            // Get the nopol from the MasterArmada model
            $nopol = $masterArmada->nopol;
            
            $notaBeliItems = $dataSave['nota_beli_items'];
            $notaBeliIds = [];

                
            $servisData = [
                "master_armada_id" => $dataSave['master_armada_id'],
                "nama_toko" => $dataSave['nama_toko'],
                "tanggal_servis" => $dataSave['tanggal_servis'],
                "nopol" => $nopol, // get nopol,
                "kategori_servis" => $dataSave['kategori_servis']
            ];
            
            $result = $this->servisModel->create($servisData);
            
            foreach ($notaBeliItems as $item) {
                $notaBeliData = [
                    // "master_armada_id" => $dataSave['master_armada_id'],
                    'servis_id' => $result->id,
                    "nama_barang" => $item['nama_barang'],
                    "harga" => $item['harga'],
                    "jumlah" => $item['jumlah'],
                ];
            
                $notaBeli = $this->notaBeliModel->create($notaBeliData);
                $notaBeliIds[] = $notaBeli->id;
            }
            DB::commit();
            return [
                'status' => true,
                'message' => 'Servis created successfully',
                "data" => $result,
                "servis_id" => $result->id,
                "nopol" => $nopol, // Return the nopol of the MasterArmada model // Return the ID of the created servis record
                "nota_beli_ids" => $notaBeliIds
                 // Return the IDs of the created notaBeli records
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => 'Failed to create Servis',
                'dev' => $e->getMessage(),
            ];
        }
    }
    
    public function createServis($payload)
    {

    }

    public function update(Request $payload,$id)
{
    try {
        $dataSave = $payload->only([
            "master_armada_id",
            "nama_toko",
            "nota_beli_items",
            "tanggal_servis",
            'kategori_servis'
        ]);

        // Fetch the Servis model
        $servis = $this->servisModel->find($id);

        // Check if the Servis model was found
        if (!$servis) {
            throw new \Exception('Servis not found');
        }

        // Fetch the MasterArmada model
        $masterArmada = $this->masterArmadaModel->find($dataSave['master_armada_id']);

        // Check if the MasterArmada model was found
        if (!$masterArmada) {
            throw new \Exception('MasterArmada not found');
        }

        // Get the nopol from the MasterArmada model
        $nopol = $masterArmada->nopol;

        $notaBeliItems = $dataSave['nota_beli_items'];
        $notaBeliIds = [];

        foreach ($notaBeliItems as $item) {
            $notaBeliData = [
                "master_armada_id" => $dataSave['master_armada_id'],
                "nama_barang" => $item['nama_barang'],
                "harga" => $item['harga'],
                "jumlah" => $item['jumlah'],
                'servis_id' => $servis->id,
            ];

            // Update the existing NotaBeli record if it exists, otherwise create a new one
            $notaBeli = $this->notaBeliModel->updateOrCreate(['id' => ($item['id'] ?? -1)], $notaBeliData);
            $notaBeliIds[] = $notaBeli->id;
        }

        // delete data nota beli yang tidak ada di request
        $nota_beli_ids = array_column($notaBeliItems, 'id');
        $this->notaBeliModel->where('servis_id', $servis->id)->whereNotIn('id', $nota_beli_ids)->delete();

        switch ($dataSave['kategori_servis']) {
            case 'servis':
                $servisData = [ 
                    "master_armada_id" => $dataSave['master_armada_id'],
                    "nama_toko" => $dataSave['nama_toko'],
                    "tanggal_servis" => $dataSave['tanggal_servis'],
                    'kategori_servis' => $dataSave['kategori_servis'], // Add the 'kategori_servis' key to the 'servisData' array
                    "nopol" => $nopol,
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
                    "nopol" => $nopol,
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
            "nopol" => $nopol,
            "nota_beli_ids" => $notaBeliIds
        ];
    } catch (\Exception $e) {
        return [
            'status' => false,
            'message' => 'Failed to update Servis',
            'dev' => $e->getMessage(),
        ];
    }
}
}