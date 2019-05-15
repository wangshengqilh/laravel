<?php
namespace App\Http\Controllers\Home;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\Model\UserModel;
use Illuminate\Support\Facades\Session;
class IndexController extends Controller
{

    /**
     * 用户注册
     * 2019年3月21日14:26:56
     * xuyongxian
     */
    public function reg()
    {
        return view('home.reg');


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

    //登录
    public function index(Request $request){
        $current_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        echo $current_url;echo '</br>';
        $data = [
            'login' => $request->get('is_login'),
            'current_url' => urlencode($current_url)
        ];
        print_r($data);
        return view('home.index',$data);
    }

    public function doLogin(Request $request)
    {
        echo '<pre>';print_r($_POST);echo '</pre>';

        $emial = $request->input('u_email');
        $pass = $request->input('u_pass');

        $u = UserModel::where(['email'=>$emial])->first();

        if($u){
            if( password_verify($pass,$u->pass)){

                $token = substr(md5(time().mt_rand(1,99999)),10,10);
                setcookie('uid',$u->uid,time()+86400,'/','lening.com',false,true);
                setcookie('token',$token,time()+86400,'/user','',false,true);

                $request->session()->put('u_token',$token);
                $request->session()->put('uid',$u->uid);

                header("Refresh:3;url=/user/center");
                echo "登录成功";
            }else{
                echo "密码不正确";
                header("Refresh:2;url=/user/login");
            }
        }else{
            echo "用户不存在";
            header('Refresh:2;url=/usre/login');
        }

    }

    //个人中心
    public function center()
    {
        $data = [];
        return view('users.center',$data);
    }
}