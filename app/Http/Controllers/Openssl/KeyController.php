<?php
namespace App\Http\Controllers\Openssl;

use App\Http\Controllers\Controller;
use App\Model\OpensslModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
class KeyController extends Controller{
    //入库
    public function add(){
        $private_key = "/usr/local/openssl/priv.key";
        $pblic_key = "/usr/local/openssl/pub.pem";
        $privatekey=file_get_contents($private_key);
        $pblickey=file_get_contents($pblic_key);
        $data=[
            'public'=>$pblickey,
            'private'=>$privatekey
        ];
        OpensslModel::insert($data);
    }
    //加密
    public function encode(Request $request){
        $str=$request->input('str');
        $key=OpensslModel::first()->toArray();
        $privatekey=$key['private'];
        $encryptData="";
        openssl_private_encrypt($str,$encryptData,$privatekey);
        $content=base64_encode($encryptData);
        echo $content;
    }
    //解密
    public function decode(Request $request){
        $str=$request->input('str');
        $str=str_replace('','+',$str);
        $content=base64_decode($str);
        $key=OpensslModel::first()->toArray();
        $publickey=$key['public'];
        $go="";
        openssl_public_decrypt($content,$go,$publickey);
        dump($publickey);
    }
    //切片上传
    public function upload(){
        return view("test/test");
    }
    public function uploadinfo(){
        $tmpname=$_FILES['file']['tmp_name'];
        $imgname=$_FILES['file']['name'];
        $filepath="./image/$imgname";
//        echo $tmpname;die;
        file_put_contents($filepath,file_get_contents($tmpname),FILE_APPEND);
        return json_encode(['success'=>1,'code'=>0]);
    }
}