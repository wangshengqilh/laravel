<?php
namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
class UsersController extends Controller
{
    public function reg(Request $request){
        $pass1=$request->input('pass1');
        $pass2=$request->input('pass2');
        if($pass1!=$pass2){
            $arr=[
                'error'=>1,
                'msg'=>'密码不一致'
            ];
            die(json_encode($arr,JSON_UNESCAPED_UNICODE));
        }
        $email=$request->input('email');
        $emails=UserModel::where(['email'=>$email])->first();
        if($emails){
            $arr=[
                'error'=>2,
                'msg'=>'邮箱已经有了'
            ];
            die(json_encode($arr,JSON_UNESCAPED_UNICODE));
        }
        $name=$request->input('name');
        $username=UserModel::where(['nick_name'=>$name])->first();
        if($username){
            $arr=[
                'error'=>3,
                'msg'=>'用户名已经有了'
            ];
            die(json_encode($arr,JSON_UNESCAPED_UNICODE));
        }
        $pass=password_hash($pass1,PASSWORD_BCRYPT);
        $data=[
            'nick_name'=>$request->input('name'),
            'age'=>$request->input('age'),
            'email'=>$email,
            'reg_time'=>time(),
            'pass'=>$pass,
        ];
        $res=UserModel::insert($data);
        if($res){
            $arr=[
                'success'=>0,
                'msg'=>'添加成功',
            ];
        }else{
            $arr=[
                'error'=>4,
                'msg'=>'添加失败'
            ];
        }
        die(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
    public function login(Request $request){
        $name=$request->input('name');
        $pass=$request->input('pass');
        $res=UserModel::where(['nick_name'=>$name])->first();
        if($res){
            if(password_verify($pass,$res->pass)){
                $token=$this->token($res->uid);
                $redis='login_tokens:uid:'.$res->uid;
                Redis::set($redis,$token);
                Redis::expire($redis,60);
                $arr=[
                    'error'=>0,
                    'msg'=>'登录成功',
                    'data'=>[
                        'token'=>$this->token($res->uid)
                    ]
                ];
            }else{
                $arr=[
                    'error'=>5,
                    'msg'=>'密码不对'
                ];
            }
        }else{
            $arr=[
                'error'=>6,
                'msg'=>'用户不存在'
            ];
        }
        die(json_encode($arr,JSON_UNESCAPED_UNICODE));
    }
    private function token($uid){
        return substr(sha1($uid.time().Str::random(10)),5,15);
    }
}