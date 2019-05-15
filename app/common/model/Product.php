<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/15 0015
 * Email: <1401128990@qq.com>
 */
namespace app\common\model;

use think\Model;

class Product extends Model
{
    //下架
    const DOWN_SHELF   = 0;
    //上架
    const UP_SHELF     = 1;
    //删除
    const DELETE_SHELF = 2;

    // 设置字段信息
    protected $schema = [
        'id'            => 'int',
        'no'            => 'string',  //商品编号
        'price'         => 'float',   //商户当前价格
        'original_price'=> 'float',   //商户原价
        'category_id'   => 'int',     //商品类别
        'title'         => 'string',  //商品标题
        'tags'          => 'string',  //商品标签
        'marque'        => 'string',  //商品型号
        'img'           => 'string',  //商品主图片链接
        'keywords'      => 'string',  //商品关键字
        'brand'         => 'string',  //商品品牌名称
        'unit'          => 'string',  //商品单位
        'desc'          => 'string',  //商品简介
        'discount'      => 'float',   //折扣 * 价格 = 减免
        'status'        => 'int',     //商品状态0=>下架，1=>上架，2=>已删除
        'barcode'       => 'string',  //仓库条码
        'stock'         => 'int',     //库存量
        'create_time'   => 'string',
        'update_time'   => 'string',
    ];
}