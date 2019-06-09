<?php
namespace app\common\helper;

use think\facade\Cache;
use think\facade\Config;

/**
 * Class WechatHelper
 */
class WechatHelper{
    private $path;
    private $scene;

    public function __construct(string $path = '',$scene = '')
    {
        $this->path = $path;
        $this->scene = $scene;
    }
    //获取access_token
    public function getAccessToken()
    {
        if($access_token = Cache::get('wx_access_token','')){
            return $access_token;
        }
        $wx = Config::get(app.wx);
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$wx['appid']."&secret=".$wx['secret'];
        $data = json_decode( curl($url,false,''),true);
        if(!$data['code']){
            Cache::set('wx_access_token',$data['access_token'],5400);
            return $data['access_token'];
        }else{
            return false;
        }
    }

    /**
     * 获取小程序码，适用于需要的码数量极多的业务场景。通过该接口生成的小程序码，永久有效，数量暂无限制
     */
    public function UnLimitQrCode() {
        $md5 = md5($this->path.$this->scene);
        $path = '/uploads/wxqrcode/'.$md5.'.png';
        $file = '../public'.$path;
        if(file_exists($file)){
            return $path;
        }
        $data = [
            'scene'=> 'category_id='.$this->scene,
            'path' => $this->path,
            'width'=> 430
        ];
        $data = json_encode($data);
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=" . $access_token;
        $result = curl($url,true,$data);
        $res = file_put_contents($file,$result);//将微信返回的图片数据流写入文件
        return $path;
    }
}

