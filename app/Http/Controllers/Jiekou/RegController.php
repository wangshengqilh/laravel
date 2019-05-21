<?php

namespace App\Http\Controllers\Jiekou;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Model\UserModel;
class RegController extends Controller
{
    public function reg(){
        return view('jiekou.reg');
    }
    public function regadd(Request $request){
        $name=$request->post('name');
        $pass1=$request->post('pass1');
        $pass2=$request->post('pass2');
        $email=$request->post('email');
        $arr=UserModel::where('nick_name',$name)->first();
        if($arr){
            return json_encode(['code'=>1,'msg'=>'已经有了']);
        }
        if($pass1!=$pass2){
            return json_encode(['code'=>1,'msg'=>'密码不一致']);
        }
        $data=[
            'nick_name'=>$name,
            'pass'=>$pass1,
            'email'=>$email
        ];
        $res=UserModel::insert($data);
        if($res){
            return json_encode(['code'=>0,'msg'=>'注册成功']);
        }else{
            return json_encode(['code'=>1,'msg'=>'注册失败']);
        }
    }
}