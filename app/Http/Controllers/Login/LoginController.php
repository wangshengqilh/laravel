<?php

namespace App\Http\Controllers\Login;

use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    //
    public function login()
    {
        return view('login.login');
    }

    public function logininfo(Request $request)
    {

        $account=$request->input('account');
        $pwd=$request->input('pwd');
        if (empty($account) || empty($pwd)){
            die('账号或密码不能为空');
        }
        $data=UserModel::where(['u_name'=>$account])->first();
        if ($account!=$data['u_name']){
            die('账号不存在或密码有误1');
        }else if (password_verify($pwd,$data['u_pwd'])==false){
            die('账号不存在或密码有误2');
        }else{
            $token = substr(md5(time().mt_rand(1,99999)),10,10);
            setcookie('uid',$data['u_id'],time()+86400,'/','mengli2426.club',false,true);
            setcookie('token',$token,time()+86400,'/','mengli2426.club',false,true);

            $request->session()->put('u_token',$token);
            $request->session()->put('uid',$data->u_id);
            $key='str:u:token:'.$data->u_id;
            Redis::del($key);
            Redis::hset($key,'web',$token);
            header("Refresh:3;url=/root");
            echo "登录成功";
        }

    }

    public function center(Request $request)
    {

        if($_COOKIE['token'] != $request->session()->get('u_token')){
            die("非法请求");
        }else{
            echo '正常请求';
        }
        echo 'u_token: '.$request->session()->get('u_token'); echo '</br>';
        echo '<pre/>';print_r($_COOKIE);echo '</pre>';

        if(empty($_COOKIE['uid'])){
            header('Refresh:2;url=login/user');
            echo '请先登录';
            exit;
        }else{
            echo 'UID: '.$_COOKIE['uid'] . ' 欢迎回来';
        }
    }

    public function test(Request $request){
        $user=$request->post('username');
        $pwd=$request->post('password');

        if (!empty($user && $pwd)){
            $response=[
                'error'=>0,
                'msg' => 'OK'
            ];
        }else{
            $response=[
                'error'=>500,
                'msg' => 'NO'
            ];
        }
        return json_encode($response);
    }
}
