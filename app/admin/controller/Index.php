<?php
namespace app\admin\controller;

use app\common\exception\ParamNotExistException;
use app\common\service\AdminService;
use app\common\service\AuthService;

class Index extends BaseController
{
    public function login()
    {
       $account  = $this->request->param('account');
       $password = $this->request->param('password');
       if(!$account || !$password){
           throw new ParamNotExistException();
       }
       $host = $this->request->domain();
       $adminService = new AdminService();
       $admin = $adminService->login(trim($account),trim($password));
       if(is_numeric($admin)){
           if($admin < 0){
               $this->error('账号不存在',103);
           }elseif($admin > 0){
               $this->error('密码错误',104);
           }else{
               $this->error('该账号已被禁用',105);
           }
       }
       $admin['avatar'] = $admin['avatar'] ? $host.$admin['avatar'] : '';
       $token = $adminService->getTokenBuUid($admin['id']);
       $menu = (new AuthService())->getMenu($admin['id']);
       unset($admin['id']);
       $result = ['token'=>$token,'rules'=>$menu,'admin'=>$admin];
       $this->success($result);
    }
}
