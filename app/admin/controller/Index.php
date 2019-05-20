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
       $adminService = new AdminService();
       $admin = $adminService->login(trim($account),trim($password));
       if(!$admin){
           $this->error('账号或密码错误',303);
       }
       $token = $adminService->getTokenBuUid($admin['id']);
       $menu = (new AuthService())->getMenu($admin['id']);
       unset($admin['id']);
       $result = ['token'=>$token,'rules'=>$menu,'admin'=>$admin];
       $this->success($result);
    }
}
