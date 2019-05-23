<?php
namespace App\Http\Controllers\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use App\Model\VersionsModel;
use Illuminate\Support\Facades\DB;
class TextController extends Controller{
    /**
     * 登录
     * @param Request $request
     * @return int
     */
    public function login(Request $request){
        $name=$request->input('name');
        $pass=$request->input('pass');
        $where=[
            'nick_name'=>$name,
        ];
        $arr=UserModel::where($where)->first();
        $res=$arr['uid'];
        if($res){
            $error=[
                'arr'=>$res,
                'msg'=>1
            ];
            return json_encode($error);
        }
    }

    /**
     * 注册
     * @param Request $request
     * @return int
     */
    public function reg(Request $request){
        $name=$request->input('name');
        $pass=$request->input('pass');
        $where=[
            'nick_name'=>$name,
        ];
        $arr=UserModel::where($where)->first();
        if(empty($arr)){
            $date=[
                'nick_name'=>$name,
                'pass'=>$pass,
            ];
            UserModel::insert($date);
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 判断版本
     * @param Request $request
     */
    public function versions(Request $request){
        $id=$request->input('id');
        $arr=VersionsModel::where('versions_num','>',$id)->first();
        if($arr){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 加载刷新
     * @param Request $request
     * @return false|string
     */
    public function show(Request $request){
        $arr=DB::table('p_goods')->paginate(2)->toArray()['data'];
        return json_encode($arr);
    }
    public function wsq(){
        return view('shop.mstore.index');
    }
}
