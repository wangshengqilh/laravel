<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Redis;

use Closure;


class fangshua
{
    public function fs($request, Closure $next){

        $key='minutes:ip:'.$_SERVER['REMOTE_ADDR'].':token:'.$request->input('token');
        $num=Redis::get($key);
        if($num>10){
            die("超过次数");
        }
        Redis::incr($key);
        Redis::expire($key,60);
        return $next($request);
    }
}