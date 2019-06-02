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
use app\common\model\BusinessCode;
use app\common\model\OrderCart;
use app\common\model\Product;
use app\common\model\ProductCategory;
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
        //更具唯一主订单号生成子订单号

        //处理所有子订单的商品价格
        Db::startTrans();
        try {
            $user = new User;
            $user->save([
                'name'  =>  'thinkphp',
                'email' =>  'thinkphp@qq.com'
            ]);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
        }



    }
}