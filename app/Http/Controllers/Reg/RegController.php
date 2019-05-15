<?php

namespace App\Http\Controllers\Reg;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\Model\UserModel;
use Illuminate\Support\Facades\Session;

class RegController extends Controller
{
    public function text(){
        echo 111;
    }
    public function reg()
    {
        return view('users.reg');
    }

    public function doReg(Request $request)
    {

        $nick_name = $request->input('nick_name');

        $u = UserModel::where(['nick_name'=>$nick_name])->first();
        if($u){
            die("用户名已存在");
        }

        $pass1 = $request->input('u_pass');
        $pass2 = $request->input('u_pass2');


        if($pass1 !== $pass2){
            die("密码不一致");
        }

        $pass = password_hash($pass1,PASSWORD_BCRYPT);

        $data = [
            'nick_name'  => $request->input('nick_name'),
            'age'  => $request->input('age'),
            'email'  => $request->input('u_email'),
            'reg_time'  => time(),
            'pass'  => $pass
        ];

        $uid = UserModel::insertGetId($data);

        if($uid){
            setcookie('uid',$uid,time()+86400,'/','lening.com',false,true);
            header("Refresh:3;url=/user/center");
            echo '注册成功,正在跳转';
        }else{
            echo '注册失败';
        }
    }
}