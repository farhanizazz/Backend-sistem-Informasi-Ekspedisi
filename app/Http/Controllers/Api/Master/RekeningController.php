<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\RekeningRequest\CreateRequest;
use App\Http\Requests\RekeningRequest\UpdateRequest;
use App\Models\Master\RekeningModel;
use Illuminate\Http\Request;

class RekeningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private  $rekeningModel;
    public function __construct()
    {
        $this->rekeningModel = new RekeningModel();
    }
    public function index()
    {
        $total = RekeningModel::selectRaw('SUM(biaya_kuli + biaya_akomodasi - claim - brg_rusak + biaya_tol) as total')->value('total');

        return response()->json([
            'total'=> $total,
            'status' => 'success',
            'data' => $this->rekeningModel->all()
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
        $total2 = RekeningModel::selectRaw('SUM(biaya_kuli + biaya_akomodasi - claim - brg_rusak + biaya_tol) as total')->value('total');
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }
        $this->rekeningModel->create([
            'biaya_kuli' => $request->biaya_kuli,
            'biaya_akomodasi' => $request->biaya_akomodasi,
            'claim' => $request->claim,
            'brg_rusak' => $request->brg_rusak,
            'biaya_tol'=>$request->biaya_tol,
            'total' => $request->total
        ]);

        return response()->json([
            'total2' => $total2,
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan'
        ]);
    }

        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function total(){
        $total = RekeningModel::selectRaw('SUM(biaya_kuli + biaya_akomodasi - claim - brg_rusak + biaya_tol) as total')->value('total');
        return response()->json([
            'status' => 'success',
            'data' => $total
        ]);
        return $total;
    }
    public function show($id)
    {
        try {
            //code...
            return response()->json([
                    'status' => 'success',
                    'data' => $this->rekeningModel->findOrFail($id)
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

        $response = $this->rekeningModel->findOrFail($id)->update($request->all());
        if (!$response) {
            return response()->json([
                    'status' => 'error',
                    'message' => 'Data gagal diubah'
                ]
            );
        }
        return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diubah'
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
    public function destroy($id)
    {
        try {
            $this->rekeningModel->findOrfail($id)->delete();
            return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus'
                ]
            );
        } catch (\Throwable $th) {
            return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]
            );
        }
    }
}
