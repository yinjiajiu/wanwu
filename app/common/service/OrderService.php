<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/25 0025
 * Email: <1401128990@qq.com>
 */


namespace app\common\service;


use app\common\model\AttributeOption;
use app\common\model\OrderCart;
use think\facade\Db;

class OrderService
{
    /**
     * 添加到购物车
     * @param array $param
     */
    public function addToCart( array $param)
    {
        $skus = $this->getAttributeOption($param['sku_ids']);
        $sku = '';
        foreach ($skus as $v) {
            $sku .= $v->name .';';
        }
        $param['sku'] = rtrim($sku,';');
        $param['status'] = OrderCart::VALID;
        $param['create_time'] = $param['update_time'] = date('Y-m-d H:i:s');
        OrderCart::create($param);
    }

    public function getAttributeOption($sku_ids)
    {
        return AttributeOption::where('id','in',$sku_ids)
            ->order('id','asc')
            ->field(' name')
            ->select();
    }

    /**
     * 修改到购物车
     * @param array $param
     */
    public function editToCart( array $param)
    {
        $skus = $this->getAttributeOption($param['sku_ids']);
        $sku = '';
        foreach ($skus as $v) {
            $sku .= $v->name .';';
        }
        $cart = OrderCart::find($param['cart_id']);
        $cart->sku = $sku;
        $cart->sku_ids = $param['sku_ids'];
        if(!empty($param['number'])){
            $cart->number = $param['number'];
        }
        $cart->save();
    }

    /**
     * 购物车列表
     * @param int $bid
     * @param int $category_id
     */
    public function cartList(int $bid ,int $category_id)
    {
        $result = Db::table('wu_order_cart')->alias('c')
            ->join('wu_product p','p.id = c.product_id')
            ->field('c.id as cart_id,p.no,p.title,p.price,p.discount,p.img,p.status as product_status,
            c.category_id,c.bid,c.sku_ids,c.product_id,c.number,c.sku,c.status as cart_status')
            ->where('c.bid',$bid)
            ->where('c.category_id',$category_id)
            ->order('c.id','desc')
            ->select();
        return $result;
    }

    /**
     * 购物车删除
     * @param int $bid
     * @param $cart_ids
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function cartDelete(int $bid,$cart_ids)
    {
        OrderCart::where('bid',$bid)
            ->where('id','in',$cart_ids)
            ->delete();
    }

    /**
     * 购物车添加数量
     */
    public function increase(int $cart_id,int $bid,int $number)
    {
        $cart = OrderCart::where('id',$cart_id)
            ->where('bid',$bid)
            ->findOrEmpty();
        if($cart->isEmpty()){
            return false;
        }
        $cart->number = $number;
        $cart->save();
        return true;
    }
}