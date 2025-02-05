<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Logger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user_id = auth()->user()->id ?? null;
        $response = $next($request);

        if($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')){
            $response_data = $response->getOriginalContent();
            if(!isset($response_data['status'])) {
                return $response;
            }
            if($response_data['status'] == 'success'){
                $data = $request->all();
                $user_id = $user_id ?? (auth()->user()->id ?? null);

                // \App\Models\LogModel::create([
                //     'user_id' => $user_id,
                //     'path' => $request->path(),
                //     'method' => $request->method(),
                //     'request' => json_encode($data),
                //     'response' => json_encode($response_data['data'] ?? []),	
                // ]);
            }

        }


        return $response;
    }
}
