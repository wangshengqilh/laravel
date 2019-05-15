<?php
namespace App\Http\Controllers\Openssl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class OpensslController extends Controller{
    public function en(){
        $str='wsq';
        $key='1234';
        $iv='1234567890qwerty';
        $en=openssl_encrypt($str,'AES-128-CBC',$key,OPENSSL_RAW_DATA,$iv);
        $ben=urlencode(base64_encode($en));
        echo $ben;echo "<br>";
        echo $en;echo "<br>";
        echo $str;
    }
}