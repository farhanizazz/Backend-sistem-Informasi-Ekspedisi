<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Helpers\Transaksi\ServisHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServisMutasiRequest\CreateServisMutasiRequest;
use App\Http\Requests\ServisRequest\CreateRequest;
use App\Http\Requests\ServisRequest\UpdateRequest;
use App\Http\Resources\Servis\ServisResource;
use App\Models\Transaksi\ServisModel;
use Illuminate\Http\Request;

class LainController extends Controller
{
    private $servisModel, $servisHelper;
    public function __construct()
    {
        // $this->servisHelper = new ServisHelper();
        $this->servisHelper = new ServisHelper();
        $this->servisModel = new ServisModel();

    }

    public function index(Request $request)
    {
        $result = $this->servisModel->getAll($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/transaksi/lainlain",
     * summary="Tambah data servis tipe lain-lain",
     * tags = {"Lain Lain"},
     * @OA\RequestBody(
     *   required=true,
     *   description="Data yang dibutuhkan untuk menambah data servis tipe lain-lain",
     *   @OA\JsonContent(
     *     required={"master_armada_id", "nomor_nota", "nama_toko", "tanggal_servis", "nota_beli_items", "kategori_servis", "nama_tujuan_lain", "keterangan_lain", "nominal_lain", "jumlah_lain", "total_lain"},
     *   )
     * ),
     * @OA\Response(
     *   response=200,
     *  description="Data servis berhasil ditambahkan"
     *  ),
     * @OA\Response(
     *  response=400,
     * description="Data servis gagal ditambahkan"
     * ),
     * @OA\Response(
     * response=422,
     * description="Data yang dibutuhkan tidak lengkap"
     * ),
     * )
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
            'message' => $result['message']
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
            'message' => $result['message']
        ]);
    }
    public function destroy($id)
    {
        try {
            $service = ServisModel::find($id);
            if ($service) {
                // $service->servis_mutasi->master_mutasi()->delete();
                // $service->servis_mutasi()->delete();
                $service->nota_beli_items()->delete();
                $service->delete();

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
            'message' => $result['message']
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
