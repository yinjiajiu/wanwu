<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class Admin extends Model
{
    //性别未知
    const SEX_UNKNOWN = 0;
    //男
    const SEX_MALE     = 1;
    //女
    const SEX_FEMALE   = 2;

    //无效
    const INVALID = 0;
    //有效
    const VALID   = 1;

    protected $schema = [
        'id'          => 'int',
        'account'     => 'string',   //登录账号
        'password'    => 'string',   //密码
        'name'        => 'string',   //姓名
        'avatar'      => 'string',   //头像
        'phone'       => 'string',   //电话
        'email'       => 'string',   //邮箱
        'sex'         => 'int',      //性别(0:未知,1:男,2:女)
        'status'      => 'int',      //状态(1:正常,0:锁定)
        'depart_id'   => 'int',      //部门id
        'level'       => 'int',      //上级领导id
        'desc'        => 'string',   //简介
        'entry_date'  => 'string',   //入职日期
        'create_time' => 'string',
        'update_time' => 'string',
    ];
}