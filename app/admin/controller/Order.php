<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/6/07 0019
 * Email: <1401128990@qq.com>
 */


namespace app\admin\controller;
use app\common\service\OrderService;
use app\common\exception\ParamNotExistException;
use app\common\exception\InvalidParamException;

class Order extends BaseController
{
    /**
     * 订单列表
     */
    public function list()
    {
        $merchant = trim($this->request->param('merchant',''));
        $where = [];
        trim($this->request->param('sub_no')) && $where[] = ['sub_no','=',trim($this->request->param('sub_no'))];
        $this->request->param('category_id') && $where[] = ['category_id','=',$this->request->param('category_id')];
        $this->request->param('status') && $where[] = ['status','=', $this->request->param('status')];
        if($this->request->param('start_date')){
            $start = date('Y-m-d 00:00:00',trim(strtotime($this->request->param('start_date'))));
           if($this->request->param('end_date')){
                $end = date('Y-m-d 23:59:59',trim(strtotime($this->request->param('end_date'))));
                $where[] = ['create_time','between',$start.','.$end];
           }else{
                $where[] = ['create_time','>=',$start];
           }
        }elseif($this->request->param('end_date')){
            $end = date('Y-m-d 23:59:59',trim(strtotime($this->request->param('end_date'))));
            $where[] = ['create_time','<=',$end];
        }
        $page = $this->request->param('page',1);
        $ps = $this->request->param('pageSize',20);
        $domain = $this->request->domain();
        $offset = ($page-1) * $ps;
        $result = (new OrderService())->list($merchant,$offset,$ps,$where,$domain);
        $this->success($result);
    }

    /**
     * 订单状态
     */
    public function change()
    {
        $sub_id = $this->request->param('sub_id');
        $status = $this->request->param('status');
        if(!$sub_id || !$status){
            throw new ParamNotExistException();
        }
        if(!is_numeric($sub_id) || !is_numeric($status)){
            throw new InvalidParamException();
        }
        $result = (new OrderService())->change($sub_id,$status);
        if($result['error']){
            $this->error($result['msg']);
        }else{
            $this->success();
        }
    }
}