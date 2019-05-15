<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/15 0015
 * Email: <1401128990@qq.com>
 */
namespace app\common\model;

use think\Model;

class ProductImg extends Model
{
    // 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'pid'         => 'int',     //关联商品id
        'desc'        => 'string',  //图片描述
        'img'         => 'string',  //图片链接
        'sort'        => 'int',     //图片顺序号
        'size'        => 'string',  //图片大小
        'ext'         => 'string',  //图片扩展名
        'create_time' => 'string',
        'update_time' => 'string',
    ];
}