<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Redis;

use Closure;


class checklogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token=$request->input('token');
        $uid=$request->input('uid');
        if(empty($token) || empty($uid)){
            $arr=[
                'error'=>6,
                'msg'=>'错了'
            ];
            die(json_encode($arr,JSON_UNESCAPED_UNICODE));
        }
        $key='login_tokens:uid:'.$request->input('uid');
        $kes=Redis::get($key);
        if($kes){
            if($kes==$token){

            }else{
                $arr=[
                    'error'=>7,
                    'msg'=>'token过期'
                ];
                die(json_encode($arr,JSON_UNESCAPED_UNICODE));
            }
        }else{
            $arr=[
                'error'=>8,
                'msg'=>'请先登录'
            ];
            die(json_encode($arr,JSON_UNESCAPED_UNICODE));
        }
        return $next($request);
    }
}