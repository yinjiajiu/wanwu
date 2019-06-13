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
     * 直接下单
     */
    public function buy()
    {
        $validate = Validate::rule([
            'pid'         => 'require|number',
            'category_id' => 'require|number',
            'trade_name'  => 'require',
            'trade_phone' => 'require|regex:(1)\d{10}',
            'number'      => 'require|number|min:1',
            'area'        => 'require',
            'address'     => 'require',
            'sku_ids'     => 'require',
//            'code'        => 'require',
//            'bid'         => 'require',
//            'shop_address'=> '',
        ]);

        if (!$validate->check($this->request->param())) {
            $this->error($validate->getError(), 102);
        }
        if(empty($this->request->param('bid')) && empty($this->request->param('code'))){
            throw new ParamNotExistException();
        }
        $custom = trim($this->request->file('custom'));
        if($custom){
            $custom = json_encode([ 'logo' => $custom]);
        }else{
            $custom = '';
        }
        $result = (new OrderService())->buy($this->request->param(),$custom);
        if($result['error']){
            $this->error($result['msg'],106);
        }else{
            $this->success();
        }
    }

    /**
     * 从购物车下单
     */
    public function cartBuy()
    {
        $validate = Validate::rule([
            'cart_ids'    => 'require',
            'trade_name'  => 'require',
            'trade_phone' => 'require|regex:(1)\d{10}',
            'area'        => 'require',
            'address'     => 'require',
            'bid'         => 'require',
//            'shop_address'=> '',
        ]);

        if (!$validate->check($this->request->param())) {
            $this->error($validate->getError(), 102);
        }
        $result = (new OrderService())->cartBuy($this->request->param());
        if($result['error']){
            $this->error($result['msg'],106);
        }else{
            $this->success();
        }
    }

    /**
     * 查看订单信息
     */
    public function record()
    {
        $bid = $this->request->param('bid');
        $cid = $this->request->param('category_id');
        $page = $this->request->param('page',1);
        $ps = $this->request->param('pageSize',20);
        $domain = $this->request->domain();
        $offset = ($page-1) * $ps;
        if(!$bid || !$cid){
            throw new ParamNotExistException();
        }
        if(!is_numeric($bid) || !is_numeric($cid)){
            throw new InvalidParamException();
        }
        $result = (new OrderService())->record($bid,$cid,$offset,$ps,$domain);
        $this->success($result);
    }

    /**
     * 印章笔定制订单需商户确认生效
     */
    public function confirm(int $bid, int $sub_id)
    {
        $bid = $this->request->param('bid');
        $sub_id = $this->request->param('sub_id');
        $mark = $this->request->param('mark');
        if(!$bid || !$sub_id){
            throw new ParamNotExistException();
        }
        if(!is_numeric($bid) || !is_numeric($sub_id)){
            throw new InvalidParamException();
        }
        $result = (new OrderService())->confirm($bid,$sub_id,$mark);
        if($result['error']){
            $this->error($result['msg'],105);
        }else{
            $this->success();
        }
    }

    /**
     * 账目核对
     */
    public function check()
    {
        $code = trim($this->request->param('code'));
        $start = $this->request->param('start_date');
        $end = $this->request->param('end_date');
        if(empty($code) || empty($start)){
            throw new ParamNotExistException();
        }
        $start = date('Y-m-d 00:00:00',strtotime($start));
        if($end){
            $end = date('Y-m-d 23:59:59',strtotime($end));
        }else{
            $end = date('Y-m-d 23:59:59');
        }
        $result = (new OrderService())->check($code,$start,$end);
        $this->success($result);
    }
}