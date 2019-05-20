<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class AuthGroup extends Model
{
    //禁用
    const INVALID = 0;
    //有效
    const VALID   = 1;

    protected $schema = [
        'id'       => 'int',
        'title'    => 'string',  //用户组中文名称
        'status'   => 'int',     //状态 : 1为正常,0为禁用
        'rules'    => 'string',  //规则ID （这里填写的是 wu_auth_rule里面的规则的ID)
    ];
}