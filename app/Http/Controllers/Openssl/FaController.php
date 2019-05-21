<?php
namespace App\Http\Controllers\Openssl;
use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class FaController extends Controller{
    public function fa(){
        return view('fa.fa');
    }
    public function kaisa(){
        $str='Hello Word';
        $n=3;
        $long=strlen($str);
        $pass='';
        for($i=0;$i<$long;$i++){
            $ascii=ord($str[$i])+$n;
            $pass.=chr($ascii);
        }
        $pss = $this->decrypt($pass);
        echo $pass;
        echo "<hr>";
        echo $pss;
    }
    public function decrypt($pass){
        $long=strlen($pass);
        $str="";
        $n=3;
        for($i=0;$i<$long;$i++){
            $ascii=ord($pass[$i]);
            $str .= chr($ascii-$n);
        }
        return $str;
    }
    public function de(){
        $key='1234';
        $iv='1234567890qwerty';
        $arr=$_GET['str'];
        echo $arr;echo "<br>";
        $bde=urldecode(base64_decode($arr));
        echo $bde;echo "<br>";
        $de=openssl_decrypt($bde,'AES-128-CBC',$key,OPENSSL_RAW_DATA,$iv);
        echo $de;echo "<br>";
    }
//    public function logininfo(Request $request){
//        $name=$request->input('name');
//        $pass=$request->input('pass');
//        if (empty($name) || empty($pass)){
//            $response=[
//                'status' =>500,
//                'msg' => '账号或密码不能为空'
//            ];
//        }else{
//            $response=[
//                'status' =>200,
//                'msg' => '登录成功'
//            ];
//        }
//        return json_encode($response,256);
//    }
    public function fei(){
        $data=[
            'name'=>'wsq',
            'pass'=>'wsqwsq'
        ];
        $data=json_encode($data,256);
        $pri=openssl_get_privatekey("file://".storage_path('app/key/priv.key'));
        openssl_private_encrypt($data,$encrypt,$pri);
        $encrypt=base64_encode($encrypt);
//        echo $encrypt;
        $url='https://wsqapi.lab993.com/feis';
        //初始化URL
        $ch = curl_init();
        //设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置post方式提交
        curl_setopt($ch, CURLOPT_POST, 1);
        //传值
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encrypt);
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
    public function dui(){
        $data=[
            'name'=>'lh',
            'pass'=>'lhwlp',
            'age'=>12
        ];
        $data=json_encode($data);
        $key='1234';
        $iv='1234567890qazwsx';
        $en=openssl_encrypt($data,'AES-128-CBC',$key,OPENSSL_RAW_DATA,$iv);
        $ben=base64_encode($en);
        $url='https://wsqapi.lab993.com/jies';
        //初始化URL
        $ch = curl_init();
        //设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置post方式提交
        curl_setopt($ch, CURLOPT_POST, 1);
        //传值
        curl_setopt($ch, CURLOPT_POSTFIELDS, $ben);
        //返回结果不输入
//      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
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
    public function yan(){
        $data=[
            'name'=>'qazwsx',
            'age'=>12,
            'pass'=>'wsqsnbb',
            'num'=>12345678901
        ];
        $data=json_encode($data);
        $pri=openssl_get_privatekey("file://".storage_path('app/key/priv.key'));
        openssl_sign($data,$sign,$pri);
        $sign=base64_encode($sign);
        $url='https://wsqapi.lab993.com/yans?sign='.urlencode($sign);
//        echo $url;
        //初始化URL
        $ch = curl_init();
        //设置抓取的url
        curl_setopt($ch, CURLOPT_URL, $url);
        //设置post方式提交
        curl_setopt($ch, CURLOPT_POST, 1);
        //传值
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        //返回结果不输入
//      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
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