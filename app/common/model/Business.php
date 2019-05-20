<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class Business extends Model
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
        'merchant'    => 'string',  //商户店铺名
        'account'     => 'string',  //商户账号
        'password'    => 'string',  //商户密码
        'name'        => 'string',  //联系人姓名
        'phone'       => 'string',  //手机号码
        'avatar'      => 'string',  //用户头像
        'sex'         => 'int',     //性别0=>未知，1=>男，2=>女
        'age'         => 'int',     //年龄
        'email'       => 'string',  //用户邮箱
        'area'        => 'string',  //地区
        'address'     => 'string',  //店铺地址
        'status'      => 'int',     //商户状态0=>商户已被禁用，1=>正常
        'create_time' => 'string',
        'update_time' => 'string',
    ];
}