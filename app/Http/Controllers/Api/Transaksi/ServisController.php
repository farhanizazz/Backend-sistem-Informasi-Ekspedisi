<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Servis\ServisCollection;
use App\Http\Resources\Servis\ServisResource;
use App\Models\Transaksi\ServisModel;
use App\Helpers\Transaksi\ServisHelper;
use App\Http\Requests\ServisRequest\CreateRequest;
use App\Http\Requests\ServisRequest\UpdateRequest;

class ServisController extends Controller
{
    //
    // private $servisHelper, $serviceModel;

    private $serviceModel, $servisHelper;
    public function __construct()
    {
        // $this->servisHelper = new ServisHelper();
        $this->servisHelper = new ServisHelper();
        $this->serviceModel = new ServisModel();

    }

    public function index(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => new ServisCollection($this->serviceModel->with(['notabeli','armada'])->get())
        ]);

    }
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
                $service->notaBeli() ->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil dihapus'
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
            $result =  $this->serviceModel->with('notabeli')-> findOrFail($id);
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




}
