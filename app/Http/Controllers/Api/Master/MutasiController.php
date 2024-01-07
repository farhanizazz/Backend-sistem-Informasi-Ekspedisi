<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\MutasiRequest\CreateRequest;
use App\Http\Requests\MutasiRequest\UpdateRequest;
use Illuminate\Http\Request;
use App\Models\Master\MutasiModel;


class MutasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private  $mutasiModel;
    public function __construct()
    {
        $this->mutasiModel = new MutasiModel();
    }
    public function index(Request $request)
    {
        $result = $this->mutasiModel->getAll($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $result
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
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }
        $this->mutasiModel->create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan'
        ]);
    }
    public function filterByNamaBank($nama_bank)
{
    $transactions = MutasiModel::with(['transaksiOrder', 'rekening'])
        ->whereHas('rekening', function ($query) use ($nama_bank) {
            $query->where('nama_bank', $nama_bank);
        })
        ->get();

    if ($transactions->isEmpty()) {
        return response()->json([
            'status' => 'error',
            'message' => 'No transactions found'
        ]);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Data retrieved successfully',
        'data' => $transactions
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
            return response()->json(
                [
                    'status' => 'success',
                    'data' => $this->mutasiModel->findOrFail($id)
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
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
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => $request->validator->errors()
                    ]
                );
            }

            $response = $this->mutasiModel->findOrFail($id)->update($request->all());
            if (!$response) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Data gagal diubah'
                    ]
                );
            }
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data berhasil diubah'
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(
                [
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
            $this->mutasiModel->findOrfail($id)->delete();
            return response()->json(
                [
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus'
                ]
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]
            );
        }
    }
}