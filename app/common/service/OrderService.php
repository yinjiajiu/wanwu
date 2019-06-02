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
use app\common\model\{BusinessCode, OrderItem, SubOrder, Product, ProductCategory};
use think\facade\Db;

class OrderService
{
    public function buy(array $param,string $custom = '')
    {
        //获取商品信息
        $product = Product::findOrEmpty($param['pid']);
        if($product->isEmpty()){
            return ['error'=>true,'msg'=>'该商品不存在'];
        }
        if((int)$product->status === Product::DOWN_SHELF){
            return ['error'=>true,'msg'=>'该商品已下架'];
        }
        if((int)$product->status === Product::DELETE_SHELF){
            return ['error'=>true,'msg'=>'该商品已删除'];
        }
        //理论总价
        $total_price = bcmul($product->price , $param['number'],2);
        //实际支付 = 理论总价 - 折扣价
        $actual_price = bcsub($total_price , bcmul($product->discount , $param['number'],2),2);
        //生成唯地址
        $address = $param['area'] . $param['address'];
        if(empty($param['bid'])){
            if(empty($param['code'])){
                return ['error'=>true,'msg'=>'请输入供应商编码'];
            }else{
                $bid = BusinessCode::where('code',trim($param['code']))->value('bid');
                if(!$bid){
                    return ['error'=>true,'msg'=>'该供应商编码不存在'];
                }
            }
        }else{
            $bid = $param['bid'];
        }
        //查询订单分类，若为印章笔定制则需要商户确认才行。
        $cate = ProductCategory::find($param['category_id']);
        //收货一主订单号
        $sub_no = orderNum();
        $date = date('Y-m-d H:i:s');
        $cart = new CartService();
        $options = $cart->getAttributeRelate($param['sku_ids']);
        $skus = $cart->getAttributeOption($options);
        $sku = '';
        foreach ($skus as $v) {
            $sku .= $v->name .';';
        }

        //处理所有子订单的商品价格
        Db::startTrans();
        try {
            SubOrder::create([
                'sub_no'      => $sub_no,
                'bid'         => $bid,
                'category_id' => $param['category_id'],
                'total_price' => $total_price,
                'actual_price'=> $actual_price,
                'trade_name'  => $param['trade_name'],
                'trade_phone' => $param['trade_phone'],
                'address'     => $address,
                'code'        => $param['code'] ?? '',
                'shop_address'=> $param['shop_address'] ?? '',
                'status'      => $cate->status == ProductCategory::BUSINESS_OBJECT ? SubOrder::WAIT_SHIP : SubOrder::WAIT_CONFIRM,
                'create_time' => $date,
                'update_time' => $date
            ]);
            OrderItem::create([
                'sub_no'       => $sub_no,
                'trade_no'     => $sub_no.'01',
                'product_id'   => $param['pid'],
                'sku'          => $sku,
                'product_name' => $product->title,
                'product_marque' => $product->marque,
                'number'       => $param['number'],
                'total_price'  => $actual_price,
                'free_price'   => bcmul($product->discount , $param['number'],2),
                'custom'       => $custom,
                'unit_price'   => bcsub($product->price,$product->discount，2),
                'create_time'  => $date,
                'update_time'  => $date
            ]);
            // 提交事务
            Db::commit();
            return ['error'=>false];
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ['error'=>true,'msg'=>$e->getMessage()];
        }
    }
}