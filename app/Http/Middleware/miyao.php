<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Redis;
use think\Request;

class miyao
{

    private $_api_data = [];
    private $_blank_key = 'blank_list';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){

        //先获取接口的数据  需要先解密
        #$this -> _decrypt($request);

        //先获取接口的数据  非对称私钥解密
        $this -> _RsaDecrypt($request);

        //访问次数限制
        $str = $this -> _checkApiAccessCount($request);

        if($str['status'] != 1000){
            return response($str);
        }

        //服务端验签方法
        $data = $this -> _checkClientSign($request);

        //把解密数据传递到控制器
        $request -> request -> replace( (array) $this -> _api_data);

        //判断签名是否正确
        if($data['status'] == 1000){

            $response = $next($request);

            #后置操作     对返回的数据进行加密
            $api_response = [];

            #使用对称加密对数据进行加密处理
            #$api_response['data'] = $this -> _encrypt($response -> original);
            $api_response['data'] = $this -> _RsaEncrypt($response -> original);

            #生成签名，返回给客户端
            $api_response['sign'] = $this -> _createServerSign($response -> original);

            return response($api_response);

        }else{
            return response($data);
        }
    }

    //服务端返回的签名
    private function _createServerSign($data){

        $app_id = $this -> _getAppId();

        $all_app = $this -> _getAllAppIdKey();

        if( ! is_array($data)){
            $data = (array) $data;
        }

        #排序
        ksort($data);

        if(!$app_id){
            return [];
        }

        #拼接
        $sign_str = http_build_query($data).'&app_key='.$all_app[$app_id];

        return md5($sign_str);

    }

    //服务端返回加密数据
    private function _encrypt($data){

        //数据不为空界面
        if(!empty($data)){
            $encrypt_data = openssl_encrypt(
                json_encode($data),
                'AES-256-CBC',
                'zhangjian',false,'0123456701234567'
            );

            return $encrypt_data;
        }

    }

    //使用对称加密方式进行解密
    private function _decrypt($request){
        $data = $request -> post('data');

        //数据不为空界面
        if(!empty($data)){
            $decrypt_data = openssl_decrypt($data,
                'AES-256-CBC',
                'zhangjian',false,'0123456701234567'
            );

            $this -> _api_data = json_decode($decrypt_data , true );
        }

    }

    //使用非对称方式私钥加密
    private function _RsaEncrypt($data){
        $i = 0;
        $all_encrypt = '';
        $str = json_encode($data);
        while($sub_str = substr($str , $i , 117)){
            openssl_private_encrypt(
                $sub_str,
                $encrypt,
                file_get_contents(public_path().'/private.key')
            );
            $all_encrypt .= base64_encode($encrypt);
            $i += 117;
        }
        return $all_encrypt;

    }

    //使用非对称加密方式私钥解密
    private function _RsaDecrypt($request){
        //非对称解密
        $i = 0;
        $all_decrypt = '';
        $decrypt = '';
        while($sub_str = substr($request -> post('data') , $i , 172)){
            $decode_data = base64_decode($sub_str);
            openssl_private_decrypt(
                $decode_data,
                $decrypt,
                file_get_contents(public_path().'/private.key')
            );
            $all_decrypt .= $decrypt;
            $i += 172;
        }

        $this -> _api_data = json_decode($all_decrypt , true );

        if(!empty($request -> query())){
            $this -> _api_data = array_merge($request -> query() , (array) $this -> _api_data);
        }
        return $this -> _api_data;
    }

    //服务端验签的方法
    private function _checkClientSign($request){

        if(!empty($this -> _api_data)){

            //获取当前所有的app_id和key
            $map = $this -> _getAllAppIdKey();
            $app_id = $this -> _getAppId();
            if(!$app_id){
                return [];
            }
            if(!array_key_exists($this -> _api_data['app_id'] , $map)){
                return [
                    'status' => 1,
                    'msg' => 'check sign fail',
                    'data' => []
                ];
            }
            //生成服务端签名
            ksort($this -> _api_data);



            //拼接app_key
            $server_str = http_build_query($this -> _api_data).
                '&app_key='.$map[$app_id];



            if(md5($server_str) != $request['sign']){
                return [
                    'status' => 2,
                    'msg' => 'check sign fail2',
                    'data' => []
                ];
            }
            return ['status' => 1000];
        }
    }

    //获取当前所有的app_id和key
    private function _getAllAppIdKey(){
        //从数据库获取对应的数据
        return [md5(1) => md5('123456')];
    }

    //接口防刷
    private function _checkApiAccessCount($request){
        //现获取app_id
        $app_id = $this -> _getAppId();

        $blank_key = $this -> _blank_key;

        //判断是否在黑名单中
        $join_blank_time = Redis::zScore($blank_key,$app_id);

        if(empty($join_blank_time)){
            //记录app_id对应的访问次数
            $this -> _addAppIdAccessCount();
            return ['status' => 1000];

        }else{

            #判断是否超过30分钟
            if(time() - $join_blank_time >= 30){
                Redis::zRemove($blank_key , $app_id);
                $this -> _addAppIdAccessCount();
            }else{
                return [
                    'status' => 3,
                    'msg' => '暂时不能访问接口，时间未到，请稍后再试',
                    'data' => []
                ];
            }
        }
    }

    //获取当前调用接口的app_id
    private function _getAppId(){

//        return $this -> _api_data['app_id'];

        if(empty($this -> _api_data['app_id'])){

            return '';
        }else{

            return $this -> _api_data['app_id'];
        }

    }

    //记录app_id对应的访问次数
    private function _addAppIdAccessCount(){

        $count = Redis::incr($this -> _getAppId());

        if($count == 1){
            Redis::Expire($this -> _getAppId() , 60);

        }
        #访问次数>=100
        if($count >= 100){
            Redis::zAdd($this ->_blank_key , time() ,$this -> _getAppId());
            Redis::del($this -> _getAppId());
            return [
                'status' => 3,
                'msg' => '暂时不能访问接口，时间未到，请稍后再试',
                'data' => []
            ];
        }

    }



























    /**
     * 生成服务端的签名
     * @param $data
     * @param $app_id
     * @return string
     */
    /*public function _createServerSign($data,$app_id){

        $api_key_arr = [
            md5('1805') => md5('1805api'),
            md5('1807') => md5('1807api'),
        ];

        #ksort字典排序
        ksort($data);

        #变成字符串
        $sign_str = http_build_query($data );

        $sign_str .= '&app_key=' . $api_key_arr[$app_id];

        $sign = md5($sign_str);

        return $sign;

    }*/

    /**
     * 加密数据
     * @param $data
     * @return string
     */
    /*private function encrypt($data){
        $conf = [
            'cipher' => 'AES-256-CBC',
            'key' => '1805test',
            'iv' => '3B65571F4EB0F92E'
        ];

        if(is_array($data)){
            $data = json_encode($data);
        }

        return openssl_decrypt($data , $conf['cipher'],$conf['key'],0,$conf['iv']);
    }*/

    /**
     * 私钥分段解密
     * @param $data
     */
    /*public function openssldecrypt($data){
        $all_encrypt = '';
        $i=0;
        while($sub_str = substr( $data , ($i*171) , 171)){
            openssl_private_decrypt(
                base64_decode($sub_str),
                $decrypt,
                file_get_contents(public_path().'/private.key')
            );
            $all_encrypt .= $decrypt;
            $i ++;
        }
        return json_decode($all_encrypt,true);
    }*/

}