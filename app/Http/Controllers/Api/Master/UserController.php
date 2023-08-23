<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest\CreateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = new User();
    }

    public function store(CreateRequest $request){
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }

        $payload = $request->only('username', 'email','name', 'password');
        $response = $this->userModel->create($payload);
        if ($response) {
            return [
                'status' => 'success',
                'message' => 'User berhasil ditambahkan',
                'data' => $response
            ];
        }
        return [
            'status' => 'error',
            'message' => 'User gagal ditambahkan',
            'data' => $response
        ];
        
    }
}
