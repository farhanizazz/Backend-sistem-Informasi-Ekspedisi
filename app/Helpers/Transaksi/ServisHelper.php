<?php

namespace App\Helpers\Transaksi;

use Illuminate\Http\Request;
use App\Models\Transaksi\ServisModel;
use App\Models\Transaksi\NotaBeliModel;
use App\Models\Master\ArmadaModel;


class ServisHelper
{
 
    public function create($request)
    {
        try {
            // Fetch the master_armada record
            $master_armada = ArmadaModel::find($request->master_armada_id);
            if (!$master_armada) {
                return [
                    'status' => false,
                    'message' => 'Master armada not found',
                    'dev' => 'Master armada not found',
                ];
            }
    
            // Create a new nota_beli record
            $nota_beli = new NotaBeliModel;
            $nota_beli->nama_barang = $request->nama_barang;
            $nota_beli->harga = $request->harga;
            $nota_beli->jumlah = $request->jumlah;
            $nota_beli->save();
    
            $servis = new ServisModel;
            $servis->nama_toko = $request->nama_toko; // Use the id of the newly created nota_beli record
            $servis->nopol = $master_armada->nopol;
            $servis->save();
    
            return [
                'status' => true,
                'message' => 'Servis created successfully',
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'Failed to create Servis',
                'dev' => $e->getMessage(),
            ];
        }
    }
    
    public function update($request, $id)
    {
        try {
            // Fetch the servis record
            $servis = ServisModel::find($id);
            if (!$servis) {
                return [
                    'status' => false,
                    'message' => 'Servis not found',
                    'dev' => 'Servis not found',
                ];
            }

            // Fetch the master_armada record
            $master_armada = ArmadaModel::find($request->master_armada_id);
            if (!$master_armada) {
                return [
                    'status' => false,
                    'message' => 'Master armada not found',
                    'dev' => 'Master armada not found',
                ];
            }

            // Update the nota_beli record
            $nota_beli = NotaBeliModel::find($servis->nota_beli_id);
            if ($nota_beli) {
                $nota_beli->nama_barang = $request->nama_barang;
                $nota_beli->harga = $request->harga;
                $nota_beli->jumlah = $request->jumlah;
                $nota_beli->save();
            }

            // Update the servis record
            $servis->nama_toko = $request->nama_toko;
            $servis->nopol = $master_armada->nopol;
            $servis->save();

            return [
                'status' => true,
                'message' => 'Servis updated successfully',
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