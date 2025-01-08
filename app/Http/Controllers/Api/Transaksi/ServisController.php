<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Servis\ServisCollection;
use App\Http\Resources\Servis\ServisResource;
use App\Models\Transaksi\ServisModel;
use App\Helpers\Transaksi\ServisHelper;
use App\Http\Requests\ServisMutasiRequest\CreateServisMutasiRequest;
use App\Http\Requests\ServisRequest\CreateRequest;
use App\Http\Requests\ServisRequest\UpdateRequest;
use Illuminate\Support\Facades\DB;

class ServisController extends Controller
{
    //
    // private $servisHelper, $serviceModel;

    private $servisModel, $servisHelper;
    public function __construct()
    {
        // $this->servisHelper = new ServisHelper();
        $this->servisHelper = new ServisHelper();
        $this->servisModel = new ServisModel();

    }

    /**
     * @OA\Get(
     * path="/api/laporan/servis",
     * summary="Get data servis tipe servis",
     * tags={"Servis Kategori Servis"},
     * @OA\Parameter(
     *  name="search",
     *  description="Kata kunci untuk mencari data servis tipe servis",
     *  required=false,
     *  in="query",
     * ),
     * @OA\Response(
     *  response=200,
     *  description="Data servis tipe servis berhasil ditemukan"
     * ),
     * )
     */
    public function index(Request $request)
    {
        $result = $this->servisModel->getAllServis($request->all());

        return response()->json([
            'status' => 'success',
            'data' => new ServisCollection($result)
        ]);
    }
    
    public function store(CreateRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }
        $result =  $this->servisHelper->create($request);
        if (!$result['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
                'dev'   => $result['dev']
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }
    public function update(UpdateRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }
        $result =  $this->servisHelper->update($request, $id);
        if (!$result['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
                'dev'   => $result['dev']
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }
    public function destroy($id, Request $request)
    {
        try {
            $service = ServisModel::find($id);
            if ($service) {
                DB::beginTransaction(); 
                if($request->force == "true"){
                    $service->nota_beli_items()->delete();
                    $service->servis_mutasi()->delete();
                    $service->delete();
                }else{
                    $service->delete();
                }
            
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus'
                ]);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan'
                ]);
            }
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
            ]);
        }
    }
    public function show($id)
    {
        try {
            $result =  $this->servisModel->with('nota_beli_items', 'servis_mutasi.master_mutasi.master_rekening','master_armada')-> findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => new ServisResource($result)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    /**
     * @OA\Post(
     * path="/api/laporan/servis/mutasi",
     * summary="Create Servis Mutasi",
     * tags={"Servis Mutasi"},
     * @OA\RequestBody(
     *  required=true,
     *  @OA\MediaType(
     *    mediaType="application/json",
     *    @OA\Schema
     *      (
     *          type="object",
     *          required={"servis_id", "master_rekening_id", "nominal"},
     *      )
     *  )
     * ),
     * @OA\Response(
     *  response=200,
     *  description="Servis mutasi berhasil dibuat"
     * ),
     * @OA\Response(
     *  response=422,
     *  description="Data tidak valid"
     * )
     * )
     */
    public function createServisMutasi(CreateServisMutasiRequest $request){
        $result = $this->servisHelper->createServisMutasi($request);
        if (!$result['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
                'dev'   => $result['dev']
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }

    public function deleteServisMutasi($id){
        $result = $this->servisHelper->hapusServisMutasi($id);
        if (!$result['status']) {
            return response()->json([
                'status' => 'error',
                'message' => $result['message'],
                'dev'   => $result['dev']
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => $result['message']
        ]);
    }

}
