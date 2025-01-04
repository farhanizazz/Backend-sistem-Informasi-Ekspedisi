<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $payload = $request->all();
        $method = $payload['method'] ?? null;
        $path = $payload['path'] ?? null;
        
        $data = \App\Models\LogModel::when($path, function($query) use ($path){
                    return $query->where('path','LIKE', '%'.$path.'%');
                })
                ->when($request['method'], function($query) use ($request){
                    return $query->where('method','LIKE', '%'.$request['method'].'%');
                })
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
