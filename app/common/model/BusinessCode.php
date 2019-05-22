<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/21 0021
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class BusinessCode extends Model
{
    //无效
    const INVALID = 0;
    //有效
    const VALID   = 1;

    protected $schema = [
        'id'          => 'int',
        'bid'         => 'int',     //关联商户id
        'desc'        => 'string',  //描述
        'code'        => 'string',  //商户码
        'class'       => 'int',     //类别
        'status'      => 'int',     //'商户码状态0=>已失效，1=>正常
        'create_time' => 'string',
        'update_time' => 'string',
    ];
}