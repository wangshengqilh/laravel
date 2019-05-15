<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\OpensslModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use App\Model\UserModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
class UserController extends Controller
{
    //

	public function user($uid)
	{
		echo $uid;
	}

	public function test()
    {
        echo '<pre>';print_r($_GET);echo '</pre>';
    }

	public function add()
	{
		$data = [
			'name'      => str_random(5),
			'age'       => mt_rand(20,99),
			'email'     => str_random(6) . '@gmail.com',
			'reg_time'  => time()
		];

		$id = UserModel::insertGetId($data);
		var_dump($id);
	}


    /**
     * 用户注册
     * 2019年1月3日14:26:56
     * liwei
     */
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

	/**
	 * 用户登录
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function login()
	{
		$data = [];
		return view('users.login',$data);
	}

    public function doLogin(Request $request)
    {
//    	echo '<pre>';print_r($_POST);echo '</pre>';
//        $arr = $request->input();
    	$emial = $request->input('u_email');
    	$pass = $request->input('u_pass');
        $key=OpensslModel::first()->toArray();
//        $privatekey=$key['private'];
//        $encryptData=$arr;
//        openssl_private_encrypt($emial,$encryptData,$privatekey);
//        openssl_private_encrypt($pass,$encryptData,$privatekey);
//        $content=base64_encode($encryptData,$encryptData,$privatekey);
//        $redis = 'ass:'.$pass. ':' . $content;
//        $num = Redis::incr($redis);
//        echo $content;
    	$u = UserModel::where(['email'=>$emial])->first();

    	if($u){
    	    if( password_verify($pass,$u->pass) ){
    	        $token = substr(md5(time().mt_rand(1,99999)),10,10);
                $redis_key = 'data:'. ':' . $token;
                $num = Redis::incr($redis_key);
    	        setcookie('uid',$u->uid,time()+5,'/','lening.com',false,true);
    	        setcookie('token',$token,time()+5,'/user','',false,true);
    	        $request->session()->put('u_token',$token);
    	        $request->session()->put('uid',$u->uid);
//                return view('users.center');
    	        header("Refresh:3;url=/user/center");
                echo "登录成功";
            }else{
    	        echo "密码不正确";
		        header("Refresh:2;url=/user/login");
//                return view('users.login');
                $uip = $_SERVER['SERVER_ADDR'];
//                dump($uip);
//                setcookie('uip',$uip,time()+60,'/user','',false,true);
                $redis_keys = 'arr:'. ':' . $uip;
                $num = Redis::incr($redis_keys);
                dump($num);
            }
        }else{
    	    echo "用户不存在";
	        header("Refresh:2;url=/user/login");
//            return view('users.login');
	        $uip=$_SERVER['SERVER_ADDR'];
            $redis_keys = 'arr:'. ':' . $uip;
            $num = Redis::incr($redis_keys);
            dump($num);
        }
    }


    /**
     * 个人中心
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function center()
    {
        $data = [];
        return view('users.center',$data);
	echo '您的账号已在别处登录，您被迫下线';
	header("Refresh:2;url=/user/login");
    }
    public function logou()
    {
        Redis::flushall();
        cookie('token','');
        return view('users.logou');
    }
    public function she(Request $request)
    {
        $jk = $request->input('jk');
        $redis_key1 = 'arr1:' . $jk;
        $a = Redis::incr($redis_key1);
        $num = $request->input('num');
        $redis_key2 = 'arr2:' . $num;
        $b = Redis::incr($redis_key2);
        $time = $request->input('time');
        $redis_key3 = 'arr3:' . $time;
        $c = Redis::incr($redis_key3);
    }
    public function guo()
    {
        return view('users.guo');
    }
}
