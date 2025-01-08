<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!(request()->bearerToken())){
            return response()->json([
                'error' => 'Token is required',
                'status' => false
            ], 400);
         }
         
        try {
            if (!JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'error' => 'Un-Authorized',
                    'status' => false
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Invalid token',
                'status' => false
            ], 400);
        }

        return $next($request);
    }
}