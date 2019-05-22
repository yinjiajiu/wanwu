<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/20 0020
 * Email: <1401128990@qq.com>
 */


namespace app\admin\controller;


use app\common\exception\InvalidParamException;
use app\common\exception\ParamNotExistException;
use app\common\service\AuthService;

class Auth extends BaseController
{
    /**
     * 权限列表
     */
    public function list()
    {
        $result = (new AuthService())->ruleList();
        $this->success($result);
    }

    /**
     * 添加权限
     */
    public function userAuth()
    {
        $ris = trim($this->request->param('rids'));
        $uid = trim($this->request->param('uid'));
        if(!$ris || !$uid ){
            throw new ParamNotExistException();
        }
        if(!is_string($ris) || !is_numeric($uid)){
            throw new InvalidParamException();
        }
        $result = (new AuthService())->userAuth($ris,$uid);
        if($result){
            $this->success();
        }else{
            $this->error('权限数据有误',105);
        }
    }

    /**
     * 查看他人权限
     */
    public function showAuth()
    {
        $uid = trim($this->request->param('uid'));
        if(!$uid || !is_numeric($uid)){
            throw new ParamNotExistException();
        }
        $result = (new AuthService())->showAuth($uid);
        if($result) {
            $this->success($result);
        }
    }
}