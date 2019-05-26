<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/24 0024
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class OrderCart extends Model
{
    //有效
    const VALID = 1;
    //无效
    const INVALID = 0;

    protected $schema = [
        'id'            => 'int',
        'product_id'    => 'int',   //商品id
        'bid'           => 'int',   //商户id
        'category_id'   => 'int',   //分类id
        'sku_ids'       => 'string',//sku串
        'number'        => 'int',   //数量
        'sku'           => 'string',//sku组合名
        'status'        => 'int',   //是否有效
        'create_time'   => 'string',
        'update_time'   => 'string',
    ];
}