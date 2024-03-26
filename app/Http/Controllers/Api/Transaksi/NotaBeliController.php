<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi\NotaBeliModel;
use App\Http\Requests\NotaBeliRequest\CreateRequest;
use App\Http\Requests\NotaBeliRequest\UpdateRequest;
class NotaBeliController extends Controller
{
    //
    private $notaBeliModel;

    public function __construct()
    {
        $this->notaBeliModel = new NotaBeliModel();
    }

    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->notaBeliModel->all()
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
        $this->notaBeliModel->create($request->all());

        return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil ditambahkan'
            ]
        );
    }

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

        $response = $this->notaBeliModel->findOrFail($id)->update($request->all());
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

    public function destroy($id)
    {
        try{
            $this->notaBeliModel->find($id)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        }catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function show($id)
    {
        try {
            $this -> notaBeliModel -> findOrFail($id);
            return response() -> json([
                'status' => 'success',
                'data' => $this -> notaBeliModel -> find($id)
            ]);

        } catch (\Exception $e) {
            return response() -> json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

}
