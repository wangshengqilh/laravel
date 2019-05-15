<?php

namespace App\Http\Middleware;

use Closure;

class checkLoginToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->session()->get('u_token')){
            echo json_encode([
                'error' => 301,
                'url'   => url('/user/login')
            ]);
            die;
        }
        return $next($request);
    }
}
