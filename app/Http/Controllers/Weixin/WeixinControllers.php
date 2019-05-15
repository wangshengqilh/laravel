<?php

namespace App\Http\Controllers\Weixin;

use App\Model\UserModel;
use App\Model\WeixinChatModel;
use App\Model\WeixinUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redis;
use GuzzleHttp;
use Illuminate\Support\Facades\Storage;

use App\Model\WeixinMedia;

class WeixinControllers extends Controller{
	protected $redis_weixin_access_token = 'str:weixin_access_token';
	public function getWXAccessToken(){
        //获取缓存
        $token = Redis::get($this->redis_weixin_access_token);
        if(!$token){        // 无缓存 请求微信接口
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WEIXIN_APPID').'&secret='.env('WEIXIN_APPSECRET');
            $data = json_decode(file_get_contents($url),true);

            //记录缓存
            $token = $data['access_token'];
            Redis::set($this->redis_weixin_access_token,$token);
            Redis::setTimeout($this->redis_weixin_access_token,3600);
        }
        return $token;
        $redis = new Redis();
   		$redis->connect('127.0.0.1', 6379);
   		echo "Connection to server sucessfully";
   		//存储数据到列表中
   		$redis->lpush("tutorial-list", "Redis");
   		$redis->lpush("tutorial-list", "Mongodb");
   		$redis->lpush("tutorial-list", "Mysql");
   		// 获取存储的数据并输出
   		$arList = $redis->lrange("tutorial-list", 0 ,5);
   		echo "Stored string in redis:: "
   		print_r($arList);

    }
    public function getUserInfo($openid){
        //$openid = 'oLreB1jAnJFzV_8AGWUZlfuaoQto';
        $access_token = $this->getWXAccessToken();      //请求每一个接口必须有 access_token
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

        $data = json_decode(file_get_contents($url),true);
        //echo '<pre>';print_r($data);echo '</pre>';
        return $data;
    }
    public function wxEvent()
    {
        $data = file_get_contents("php://input");


        //解析XML
        $xml = simplexml_load_string($data);        //将 xml字符串 转换成对象

        //记录日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('logs/wx_event.log',$log_str,FILE_APPEND);

        $event = $xml->Event;                       //事件类型
        $openid = $xml->FromUserName;               //用户openid


        // 处理用户发送消息
        if(isset($xml->MsgType)){
            if($xml->MsgType=='text'){            //用户发送文本消息
                $msg = $xml->Content;
                //记录聊天消息

                $data = [
                    'msg'       => $xml->Content,
                    'msgid'     => $xml->MsgId,
                    'openid'    => $openid,
                    'msg_type'  => 1        // 1用户发送消息 2客服发送消息
                ];

                $id = WeixinChatModel::insertGetId($data);
                var_dump($id);
                //echo $xml_response;
            }elseif($xml->MsgType=='image'){       //用户发送图片信息
                //视业务需求是否需要下载保存图片
                if(1){  //下载图片素材
                    $file_name = $this->dlWxImg($xml->MediaId);
                    $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. date('Y-m-d H:i:s') .']]></Content></xml>';
                    echo $xml_response;

                    //写入数据库
                    $data = [
                        'openid'    => $openid,
                        'add_time'  => time(),
                        'msg_type'  => 'image',
                        'media_id'  => $xml->MediaId,
                        'format'    => $xml->Format,
                        'msg_id'    => $xml->MsgId,
                        'local_file_name'   => $file_name
                    ];

                   $m_id = WeixinMedia::insertGetId($data);
                   var_dump($m_id);
                }
            }elseif($xml->MsgType=='voice'){        //处理语音信息
                $this->dlVoice($xml->MediaId);
            }elseif($xml->MsgType=='event'){        //判断事件类型

                if($event=='subscribe'){                        //扫码关注事件

                    $sub_time = $xml->CreateTime;               //扫码关注时间
                    //获取用户信息
                    $user_info = $this->getUserInfo($openid);
                    //保存用户信息
                    $u = WeixinUser::where(['openid'=>$openid])->first();
                    if($u){       //用户不存在
                        //echo '用户已存在';
                    }else{
                        $user_data = [
                            'openid'            => $openid,
                            'add_time'          => time(),
                            'nickname'          => $user_info['nickname'],
                            'sex'               => $user_info['sex'],
                            'headimgurl'        => $user_info['headimgurl'],
                            'subscribe_time'    => $sub_time,
                        ];

                        $id = WeixinUser::insertGetId($user_data);      //保存用户信息
                        //var_dump($id);
                    }
                    $xml_response = '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$xml->ToUserName.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. '欢迎关注'.']]></Content></xml>';
                    echo $xml_response;
                }elseif($event=='CLICK'){               //click 菜单
                    if($xml->EventKey=='kefu01'){       // 根据 EventKey判断菜单
                        $this->kefu01($openid,$xml->ToUserName);
                    }
                }

            }

        }
    }ss
}
