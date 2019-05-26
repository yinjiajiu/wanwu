<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/25 0025
 * Email: <1401128990@qq.com>
 */


namespace app\api\controller;


use app\common\exception\InvalidParamException;
use app\common\exception\ParamNotExistException;
use app\common\service\OrderService;
use think\facade\Validate;

class Order extends BaseController
{

    /**
    * 添加购物车
    */
    public function cartAdd()
    {
        $validate = Validate::rule([
            'category_id' => 'require|number',
            'product_id'  => 'require|number',
            'bid'         => 'require|number',
            'sku_ids'     => 'require',
            'number'      => 'require|number|min:1',

        ]);
        if (!$validate->check($this->request->param())) {
            $this->error($validate->getError(), 102);
        }
        (new OrderService())->addToCart($this->request->param());
        $this->success();
    }

    /**
     * 修改购物车
     */
    public function cartEdit()
    {
        $validate = Validate::rule([
            'cart_id'     => 'require|number',
            'sku_ids'     => 'require',
        ]);
        if (!$validate->check($this->request->param())) {
            $this->error($validate->getError(), 102);
        }
        (new OrderService())->editToCart($this->request->param());
        $this->success();
    }

    /**
     * 显示购物车
     */
    public function cartList()
    {
        $bid = $this->request->param('bid');
        $category_id = $this->request->param('category_id');
        if(!$bid || !$category_id){
            throw new ParamNotExistException();
        }
        if(!is_numeric($bid) || !is_numeric($category_id)){
            throw new InvalidParamException();
        }
        $result = (new OrderService())->cartList($bid,$category_id);
        $domain = $this->request->domain();
        foreach ($result as &$v){
            $v['img_path'] = $v['img'] ? $domain.$v['img'] : '';
            unset($v['img']);
        }
        $this->success($result);
    }

    /**
     * 删除购物车
     */
    public function cartDelete()
    {
        $bid = $this->request->param('bid');
        $cart_ids = $this->request->param('cart_ids');
        if(!$bid || !$cart_ids){
            throw new ParamNotExistException();
        }
        if(!is_numeric($bid)){
            throw new InvalidParamException();
        }
        (new OrderService())->cartDelete($bid,$cart_ids);
        $this->success();
    }

    /**
     * 购物车添加数量
     */
    public function increase()
    {
        $bid = $this->request->param('bid');
        $cart_id = $this->request->param('cart_id');
        $number = $this->request->param('number');
        if(!$bid || !$cart_id || !$number){
            throw new ParamNotExistException();
        }
        if(!is_numeric($bid) || !is_numeric($cart_id) || !is_numeric($number)){
            throw new InvalidParamException();
        }
        if($number < 1 ){
            $this->error('数量不能小于1',105);
        }
        $result = (new OrderService())->increase($bid,$cart_id,$number);
        if($result){
            $this->success();
        }else{
            $this->error('无效操作',304);
        }
    }

    /**
     * 直接下单
     */
    public function directOrder()
    {

    }

}