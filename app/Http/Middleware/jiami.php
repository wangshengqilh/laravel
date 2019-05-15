<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class jiami
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    private $api_data=[];

    private $_blank_key='blank_list';

    public function handle($request, Closure $next)
    {
        # 非对称解密
        $this->_rsaDecrypt($request);

        # 访问次数限制
        $data = $this->_checkApiCount();

        if( $data['status']!=1000){
            return response($data);
        }

        # 验证签名是否正确
        $sign = $this->_CheckClientSing($request);
        if($sign['status']!=1000){
            return response($this->_CheckClientSing($request));
        }

        # 把解密数据传递到控制器

        $request->request->replace( (array) $this->api_data);


        if($sign['status']==1000){
            $response = $next($request);


            # 后置操作对 返回的数据加密
            $api_response = [];
            # 使用对称加密 对数据进行加密

            $api_response['data'] = $this->_rsaEncrypt($response->original);

            # 生成签名
            $api_response['sign'] = $this->_createServerSign($response->original);

            return response($api_response);
        }


    }

    /*
     * 对称解密
     */
    private function _decrypt($request){

        if(!empty($request->post('data'))){


            $decrypt_data = openssl_decrypt($request->post('data'),'AES-256-CBC','login',false,'Zh-2742002000207');
            $this->api_data = json_decode($decrypt_data,true);


        }
    }
    /*
     * 非对称解密
     * openssl_private_decrypt
     */
    private function _rsaDecrypt($request){

        $i=0;
        $all='';
        while ($sub_str = substr($request->post('data'),$i,172)){
            $decude_data = base64_decode($sub_str);
            openssl_private_decrypt(
                $decude_data,
                $decrype_data,
                file_get_contents('key/private.key'),
                OPENSSL_PKCS1_PADDING
            );
            $all .=$decrype_data;
            $i+=172;
        }
//        var_dump($all);exit;
        $this->api_data = json_decode($all,true);

    }
    /*
     * 对称加密
     */
    private function _encrypt($data){
        # 数据对称加密
        $encrypt = openssl_encrypt(
            json_encode($data),
            'AES-256-CBC',
            'login',
            false,
            'Zh-2742002000207'
        );

        return $encrypt;
    }

    /*
     * 非对称加密
     */
    private function _rsaEncrypt($data){

        $i=0;
        $all='';
        $josn_str = json_encode($data);
        while ($sub_str = substr($josn_str,$i,117)){
            openssl_private_encrypt(
                $sub_str,
                $encrype_data,
                file_get_contents('key/private.key'),
                OPENSSL_PKCS1_PADDING
            );
            $all .=base64_encode($encrype_data);
            $i+=117;
        }
        return $all;
    }
    /*
     * 生成签名
     */
    private function _createServerSign($data){

        # 根据键名 字典排序
        if(!is_array($data)){
            $data = (array)$data;
        }
        ksort($data);
        # 拼接 成url格式字符串
        return md5(
            http_build_query($data).
            '&app_key='.
            $this->_getAppIdKey()[$this->_getAppid()]
        );
    }
    /*
     * 验证签名
     */
    private function _CheckClientSing($request){

        # 获取当前所有 app_id app_key
        $map = $this->_getAppIdKey();

        # 生成服务端签名
        $data = $this->api_data;

        if(!is_array($data)){
            $data = (array)$data;
        }

        ksort($data);

        # 转换为url格式字符串
        $server_str = http_build_query($data);
        if(!$map){
            return;
        }
        #拼接app_key
        $server_str = $server_str.'&app_key='.$map[$this->api_data['app_id']];
        if(md5($server_str)!=$request['sign']){
            return [
                'status'=>2,
                'msg'=>'check sign fail',
                'data'=>[]
            ];
        }else{
            return [
                'status'=>1000,
                'msg'=>'check sign ok',
                'data'=>[]
            ];
        }

    }

    /*
     * 记录访问次数 黑名单
     */
    private function _addApiCount(){

        $count = Redis::incr($this->_getAppid());

        if($count==1){

            Redis::Expire($this->_getAppid(),60);

        }
        # 如果大于等于 100 拉入黑名单 清空访问次数
        if($count>=10){
            Redis::Zadd($this->_blank_key,time(),$this->_getAppid());

            Redis::del($this->_getAppid());

            return [
                'status'=>2,
                'msg'=>json_encode('接口调用频率，请一分钟后重试',JSON_UNESCAPED_UNICODE),
                'data'=>[]
            ];
        }



    }
    /*
     * 接口防刷
     */
    private function _checkApiCount(){
        # 获取 访问app_id
        $app_id = $this->_getAppid();

        #查看 访问app_id 是否在黑名单
        $blank_list = Redis::zScore($this->_blank_key,$app_id);
        # 不存在黑名单 访问次数+1 继续访问
        if(empty($blank_list)){
            $this->_addApiCount();
            return [
                'status'=>1000,
            ];
        }else{
            #判断是否超过30分钟 超过则移除黑名单
            if(time() - $blank_list >= 10){
                Redis::zRemove($this->_blank_key,$app_id);
                $this->_addApiCount();
            }else{
                return [
                    'status'=>3,
                    'msg'=>'接口访问过于频繁，请一分钟后重试',
                    'data'=>[]
                ];
            }
        }
    }
    /*
     * 获取 app_id app_key
     */
    private function _getAppIdKey(){
        //        $res = DB::table('app_user')->where('app_id','=',"'".$app_id."'")->first();
//        $data = json_decode(json_encode($res),true);
        return [

            'ba006ba7669bb3b52655be17abaae963'=>'5e90ae5f6eb12b3f3e3c86c0409de103'

        ];
    }

    /*
     * 获取 app_id
     */
    private function _getAppid(){
        return $this->api_data['app_id'];
    }
}