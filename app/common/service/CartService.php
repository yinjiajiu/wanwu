<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/25 0025
 * Email: <1401128990@qq.com>
 */


namespace app\common\service;


use app\common\model\AttributeOption;
use app\common\model\AttributeRelate;
use app\common\model\OrderCart;
use think\facade\Db;

class CartService
{
    /**
     * 添加到购物车
     * @param array $param
     */
    public function addToCart( array $param)
    {
        $param['product_id'] = $param['pid'];
        unset($param['pid']);
        $options = $this->getAttributeRelate($param['sku_ids']);
        $skus = $this->getAttributeOption($options);
        $sku = '';
        foreach ($skus as $v) {
            $sku .= $v->name .';';
        }
        $param['sku'] = rtrim($sku,';');
        $param['status'] = OrderCart::VALID;
        $param['create_time'] = $param['update_time'] = date('Y-m-d H:i:s');
        OrderCart::create($param);
    }

    /**
     * 获取属性
     * @param $options
     */
    public function getAttributeOption(array $options)
    {
        return AttributeOption::where('id','in',$options)
            ->order('id','asc')
            ->field('name')
            ->select();
    }

    /**
     * 根据sku_ids获取具体属性
     */
    public function getAttributeRelate(?string $sku_ids){
        return AttributeRelate::where('id','in',$sku_ids)
            ->column('option_id');
    }

    /**
     * 修改到购物车
     * @param array $param
     */
    public function editToCart( array $param) :int
    {
        $options = $this->getAttributeRelate($param['sku_ids']);
        $skus = $this->getAttributeOption($options);
        $sku = '';
        foreach ($skus as $v) {
            $sku .= $v->name .';';
        }
        $cart = OrderCart::findOrEmpty($param['cart_id']);
        if($cart->isEmpty()){
            return -1;
        }
        if($cart->bid != $param['bid']){
            return 0;
        }
        $cart->sku = $sku;
        $cart->sku_ids = $param['sku_ids'];
        if(!empty($param['number'])){
            $cart->number = $param['number'];
        }
        $cart->save();
        return 1;
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
            c.category_id,c.bid,c.sku_ids,c.product_id as pid,c.number,c.sku,c.status as cart_status')
            ->where('c.bid',$bid)
            ->where('c.category_id',$category_id)
            ->order('c.id','desc')
            ->select();
        return $result;
    }

    /**
     * 购物车列表
     * @param int $bid
     * @param int $category_id
     */
    public function carts(string $cart_ids)
    {
        $result = Db::table('wu_order_cart')->alias('c')
            ->join('wu_product p','p.id = c.product_id')
            ->field('c.id as cart_id,p.no,p.title,p.price,p.discount,p.img,p.status as product_status,p.marque,
            c.category_id,c.bid,c.sku_ids,c.product_id as pid,c.number,c.sku,c.status as cart_status')
            ->where('c.id','in',$cart_ids)
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