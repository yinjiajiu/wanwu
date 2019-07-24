<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/6/2 0002
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class OrderItem extends Model
{
    protected $schema = [
       'id'            => 'int',
       'sub_no'        => 'string', //主订单号
       'trade_no'      => 'string', //子订单号
       'product_id'    => 'int',    //商品id
       'sku'           => 'string', //商品sku
       'no'            => 'string', //商品编号
       'product_name'  => 'string', //'商品可能删除,所以这里要记录，不能直接读商品表
       'product_marque'=> 'string', //商品型号
       'number'        => 'int',    //数量
       'real_price'    => 'float',  //应付总价
       'free_price'    => 'float',  //减免价格
       'custom'        => 'string', //额外私人定制-json
       'desc'          => 'string', //额外的描述
       'unit_price'    => 'float',  //记录商品下单时单价，防止以后商品价格变动
       'create_time'   => 'string',
       'update_time'   => 'string',
    ];
}