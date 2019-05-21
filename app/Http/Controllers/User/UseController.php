<?php
namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Support\Str;
class UseController extends Controller
{
    public function reg(){
        return view('users.use');
    }
    public function reginfo(Request $request){
        $data=$request->input();
        $data=json_encode($data,256);
        $pri=openssl_get_privatekey("file://".storage_path('app/key/priv.key'));
        openssl_private_encrypt($data,$arr,$pri);
        $res=base64_encode($arr);
        $url='https://wsqapi.lab993.com/regs';
        //初始化URL
        $ch = curl_init();
        //设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置post方式提交
        curl_setopt($ch, CURLOPT_POST, 1);
        //传值
        curl_setopt($ch, CURLOPT_POSTFIELDS, $res);
        //返回结果不输入
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //响应头
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-type:text/plain']);
        //获取抛出错误
        $num=curl_errno($ch);
        if($num>0){
            echo 'curl错误码：'.$num;exit;
        }
        //发起请求
        curl_exec($ch);
        //关闭并释放资源
        curl_close($ch);
    }
    public function log(){
        return view('users/log');
    }
    public function loginfo(Request $request){
        $data=$request->input();
        $data=json_encode($data,256);
        $pri=openssl_get_privatekey("file://".storage_path('app/key/priv.key'));
        openssl_private_encrypt($data,$res,$pri);
        $arr=base64_encode($res);
        $url='https://wsqapi.lab993.com/logs';
        //初始化URL
        $ch = curl_init();
        //设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置post方式提交
        curl_setopt($ch, CURLOPT_POST, 1);
        //传值
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        //返回结果不输入
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //响应头
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-type:text/plain']);
        //获取抛出错误
        $num=curl_errno($ch);
        if($num>0){
            echo 'curl错误码：'.$num;exit;
        }
        //发起请求
        curl_exec($ch);
        //关闭并释放资源
        curl_close($ch);
    }
}