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
            'old_password' => 'require|alphaDash|min:5|max:20',
            'new_password' => 'require|alphaDash|min:5|max:20',
        ]);
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
}
