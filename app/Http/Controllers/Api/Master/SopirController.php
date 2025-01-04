<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\SopirRequest\CreateRequest;
use App\Http\Requests\SopirRequest\UpdateRequest;
use App\Http\Resources\Sopir\SopirCollection;
use App\Models\Master\SopirModel;
use App\Services\SopirService;
use Illuminate\Http\Request;

class SopirController extends Controller
{
    private $sopirModel, $sopirService;

    public function __construct(SopirService $sopirService)
    {
        $this->sopirModel = new SopirModel();
        $this->sopirService = $sopirService;
    }

    /**
     * @OA\Get(
     * path="/api/master/sopir",
     * summary="Get data Sopir",
     * security={{ "apiAuth": {} }},
     * tags={"Sopir"},
     * @OA\Response(
     *  response=200,
     *  description="Data Sopir berhasil ditemukan"
     * ),
     * ),
     * @OAS\SecurityScheme(
     *  securityScheme="bearerAuth",
     *  type="http",
     *  scheme="bearer",
     *  bearerFormat="JWT"
     * )
     */
    public function index(Request $request)
    {
        $data = $this->sopirService->getAll($request);

        return response()->json(
            [
                'status' => 'success',
                'data' => $data
            ]
        );
    }

        /**
     * @OA\Get(
     * path="/api/master/sopir/paginate",
     * summary="Get data Sopir",
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="itemPerPage",
     * in="query",
     * description="Jumlah data per halaman",
     * required=false,
     * ),
     * tags={"Sopir"},
     * @OA\Response(
     *  response=200,
     *  description="Data Sopir berhasil ditemukan"
     * ),
     * ),
     * @OAS\SecurityScheme(
     *  securityScheme="bearerAuth",
     *  type="http",
     *  scheme="bearer",
     *  bearerFormat="JWT"
     * )
     */
    public function paginate(Request $request)
    {
        $data = $this->sopirService->getPaginate($request);

        return response()->json(
            [
                'status' => 'success',
                'data' => new SopirCollection($data)
            ]
        );
    }

    /**
     * @OA\Post(
     * path="/api/master/sopir",
     * summary="Tambah data sopir",
     * security={{ "apiAuth": {} }},
     * tags = {"Sopir"},
     * @OA\RequestBody(
     *   required=true,
     *   description="Data yang dibutuhkan untuk menambah data sopir",
     *   @OA\JsonContent(
     *     required={"nama","ktp","sim","nomor_hp","alamat","tanggal_gabung","status"},
     *     
     *   )
     * ),
     * @OA\Response(
     *   response=200,
     *  description="Data Sopir berhasil ditambahkan"
     *  ),
     * @OA\Response(
     *  response=400,
     * description="Data Sopir gagal ditambahkan"
     * ),
     * @OA\Response(
     * response=422,
     * description="Data yang dibutuhkan tidak lengkap"
     * ),
     * ),
     * @OAS\SecurityScheme(
     *  securityScheme="bearerAuth",
     *  type="http",
     *  scheme="bearer",
     *  bearerFormat="JWT"
     * )
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
        $result = $this->sopirModel->create($request->all());

        return response()->json(
            [
                'status' => 'success',
                'message' => 'Data berhasil ditambahkan',
                'data' => $result
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

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
                    'data' => $this->sopirModel->findOrFail($id)
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
    public function update(UpdateRequest $request, $id)
    {

        try {
            //code...
            if (isset($request->validator) && $request->validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => $request->validator->errors()->all()
                    ]
                );
            }

            $response = $this->sopirModel->findOrFail($id)->update($request->all());
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
                    'message' => 'Data berhasil diubah',
                    'data' => $this->sopirModel->findOrFail($id)
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
            $this->sopirModel->findOrfail($id)->delete();
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
