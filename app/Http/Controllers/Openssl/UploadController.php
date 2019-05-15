<?php
namespace App\Http\Controllers\Openssl;
use App\Model\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
class UploadController extends Controller{
    public function show(){
        return view("upload.show");
    }
}