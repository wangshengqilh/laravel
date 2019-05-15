<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CeController extends Controller
{
    /**
     * curl接口
     */
    public function curl(){
        $url="http://wsq1.96myshop.cn/register";
        $data=[
            'u_name'=>'wsq',
            'u_pwd'=>'wsq123'
        ];
//        $post_str="u_name=wsq&u_pwd=wsq123";
        $arr=json_encode($data);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);

        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);

//       curl_setopt($ch,CURLOPT_POSTFIELDS,$post_str);
        //json格式
//        curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
//        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type:text/plain']);

        $data=curl_exec($ch);
        echo $data;
    }
}
