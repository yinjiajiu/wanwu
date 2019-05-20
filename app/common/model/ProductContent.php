<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/18 0018
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class ProductContent extends Model
{
    // 设置字段信息
    protected $schema = [
        'id'            => 'int',
        'pid'           => 'int',     //关联商品id
        'title'         => 'string',  //图文标题
        'content'       => 'string',  //商品简介
        'create_time'   => 'string',
        'update_time'   => 'string',
    ];
}