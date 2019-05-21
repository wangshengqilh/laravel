<?php

namespace App\Http\Controllers\License;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Model\LicenseModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class RegController extends Controller
{
    public function reg(){
        return view('license.reg');
    }
    public function regadd(Request $request){
        $name=$request->input('name');
        $pass1=$request->input('pass1');
        $pass2=$request->input('pass2');
        $type=$request->input('type');
        $home=$request->input('home');
        $user=$request->input('user');
        $num=$request->input('num');
        $addtime=time();
        $endtime=$request->input('endtime');
        $scope=$request->input('scope');
//        $imgs=$request->input('imgs');
        $arr=LicenseModel::where('name',$name)->first();
        if($arr){
            return json_encode(['code'=>1,'msg'=>'已经有了']);
        }
        if($pass1!=$pass2){
            return json_encode(['code'=>1,'msg'=>'密码不一致']);
        }
        $data=[
            'name'=>$name,
            'pass'=>$pass1,
            'type'=>$type,
            'home'=>$home,
            'user'=>$user,
            'num'=>$num,
            'addtime'=>$addtime,
            'endtime'=>$endtime,
            'scope'=>$scope
//            'img'=>$imgs
        ];
        $res=LicenseModel::insert($data);
        if($res){
            return json_encode(['code'=>0,'msg'=>'注册成功']);
        }else{
            return json_encode(['code'=>1,'msg'=>'注册失败']);
        }
    }
//    public function img(Request $request){
//        if($request->isMethod('POST')){
//            $fileCharater=$request->file('img');               // 使用request 创建文件上传对象
//            if($fileCharater->isValid()){                            //是否有效
//                $ext=$fileCharater->getClientOriginalExtension();   //获取图片的后缀
//                $path=$fileCharater->getRealPath();               //获取临时文件的路径
//                $filename=date('Ymdhis').'.'.$ext;
//                Storage::disk('public')->put($filename,file_get_contents($path));
//                $file_path="./public/".$filename;
//            }
//        }
//        return json_encode($file_path);
//    }
    public function login(){
        return view('license.login');
    }
    public function logins(Request $request){
        $name=$request->input('name');
        $pass=$request->input('pass');
        $arr=LicenseModel::where('name',$name)->first();
        if($arr){
            if($pass==$arr['pass']){
                return json_encode(['code'=>0,'msg'=>'登录成功']);
            }else{
                return json_encode(['code'=>1,'msg'=>'密码不对']);
            }
        }else{
            return json_encode(['code'=>1,'msg'=>'没有此用户']);
        }
    }
    public function center(){
        return view('license.center');
    }
    public function token(){
        $appid=$_GET['appid'];
        $key=$_GET['key'];
        if(empty($appid)){
            return json_encode(['code'=>1,'msg'=>'appid不能为空'],256);
        }
        if(empty($key)){
            return json_encode(['code'=>1,'msg'=>'key不能为空'],256);
        }
        $arr=LicenseModel::where(['appid'=>$appid,'key'=>$key])->first();
        if($arr){
            $key="access_toekn";
            $token=Str::random(15);
            Redis::set($key,$token);
            Redis::expire($key,7200);
            $access_token=Redis::get($key);
            return json_encode(['code'=>0,'msg'=>'生成成功','token'=>$token],256);
        }else{
            return json_encode(['code'=>1,'msg'=>'数据不合法'],256);
        }
    }
    public function ip(){
        $token=$_GET['token'];
        if(empty($token)){
            return json_encode(['code'=>1,'msg'=>'token不能为空'],256);
        }
        $arr=Redis::get("access_toekn");
        if($arr==$token){
            $key="ip";
            $ip=$_SERVER['SERVER_ADDR'];
            Redis::set($key,$ip);
            Redis::expire($key,7200);
            $access_token=Redis::get($key);
            return json_encode(['code'=>0,'msg'=>'查询成功','ip'=>$ip],256);
        }else{
            return json_encode(['code'=>1,'msg'=>'数据不合法'],256);
        }
    }
    public function useragent(){
        $token=$_GET['token'];
        if(empty($token)){
            return json_encode(['code'=>1,'msg'=>'token不能为空'],256);
        }
        $arr=Redis::get("access_toekn");
        if($arr==$token){
            $key="useragent";
            $useragent=$_SERVER['HTTP_USER_AGENT'];
            Redis::set($key,$useragent);
            Redis::expire($key,7200);
            $access_token=Redis::get($key);
            return json_encode(['code'=>0,'msg'=>'查询成功','useragent'=>$useragent],256);
        }else{
            return json_encode(['code'=>1,'msg'=>'数据不合法'],256);
        }
    }
    public function usershow(){
        $token=$_GET['token'];
        $appid=$_GET['appid'];
        if(empty($token)){
            return json_encode(['code'=>1,'msg'=>'token不能为空'],256);
        }
        if(empty($appid)){
            return json_encode(['code'=>1,'msg'=>'appid不能为空'],256);
        }
        $arr=Redis::get("access_toekn");
        if($arr==$token){
            $data=LicenseModel::where(['appid'=>$appid])->first();
            if($data){
                return json_encode(['code'=>0,'msg'=>'查询成功','usershow'=>$data->toArray()],256);
            }else{
                return json_encode(['code'=>1,'msg'=>'查询失败'],256);
            }
        }else{
            return json_encode(['code'=>1,'msg'=>'数据不合法'],256);
        }
    }
}