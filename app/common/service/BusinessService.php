<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\common\service;


use app\common\model\Business;
use app\common\model\BusinessCode;
use think\facade\Db;

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
        $where[] = ['status','=',Business::VALID];
        return Business::where($where)
            ->field('id as bid,merchant,account,name,phone,sex,age,email,area,address,create_time')
            ->limit($offset,$ps)
            ->order('id',$sort)
            ->select();
    }

    /**
     * 供应商总数
     */
    public function listTotal(array $where)
    {
        $where[] = ['status','=',Business::VALID];
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
        if(isset($param['account'])) {
            $param['account'] = trim($param['account']);
            $exist = Business::where('account', trim($param['account']))
                ->where('id', '<>', $param['bid'])
                ->findOrEmpty();
            if (!$exist->isEmpty()) {
                return -1;
            }
        }
        $exist = Business::findOrEmpty($param['bid']);
        if ($exist->isEmpty()) {
            return 0;
        }
        if(!empty($param['password'])){
            $param['password'] = password_hash(trim($param['password']),PASSWORD_DEFAULT);
        }
        $exist->save($param);
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
        if($password){
            $business->password = password_hash($password,PASSWORD_DEFAULT);
        }else{
            $business->password = password_hash($business->account,PASSWORD_DEFAULT);
        }
        $business->save();
        return true;
    }

    /**
     * 添加供应商编码
     * @param array $param
     * @return bool
     */
    public function addCode(array $param) :bool
    {
        $code= BusinessCode::where('code',$param['code'])->findOrEmpty();
        if (!$code->isEmpty()) {
            return false;
        }
        $param['status'] = BusinessCode::VALID;
        $param['create_time'] = $param['update_time'] = date('Y-m-d H:i:s');
        BusinessCode::create($param,['bid','desc','code','class','address','status','create_time','update_time']);
        return true;
    }

    /**
     * 删除供应商编码
     * @param int $cid
     */
    public function deleteCode(int $cid)
    {
        BusinessCode::destroy($cid);
    }

    /**
     * 修改供应商编码
     * @param int $cid
     */
    public function editCode(array $param) :int
    {
        $code = BusinessCode::where('code',$param['code'])
            ->where('id','<>',$param['cid'])
            ->findOrEmpty();
        if (!$code->isEmpty()) {
            return -1;
        }

        $code = BusinessCode::findOrEmpty($param['cid']);
        if ($code->isEmpty()) {
            return 0;
        }
        $code->allowField(['bid', 'code','desc','status','status','class','address'])->save($_POST);
        return 1;
    }

    /**
     * 供应商编码列表
     * @param array $where
     * @param int $offset
     * @param int $ps
     */
    public function codeList(array $where, int $offset , int $ps )
    {
        $result = Db::table('wu_business_code')->alias('c')
            ->leftJoin('wu_business b','b.id = c.bid')
            ->field('c.id as cid,b.merchant,c.code,c.bid,c.status,b.name,b.phone,c.create_time')
            ->where($where)
            ->order('c.id','desc')
            ->limit($offset,$ps)
            ->select();
        return $result;
    }

    /**
     * 供应商编码数量
     * @param $where
     * @return int
     */
    public function codeTotal($where)
    {
        return  Db::table('wu_business_code')
            ->alias('c')
            ->where($where)
            ->count('id');
    }

    /**
     * api登录
     * @param string $account
     * @param string $password
     * @return array
     */
    public function login(string $account, string $password)
    {
        $business = Business::where('account',$account)
            ->field('id as bid,merchant,account,name,phone,sex,age,email,area,address,status,password')
            ->findOrEmpty();
        if($business->isEmpty()) {
            return ['error'=>true,'result'=>'账号不存在'];
        }
        if($business->status == Business::INVALID ){
            return ['error'=>true,'result'=>'该账号已被禁用，请联系厂家'];
        }
        if(password_verify($password,$business->password)){
            unset($business->password,$business->status);
            return ['error'=>false,'result'=>$business];
        }else{
            return ['error'=>true,'result'=>'密码错误'];
        }
    }

    /**
     * api修改密码
     * @param $bid
     * @param $old
     * @param $new
     */
    public function change(int $bid,string $old,string $new) :int
    {
        $business = Business::findOrEmpty($bid);
        if($business->isEmpty()){
            return -1;
        }
        if(password_verify($old,$business->password)){
            $business->password = password_hash($new,PASSWORD_DEFAULT);
            $business->save();
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 修改用户信息
     */
    public function editInfo(array $param)
    {
        $business = Business::find($param['bid']);
        $business->allowField(['name','phone', 'area','address'])->save($param);
    }

    /**
     * 获取供应商编码
     */
    public function code(int $bid)
    {
        return BusinessCode::where('status',BusinessCode::VALID)
            ->where('bid',$bid)
            ->field('code,address')
            ->select();
    }

    /**
     * 查询商户状态
     */
    public function check(int $bid) :array {
        $business = Business::findOrEmpty($bid);
        if($business->isEmpty()){
            return ['error' => true,'result' => '商户不存在'];
        }elseif(!$business->status){
            return ['error' => true,'result' => '该商户已经被禁用'];
        }
        return ['error' => false];
    }
}