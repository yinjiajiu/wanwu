<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/18 0018
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class ProductAttribute extends Model
{
    //不是商品属性
    const NOT_SALE_ATTR = 0;
    //是商品属性
    const SALE_ATTR     = 1;
    //无图片链接
    const NOT_SRC = 0;
    //有图片链接
    const IS_SRC  = 1;
    // 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'category_id' => 'int',     //商品类别编号
        'name'        => 'string',  //属性名称
        'sort'        => 'int',     //属性优先级
        'is_sale'     => 'int',     //是否销售属性 0=>否 1=>是
        'has_src'     => 'int',     //是否拥有图片链接 0=>否，1=>是
    ];
}