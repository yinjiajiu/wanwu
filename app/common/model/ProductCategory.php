<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/15 0015
 * Email: <1401128990@qq.com>
 */
namespace app\common\model;

use think\Model;

class ProductCategory extends Model
{
    //无效分类
    const STATUS_INVALID = 0;
    //有效分类
    const STATUS_VALID   = 1;
    // 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'name'        => 'string',
        'pid'         => 'int',
        'code'        => 'string',
        'sort'        => 'int',
        'bar_code'    => 'string',
        'status'      => 'int',
        'create_time' => 'string',
        'update_time' => 'string',
    ];
}