<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Http\Traits\GlobalTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth as FacadesJWTAuth;

class AuthController extends Controller
{
    use GlobalTrait;
        /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    private $userModel;
    public function __construct()
    {
        $this->userModel = new User();
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * summary="Login user",
     * tags={"Auth"},
     * @OA\RequestBody(
     *  required=true,
     *  @OA\MediaType(
     *    mediaType="application/json",
     *    @OA\Schema
     *      (
     *          type="object",
     *          required={"username", "password"},
     *          @OA\Property(
     *              property="username",
     *              type="string",
     *              example="boss"
     *          ),
     *          @OA\Property(
     *              property="password",
     *              type="string",
     *              example="Abc12345"
     *          ),
     *      )
     *  )
     * ),
     * @OA\Response(
     *  response=200,
     *  description="User berhasil login"
     * ),
     * @OA\Response(
     *  response=401,
     *  description="Username dan Password anda salah"
     * )
     * )
     */
    public function login(LoginRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }

        $credentials = $request->only('username', 'password');

        if ($token = FacadesJWTAuth::attempt($credentials)) {
            // dd(JWTAuth::attempt($credentials));
            $user =$this->guard()->user();
            return response()->json(['status' => 'success', 'data' => ($this->respondWithToken($token, $user))->original], 200);
        }

        return response()->json(['status' => 'error', 'message' => 'Username dan Password anda salah'], 401);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        try {
            $user = auth()->user();
            return response()->json(['status'=> 'success', 'data'=>$user]);
            // return response()->json(['status'=> 'successs', 'data'=>($)])
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'message' => $th->getMessage()], 404);
        }
    }


    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['status' => 'success','message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        try {
            return $this->respondWithToken(FacadesJWTAuth::refresh(), auth()->user());
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['status' => 'error', 'message' => 'Token is invalid'], 401);
        }
    }
    

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>auth('api')->factory()->getTTL() * 60,
            'user' => new UserResource($user)
        ]);
    }

        /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
