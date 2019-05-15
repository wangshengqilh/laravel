<?php
namespace App\Http\Controllers\Test;

use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;


class JiamiController
{
    public function test(){
        $str="hello word";
        $key="pass";
        $iv=mt_rand(1111,9999)."aaaabbbbcccc";
        //加密
        $enc_str=openssl_encrypt($str,'AES-128-CBC',$key,OPENSSL_RAW_DATA,$iv);
        $str=base64_encode($enc_str);
        var_dump($enc_str);echo '</br>';
        var_dump($str);echo '</br>';
        //解密
        $dec_str=openssl_decrypt($enc_str,"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv);
        var_dump($dec_str);

    }
    //响应请求
    public function tes(){
        //接收请求
        $timestamp=$_GET['t'];
        $key="pass";
        $salt="****";
        $method="AES-128-CBC";
        $iv=substr(md5($timestamp.$salt),5,16);

        //签名
        $sign=base64_decode($_POST["sign"]);
        //var_dump($sign);
        $base64_data=$_POST['data'];
        //var_dump($base64_data);die;
        //验证签名
        $pub_res=openssl_pkey_get_public(file_get_contents("/home/wwwroot/laravel_api/public/test/public.pem"));
        //var_dump($pub_res);
        //验证签名
        $pub_sign=openssl_verify($base64_data,$sign,$pub_res,OPENSSL_ALGO_SHA256);

        var_dump($pub_sign);
        if(!$pub_sign){
            echo "fail";
        }
        //接收加密数据
        $post_data=base64_decode($_POST['data']);
        //var_dump($post_data);
        //解密
        $dec_str=openssl_decrypt($post_data,$method,$key,OPENSSL_RAW_DATA,$iv);
        //var_dump($dec_str);
        //解密之后响应给客户端
        if(1){
            $now_time =time();
            $response=[
                "error"=>0,
                "msk"=>"ok",
                "data"=>"test"
            ];
            $iv2=substr(md5($now_time.$salt),5,16);
            $dec_data=json_encode($response);
            //加密响应数据
            $de_str=openssl_encrypt($dec_data,$method,$key,OPENSSL_RAW_DATA,$iv2);
            //var_dump($de_str);
            $arr=[
                "t"=>$now_time,
                "data"=>base64_encode($de_str),
            ];
            echo json_encode($arr);

        }

    }
    public function aaa(){
        //    echo PHP_INT_SIZE;echo '</br>';
//    echo PHP_INT_MAX;echo '</br>';
//    echo PHP_INT_MIN;echo '</br>';
        $now=time();
        $url="http://www.lumen.com/test2?t=".$now;
        //var_dump($url);
        $data=[
            "name"=>'zhangsan',
            "content"=>"test"
        ];
        //$str="hello word";
        $salt="****";
        $key='pass';
        $iv=substr(md5($now.$salt),5,16);
        $json_str=json_encode($data);
        //$iv=mt_rand(1000,9999)."aaaabbbbcccc";
        //加密
        $enc_str=openssl_encrypt($json_str,"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv);
        //var_dump($enc_str);echo '</br>';
        $post_data=base64_encode($enc_str);
        //var_dump($post_data);echo '</br>';

        //计算签名
        //生成签名
        $sig=openssl_pkey_get_private(file_get_contents("./private.key"));
        //var_dump($sign);
        openssl_sign($post_data,$signature,$sig,OPENSSL_ALGO_SHA256);
        //释放资源
        openssl_free_key($sig);
        $sign=base64_encode($signature);
        //var_dump($sign);

        //向服务器发送请求
        $ch=curl_init($url);
        //var_dump($ch);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,['data'=>$post_data,'sign'=>$sign]);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_HEADER, 0);
        //接收服务端响应
        $rs=curl_exec($ch);
        var_dump($rs);
        $response=json_decode($rs,true);

        //解密
        $dec_str=openssl_decrypt($enc_str,"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv);
//var_dump($dec_str);
    }
}
