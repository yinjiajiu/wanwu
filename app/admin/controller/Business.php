<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\admin\controller;

use app\common\exception\ParamNotExistException;
use app\common\service\BusinessService;
use think\facade\Validate;

class Business extends BaseController
{
    /**
     * 获取供应商列表
     */
    public function list()
    {
        $page = $this->request->param('page',1);
        $ps = $this->request->param('pageSize',20);
        $offset = $ps*($page-1);
        $query = $this->request->param('query');
        $where = [];
        if($query) {
            $where['merchant'] = ['like','%'.$query.'%'];
        }
        $service = new BusinessService();
        $list = $service->getList($where,$offset,$ps);
        $total = $service->listTotal($where);
        $this->success(['list'=>$list,'total'=>$total]);
    }

    /**
     * 添加供应商
     */
    public function add()
    {
        $validate = Validate::rule([
            'merchant' => 'require',
            'account'  => 'require|alphaDash|min:5|max:50',
            'password' => 'min:5|max:20',
            'name'     => 'require',
            'phone'    => 'regex:(1)\d{10}',
            'sex'      => 'in:0,1,2',
            'age'      => 'number|between:1,120',
            'email'    => 'email',
        ]);
        if (!$validate->check($this->request->param())) {
            $this->error($validate->getError(), 102);
        }
        $result = (new BusinessService())->addBusiness($this->request->param());
        if($result){
            $this->success();
        }
        $this->error('该账户已被创建过',104);
    }

    /**
     * 修改供应商
     */
    public function edit()
    {
        $validate = Validate::rule([
            'bid'      => 'require',
            'merchant' => 'require',
            'account'  => 'require|alphaDash|min:5|max:50',
            'password' => 'min:5|max:20',
            'name'     => 'require',
            'phone'    => 'regex:(1)\d{10}',
            'sex'      => 'in:0,1,2',
            'age'      => 'number|between:1,120',
            'email'    => 'email',
        ]);
        if (!$validate->check($this->request->param())) {
            $this->error($validate->getError(), 102);
        }
        $result = (new BusinessService())->editBusiness($this->request->param());
        if($result === 1){
            $this->success();
        }elseif($result === -1){
            $this->error('该账户已存在',104);
        }else{
            $this->error('该商户不存在',104);
        }

    }

    /**
     * 删除供应商
     */
    public function delete()
    {
        $bid = $this->request->param('bid');
        if(!$bid || !is_numeric($bid)){
            throw new ParamNotExistException();
        }
        $reslut= (new BusinessService())->deleteBusiness($bid);
        if($reslut){
            $this->success();
        }
        $this->error('未找到该商户',501);
    }

    /**
     * 重置供应商密码
     */
    public function reset()
    {
        $bid = $this->request->param('bid');
        if(!$bid || !is_numeric($bid)){
            throw new ParamNotExistException();
        }
        $password = $this->request->param('password','');
        $reslut = (new BusinessService())->resetPass($bid,$password);
        if($reslut){
            $this->success();
        }
        $this->error('未找到该商户',501);
    }
}