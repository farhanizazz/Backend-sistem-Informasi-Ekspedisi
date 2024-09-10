<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\HutangSopirRequest\CreateRequest;
use App\Http\Requests\HutangSopirRequest\UpdateRequest;
use App\Http\Resources\HutangSopir\HutangPerSopirCollection;
use App\Models\Transaksi\HutangSopirModel;
use App\Services\HutangSopirService;
use Illuminate\Http\Request;

class HutangSopirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $hutangSopirModel;
    private $hutangSopirService;
    public function __construct(HutangSopirService $hutangSopirService)
    {
        $this->hutangSopirModel = new HutangSopirModel();
        $this->hutangSopirService = $hutangSopirService;
    }

    /**
     * @OA\GET(
     * path="/api/transaksi/hutang-sopir",
     * summary="Get data Hutang Sopir",
     * tags={"Transaksi Hutang Sopir"},
     * @OA\Response(
     * response=200,
     * description="Data Hutang Sopir berhasil ditemukan"
     * ),
     * )
     */
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
     * @OA\Post(
     * path="/api/transaksi/hutang-sopir",
     * summary="Create data Hutang Sopir",
     * tags={"Transaksi Hutang Sopir"},
     * @OA\RequestBody(
     *  @OA\JsonContent(
     *     required={"tgl_transaksi","master_sopir_id"}
     *  ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Data Hutang Sopir berhasil ditambahkan"
     * ),
     * )
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
     * @OA\Get(
     * path="/api/transaksi/hutang-sopir/{id}",
     * summary="Get data Hutang Sopir by ID",
     * tags={"Transaksi Hutang Sopir"},
     * @OA\Response(
     * response=200,
     * description="Data Hutang Sopir berhasil ditemukan"
     * ),
     * )
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
     * @OA\Put(
     * path="/api/transaksi/hutang-sopir/{id}",
     * summary="Update data Hutang Sopir",
     * tags={"Transaksi Hutang Sopir"},
     * @OA\RequestBody(
     *   required=true,
     *  @OA\JsonContent(
     *     required={"tgl_transaksi","master_sopir_id"}
     *  ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Data Hutang Sopir berhasil diubah"
     * ),
     * )
     */
    public function update(UpdateRequest $request, $id)
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
     * @OA\Delete(
     * path="/api/transaksi/hutang-sopir/{id}",
     * summary="Delete data Hutang Sopir",
     * tags={"Transaksi Hutang Sopir"},
     * tags={"Transaksi Hutang Sopir"},
     * @OA\Response(
     * response=200,
     * description="Data Hutang Sopir berhasil dihapus"
     * ),
     * )
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

    /**
     * @OA\Get(
     * path="/api/transaksi/hutang-sopir/total",
     * summary="Get total Hutang Sopir",
     * tags = {"Transaksi Hutang Sopir"},
     * @OA\Response(
     * response=200,
     * description="Total Hutang Sopir berhasil ditemukan"
     * )
     * )
     */
    public function total()
    {
        $data = $this->hutangSopirService->getTotalHutangSopir();
        return response()->json(
            [
                'status' => 'success',
                'data' => $data
            ]
        );
    }

    /**
     * @OA\Get(
     * path="/api/transaksi/hutang-sopir/total/{id}",
     * summary="Get total Hutang Sopir by ID",
     * tags = {"Transaksi Hutang Sopir"},
     * @OA\Response(
     * response=200,
     * description="Total Hutang Sopir berhasil ditemukan"
     * )
     * )
     */
    public function totalById($id)
    {
        $data = $this->hutangSopirService->getTotalHutangSopirById($id);
        return response()->json(
            [
                'status' => 'success',
                'data' => $data
            ]
        );
    }
    /**
     * @OA\Get(
     * path="/api/transaksi/hutang-sopir/{id}/list",
     * summary="Get Hutang Sopir by ID",
     * tags = {"Transaksi Hutang Sopir"},
     * @OA\Response(
     * response=200,
     * description="Hutang Sopir berhasil ditemukan"
     * )
     * )
     */
    public function getListHutangSopirById($id, Request $request)
    {
        $data = $this->hutangSopirService->getHutangSopirById($id, $request);
        return response()->json(
            [
                'status' => 'success',
                'data' => new HutangPerSopirCollection($data['list'], $data['sopir'])
            ]
        );
    }
}
