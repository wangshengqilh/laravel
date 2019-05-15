<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Redis;

use Closure;


class encode
{
    //加密
    public function encode(Request $request){
        $str=$request->input('str');
        $key=OpensslModel::first()->toArray();
        $privatekey=$key['private'];
        $encryptData="";
        openssl_private_encrypt($str,$encryptData,$privatekey);
        $content=base64_encode($encryptData);
        echo $content;
    }
}