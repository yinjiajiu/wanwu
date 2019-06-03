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
use think\Exception;
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
        $file = $this->request->file('file');
        if($file){
            $path = '/uploads/api/custom/';
            $info = $file->validate(['size'=>1024*1024*2])->move( '../public/'.$path );
            if($info) {
                $name = str_replace('\\', '/', $info->getSaveName());
                $custom = json_encode([ 'logo' => $path . $name]);
            }
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


}