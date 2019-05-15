<?php
namespace App\Http\Controllers\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class LoginController extends Controller{
    public function token(){
        $redis=new \Redis();
        $redis->connect('127.0.0.1',6379);
        $keys="token_lists";
        $storage=$redis->scard($keys);
        $num=100-$storage;
        for($i=0;$i<$num;$i++){
            $token=rand(100,10000).time();
            $tokens=md5($token);
            $start=rand(0,10);
            $end=rand(11,32);
            $arr=substr($tokens,$start,$end);
            $redis->sadd($keys,$arr);
        }
    }
    public function adds(){
        $redis=new \Redis();
        $redis->connect('127.0.0.1',6379);
        $token=$redis->spop('token_lists');
        return view('test.codes',['token_lists'=>$token]);
    }
    public function cun(Request $request){
        $id=$request->input('uid');
        $token=$request->input('token');
        $redis=new \Redis();
        $redis->connect('127.0.0.1',6379);
        $arr=$redis->set($token,$id,60);
        if($arr){
            return 1;
        }else{
            return 0;
        }
    }
    public function logins(Request $request){
        $token=$request->input('token');
        $redis=new \Redis();
        $redis->connect('127.0.0.1',6379);
        $data=$redis->get($token);
        if($data){
            return 1;
        }else{
            return 0;
        }
    }

}