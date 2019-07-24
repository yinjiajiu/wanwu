<?php
namespace app\api\controller;

use app\common\exception\InvalidParamException;
use app\common\exception\ParamNotExistException;
use app\common\service\BusinessService;
use think\facade\Validate;

class Business extends BaseController
{
    /**
     * 登录
     */
    public function login()
    {
        $account = $this->request->param('account');
        $password = $this->request->param('password');
        if(!$account || !$password){
            throw new ParamNotExistException();
        }
        $result = (new BusinessService())->login($account,$password);
        if($result['error']){
            $this->error($result['result'],104);
        }
        $this->success($result['result']);
    }

    /**
     * 修改密码
     */
    public function change()
    {
        $validate = Validate::rule([
            'bid'      => 'require|number',
            'old_password' => 'require',
            'new_password' => 'require|alphaDash|min:5|max:20',
        ])->message([
                'new_password.alphaDash' => '新密码必须是数字，字母下划线',
                'new_password.min' => '新密码长度不能低于5位',
                'new_password.max' => '新密码长度不能长于20位',
            ]
        );
        if (!$validate->check($this->request->param())) {
            $this->error($validate->getError(), 102);
        }
        $bid = $this->request->param('bid');
        $old = trim($this->request->param('old_password'));
        $new = trim($this->request->param('new_password'));
        $result = (new BusinessService())->change($bid,$old,$new);
        if($result < 0){
            $this->error('用户不存在',105);
        }elseif($result > 0){
            $this->success();
        }else{
            $this->error('原密码错误',104);
        }
    }

    /**
     * 修改商户相关信息
     */
    public function editInfo()
    {
        $validate = Validate::rule([
            'bid'      => 'require|number',
            'phone'    => 'regex:(1)\d{10}',
            'address'  => 'require',
        ]);
        if (!$validate->check($this->request->param())) { 
            $this->error($validate->getError(), 102);
        }
        (new BusinessService())->editInfo($this->request->param());
        $this->success();
    }

    /**
     * 获取供应商所有编码
     */
    public function code()
    {
        $bid = $this->request->param('bid');
        if(!$bid){
            throw new ParamNotExistException();
        }
        if(!is_numeric($bid)){
            throw new InvalidParamException();
        }
        $result = (new BusinessService())->code($bid);
        $this->success($result);
    }

    /**
     * 判断商户状态
     */
    public function check()
    {
        $bid = $this->request->param('bid');

        if(!$bid || !is_numeric($bid)){
            throw new InvalidParamException();
        }
        $result = (new BusinessService())->check($bid);
        if($result['error']){
            $this->error($result['msg'],104);
        }
        $this->success();
    }
}
