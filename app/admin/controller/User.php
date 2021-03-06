<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\admin\controller;

use app\common\exception\InvalidParamException;
use app\common\exception\ParamNotExistException;
use app\common\service\AdminService;
use think\facade\Validate;

class User extends BaseController
{
    public function list()
    {
        $query = $this->request->param('query');
        if($query){
            $where[] = ['name','like','%'.$query.'%'];
        }else{
            $where = [];
        }
        $result = (new AdminService())->getList($where);
        $this->success($result);
    }

    /**
     * 删除管理人员
     * @throws InvalidParamException
     */
    public function delete()
    {
        $id = $this->request->param('uid');
        if(!$id || !is_numeric($id)){
            throw new InvalidParamException();
        }
        $result = (new AdminService())->delete($id);
        if(!$result){
            $this->error('非法操作，该次操作已被记录在库',501);
        }
        $this->success();
    }

    /**
     * 添加管理员
     */
    public function add()
    {
        $validate = Validate::rule([
            'account'      => 'require|alphaDash|min:5|max:50',
            'password'     => 'alphaDash|min:5|max:20',
            'phone'        => 'regex:(1)\d{10}',
            'email'        => 'email',
            'sex'          => 'in:0,1,2',
            'entry_date'   => 'dateFormat:Y-m-d'
        ])->message([
                'account.require'   => '账号必填',
                'account.alphaDash' => '账号必须是数字，字母下划线',
                'account.min' => '账号长度不能低于5位',
                'account.max' => '账号长度不能长于50位',
                'password.alphaDash' => '密码必须是数字，字母下划线',
                'password.min' => '密码长度不能低于5位',
                'password.max' => '密码长度不能长于20位',
            ]
        );
        if (!$validate->check($this->request->param())) {
            $this->error($validate->getError(), 102);
        }
        $result = (new AdminService())->add($this->request->param());
        if(!$result){
            $this->error('该账户已被创建过',104);
        }
        $this->success();
    }

    /**
     * 修改管理员
     */
    public function edit()
    {
        $validate = Validate::rule([
            'uid'          => 'require|number',
            'password'     => 'min:4|max:20',
            'phone'        => 'regex:(1)\d{10}',
            'email'        => 'email',
            'sex'          => 'in:0,1,2',
            'entry_date'   => 'dateFormat:Y-m-d'
        ]);
        if (!$validate->check($this->request->param())) {
            $this->error($validate->getError(), 102);
        }
        $result = (new AdminService())->edit($this->request->param());
        if($result < 0){
            $this->error('该账户已存在',104);
        }elseif($result > 0){
            $this->error('该账户不存在',104);
        }else{
            $this->success();
        }
    }

    /**
     * 添加部门
     * @throws ParamNotExistException
     */
    public function addDepart()
    {
        $name = trim($this->request->param('name'));
        if(!$name){
            throw new ParamNotExistException();
        }
        $depart_id = (new AdminService())->addDepart($name);
        $this->success(['depart_id'=>$depart_id,'name'=>$name]);
    }

    /**
     * 部门列表
     */
    public function departList()
    {
        $list = (new AdminService())->departList();
        $this->success($list);
    }
}