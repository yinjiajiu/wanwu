<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class AuthGroupAccess extends Model
{
    protected $schema = [
        'id'       => 'int',
        'uid'      => 'int',   //用户id
        'group_id' => 'int',   //组id
    ];
}