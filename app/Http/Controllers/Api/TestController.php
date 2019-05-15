<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
class TestController extends Controller
{
    //请求Api  获取数据
    public function test(){
        $url="xyxapi.96myshop.cn";     //接口地址
        $client= new Client();
        $response = $client->request('GET',$url);
        echo $response->getBody();
    }
}
