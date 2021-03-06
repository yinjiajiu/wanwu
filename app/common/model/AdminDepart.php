<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class AdminDepart extends Model
{
    //无效
    const INVALID = 0;
    //有效
    const VALID   = 1;

    protected $schema = [
        'id'    => 'int',
        'name'  => 'string', //部门名称
        'status'=> 'int'     //状态0:禁用，1:有效
    ];
}