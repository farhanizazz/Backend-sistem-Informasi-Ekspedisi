<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\HutangSopirRequest\CreateRequest;
use App\Models\Transaksi\HutangSopirModel;
use Illuminate\Http\Request;

class HutangSopirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $hutangSopirModel;
    public function __construct()
    {
        $this->hutangSopirModel = new HutangSopirModel();
    }
    public function index()
    {
        $data = HutangSopirModel::with('master_sopir')->get();

        return response()->json(
            [
                'status' => 'success',
                'data' => $data
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        //
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }
        $this->hutangSopirModel->create($request->all());

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data berhasil ditambahkan'
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            //code...
            return response()->json(
                [
                    'status' => 'success',
                    'data' => $this->hutangSopirModel->with('master_sopir')->findOrFail($id)
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

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

            $response = $this->hutangSopirModel->findOrFail($id)->update($request->all());
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
        //

        try {
            $this->hutangSopirModel->findOrfail($id)->delete();
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
