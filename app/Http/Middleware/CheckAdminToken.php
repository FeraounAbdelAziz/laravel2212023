<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckAdminToken
{

    public function handle($request, Closure $next)
    {

    $user = null;
    try {
        $user = JWTAuth::parseToken()->authenticate();
            //throw an exception

    } catch (\Exception $e) {
        if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
            return response() -> json(['success' => false, 'msg' => 'INVALID _TOKEN']);
        }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
            return response() -> json(['success' =>false, 'msg'=>'EXPIRED_TOKEN']);
        } else{
            return response() -> json(['success' => false, 'msg' => 'Error']);
        }
    }
    if(!$user){
        return response()->json([
            'success' => false,
            'msg' => trans('Unauthenticated')
        ],200);
    }
    return $next($request);
    }
}
