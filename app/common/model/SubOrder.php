<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/6/2 0002
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class SubOrder extends Model
{
    /**
     * 订单状态枚举
     */
    //待确认
    const WAIT_CONFIRM = 0;
    //待付款
    const WAIT_PAY = 5;
    //超时取消
    const TIME_OUT = 7;
    //待发货
    const WAIT_SHIP = 10;
    //普通客户取消
    const COMMON_CANCER = 11;
    //商户取消
    const BUSINESS_CANCEL = 12;
    //厂家取消
    const PRODUCT_CANCEL = 13;
    //待签收
    const WAIT_RECEIPT = 15;
    //已签收
    const HAS_RECEIPT = 20;
    //退货中
    const RETURN_ING = 25;
    //已退货
    const HAS_RETURN = 30;

    const STATUS_ARR = [
        self::WAIT_CONFIRM,
        self::WAIT_PAY,
        self::TIME_OUT,
        self::WAIT_SHIP,
        self::COMMON_CANCER,
        self::BUSINESS_CANCEL,
        self::PRODUCT_CANCEL,
        self::WAIT_RECEIPT,
        self::HAS_RECEIPT,
        self::RETURN_ING,
        self::HAS_RETURN,
    ];

    // 设置字段信息
    protected $schema = [
        'id'            => 'int',
        'sub_no'        => 'string',    //主订单号
        'bid'           => 'int',   //商户id
        'category_id'   => 'int',   //分类id
        'total_price'   => 'float', //应付总价
        'actual_price'  => 'float', //实付总价
        'express_price' => 'float', //运费
        'trade_name'    => 'string',    //收货名
        'trade_phone'   => 'string',    //收货号码
        'address'       => 'string',    //收货地址
        'mark'          => 'string',    //订单备注
        'code'          => 'string',    //供应商编码
        'shop_address'  => 'string',    //店铺地址
        'status'        => 'int',   //订单状态
        'receive_time'  => 'string',    //收货时间
        'return_time'   => 'string',    //退货时间
        'create_time'   => 'string',
        'update_time'   => 'string',
    ];
}