<?php

namespace App\Controllers\Mu;

class MuMiddleware
{
    public function handle($request, $next)
    {    	 
    	if (in_array(config('app', 'env'), ['dev', 'testing'])) {
            return $next($request, $response);
        }
        $key = self::getMuKeyFromReq($request);
        if ($key == null) {
            $res['ret'] = 0;
            $res['msg'] = "key is null";
            return response()->json($res, 401);            
        }
        if ($key != config('mu', 'muKey')) {
            $res['ret'] = 0;
            $res['msg'] = "token is  invalid";
            return response()->json($res, 401);   
        }
        return $next($request);    
    }
    
    public static function getMuKeyFromReq($request)
    {
    	if ($request->input('Key')) {
    		return $request->input('Key');
    	}
    	if ($request->input('Token')) {
    		return $request->input('Token');
    	}
    	if ($request->input('key')) {
    		return $request->input('key');
    	}
    	if ($request->input('token')) {
    		return $request->input('token');
    	}
    	return null;
    }
}