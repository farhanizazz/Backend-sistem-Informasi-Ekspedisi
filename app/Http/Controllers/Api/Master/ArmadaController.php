<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArmadaRequest\CreateRequest;
use App\Http\Requests\ArmadaRequest\UpdateRequest;
use App\Models\Master\ArmadaModel;
use App\Models\Master\MutasiModel;
use App\Models\Transaksi\NotaBeliModel;
use App\Models\Transaksi\OrderModel;
use App\Models\Transaksi\ServisModel;
use App\Models\Transaksi\ServisMutasiModel;
use App\Models\Transaksi\TransaksiTagihanDetModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArmadaController extends Controller
{

    private $armadaModel;
    public function __construct()
    {
        $this->armadaModel = new ArmadaModel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->armadaModel->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }

        $result = $this->armadaModel->create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan',
            'data' => $result
        ]);
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            //code...
            return response()->json([
                    'status' => 'success',
                    'data' => $this->armadaModel->findOrFail($id)
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            //code...
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }
        
        $response = $this->armadaModel->findOrFail($id)->update($request->all());
        if (!$response) {
            return response()->json([
                    'status' => 'error',
                    'message' => 'Data gagal diubah'
                ]
            );
        }
        return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diubah',
                'data' => $this->armadaModel->findOrFail($id)
            ]
        );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        try {
            DB::beginTransaction();
            if($request->force == "true"){
                $servis = ServisModel::where('master_armada_id', $id)->get();
                NotaBeliModel::whereIn('servis_id', $servis->pluck('id'))->delete();
                ServisMutasiModel::whereIn('servis_id', $servis->pluck('id'))->delete();
                ServisModel::where('master_armada_id', $id)->delete();

                $order = OrderModel::where('m_armada_id', $id)->get();
                    TransaksiTagihanDetModel::whereIn('transaksi_order_id', $order->pluck('id'))->delete();
                    MutasiModel::whereIn('transaksi_order_id', $order->pluck('id'))->delete();
                    OrderModel::where('m_armada_id', $id)->delete();

                $this->armadaModel->findOrfail($id)->delete();
            }else{
                $this->armadaModel->findOrfail($id)->delete();
            
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]
        );
            
        } catch (\Throwable $th) {
            DB::rollBack();
            if ($th->getCode() == 23000) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Data ini tidak dapat diubah karena sedang digunakan di tabel lain.'
                    ]
                );
            }


            return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]
            );
        }
    }
}
