<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest\CreateRequest;
use App\Http\Requests\UserRequest\CreateUserFromAdminRequest;
use App\Http\Requests\UserRequest\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userModel;

    /**
     * UserController constructor.
     * Initializes the $userModel field with an instance of the User model.
     */
    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * @OA\Post(
     *  path="/register",
     *  summary="Register new user",
     *  tags={"Auth"},
     *  @OA\RequestBody(
     *      required=true,
     *      description="Data that will be used to register new user",
     *      @OA\JsonContent(
     *          required={"username", "email", "name", "password"},
     *          @OA\Property(property="username", type="string", format="text", example="user123"),
     *          @OA\Property(property="email", type="string", format="email", example="user@gmail.com"),
     *          @OA\Property(property="name", type="string", format="text", example="User 123"),
     *          @OA\Property(property="password", type="string", format="text", example="password123")
     *      )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Success register new user"
     * )
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

        $payload = $request->only('username', 'email', 'name', 'password');
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

    public function create(CreateUserFromAdminRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }

        $payload = $request->only('username', 'email', 'name', 'm_role_id', 'password');
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

    public function destroy($id)
    {
        try {
            //code...
            $response = $this->userModel->findOrFail($id)->delete();
            if ($response) {
                return response()->json(
                    [
                        'status' => 'success',
                        'message' => 'User berhasil dihapus'
                    ]
                );
            }
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'User gagal dihapus'
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
     * Retrieve all users.
     *
     * @return array JSON response with either a success status, success message, and the retrieved user data, or an error status, error message, and the response from the `all` method of the `User` model.
     */
    public function index()
    {
        $response = $this->userModel->all();
        if ($response) {
            return [
                'status' => 'success',
                'message' => 'User berhasil didapatkan',
                'data' => $response
            ];
        }
        return [
            'status' => 'error',
            'message' => 'User gagal didapatkan',
            'data' => $response
        ];
    }


    public function show($id)
    {
        try {
            //code...
            return response()->json(
                [
                    'status' => 'success',
                    'data' => $this->userModel->with(['role'])->findOrFail($id)
                ]
            );
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }

    public function update(UpdateRequest $request, $id)
    {
        try {
            if (isset($request->validator) && $request->validator->fails()) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => $request->validator->errors()
                    ]
                );
            }

            $response = $this->userModel->findOrFail($id)->update($request->all());
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
}
