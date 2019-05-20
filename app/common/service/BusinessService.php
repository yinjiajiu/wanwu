<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\common\service;


use app\common\model\Business;

class BusinessService
{
    /**
     * 获取供应商列表
     * @param array $where
     * @param int $offset
     * @param int $ps
     * @param string $sort
     */
    public function getList(array $where, int $offset , int $ps ,string $sort = 'asc')
    {
        return Business::where($where)
            ->field('merchant,account,name,phone,sex,age,email,address,create_time')
            ->limit($offset,$ps)
            ->order('id',$sort)
            ->select();
    }

    /**
     * 供应商总数
     */
    public function listTotal(array $where)
    {
        return Business::where($where)->count('id');
    }

    /**
     * 添加供应商
     * @param array $param
     * @return bool
     */
    public function addBusiness(array $param)
    {
        $account = trim($param['account']);
        $user = Business::where('account',$account)->findOrEmpty();
        if (!$user->isEmpty()) {
            return false;
        }
        if(empty($param['password'])){
            $param['password'] = password_hash($account,PASSWORD_DEFAULT);
        }else{
            $param['password'] = password_hash(trim($param['password']),PASSWORD_DEFAULT);
        }
        $param['account'] = $account;
        $param['name'] = trim($param['name']);
        $param['status'] = Business::VALID;
        $param['create_time'] = $param['update_time'] = date('Y-m-d H:i:s');
        Business::create($param, [
            'merchant', 'account','password','name','phone','avatar','sex','age',
            'email','area','address','status','create_time','update_time'
        ]);
        return true;
    }

    public function editBusiness($param)
    {
        $count = Business::where('account',trim($param['account']))->count();
        if($count > 1){
            return -1;
        }
        $business = Business::findOrEmpty($param['bid']);
        if ($business->isEmpty()) {
            return 0;
        }
        if(isset($param['password'])){
            $param['password'] = password_hash(trim($param['password']),PASSWORD_DEFAULT);
        }
        $business->save($param);
        return 1;
    }

    /**
     * 删除供应商
     */
    public function deleteBusiness(int $bid)
    {
        $business = Business::find($bid);
        if(!$business){
            return false;
        }
        $business->status = Business::INVALID;
        $business->save();
        return true;
    }

    /**
     * 重置密码
     */
    public function resetPass(int $bid,string $password)
    {
        $business = Business::findOrEmpty($bid);
        if ($business->isEmpty()) {
            return false;
        }
        if(!$password){
            $business->password = password_hash($business->account,PASSWORD_DEFAULT);
        }else{
            $business->password = password_hash($password,PASSWORD_DEFAULT);
        }
        $business->save();
        return true;
    }
}