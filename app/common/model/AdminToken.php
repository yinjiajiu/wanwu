<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class AdminToken extends Model
{
    //有效时长（默认5天）
    const EXPIRE = 60*60*24*5;

    protected $schema = [
        'id'          => 'int',
        'uid'         => 'int',     //后台用户id
        'token'       => 'string',  //token
        'expire'      => 'int',     //过期时间
        'count'       => 'int',     //登录次数
        'update_time' => 'string'   //更新时间
    ];
}