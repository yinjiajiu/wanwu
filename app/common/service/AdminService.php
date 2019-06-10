<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\common\service;


use app\common\model\Admin;
use app\common\model\AdminDepart;
use app\common\model\AdminToken;
use think\facade\Db;

class AdminService
{
    /**
     * 后台人员登录
     * @param string $account
     * @param string $password
     */
    public function login( string $account ,string $password)
    {
        $admin = Admin::where('account',$account)
            ->field('id,account,password,name,avatar,phone,email,sex,depart_name,status,desc,entry_date')
            ->find();
        if(!$admin) return -1;
        if($admin->status === Admin::INVALID) return 0;
        if(!password_verify($password,$admin->password)) return 1;
        unset($admin->password,$admin->status);
        return $admin;
    }

    /**
     * 生成token
     * @param int $uid
     * @return string
     */
    public function getTokenBuUid( int $uid) :string
    {
        $token = bin2hex(random_bytes(10));
        $adminToken = AdminToken::where('uid',$uid)->find();
        if($adminToken){
            $adminToken->token  = $token;
            $adminToken->expire = time() + AdminToken::EXPIRE;
            $adminToken->count += 1;
            $adminToken->save();
        }else{
            AdminToken::create([
                'uid'   => $uid,
                'token' => $token,
                'expire'=> time() + AdminToken::EXPIRE,
                'count' => 1,
                'update_time' => date('Y-m-d H:i:s'),
            ]);
        }
        return $token;
    }

    /**
     * 获取管理员列表
     * @param array $where
     */
    public function getList(array $where = [])
    {
        return Admin::where($where)
            ->field('id as uid,name,account,sex,phone,email,entry_date,depart_name,status,desc')
            ->select();
    }

    /**
     * 删除管理人员
     * @param int $id
     */
    public function delete(int $id) :bool
    {
        $user = Admin::find($id);
        if(!$user){
            return false;
        }
        $user->status = Admin::INVALID;
        $user->save();
        return true;
    }

    /**
     * 添加管理人员
     * @param array $param
     * @return bool
     */
    public function add(array $param) :bool
    {
        $user = Admin::where('account',$param['account'])->findOrEmpty();
        if (!$user->isEmpty()) {
            return false;
        }
        if(isset($param['password'])){
            $param['password'] = password_hash(trim($param['password']),PASSWORD_DEFAULT);
        }else{
            $param['password'] = password_hash(trim($param['account']),PASSWORD_DEFAULT);
        }
        $param['create_time'] = $param['update_time'] = date('Y-m-d H:i:s');
        $param['status'] = Admin::VALID;
        Admin::create($param,['account','password','name','phone',
            'email','sex','status','depart_name','level','desc','entry_date','create_time','update_time']);
        return true;
    }

    /**
     * 添加管理人员
     * @param array $param
     * @return bool
     */
    public function edit(array $param) 
    {
        if(isset($param['account'])){
            $param['account'] = trim($param['account']);
            $exist = Admin::where('account',$param['account'])->where('id','!=',$param['uid'])->count();
            if($exist) return -1;
        }
        $user = Admin::findOrEmpty($param['uid']);
        if ($user->isEmpty()) {
            return 1;
        }
        if(isset($param['password'])){
            $param['password'] = password_hash(trim($param['password']),PASSWORD_DEFAULT);
        }
        unset($param['uid']);
        $user->save($param);
        return 0;
    }

    /**
     * 添加部门
     * @param string $name
     * @return int
     */
    public function addDepart(string $name) : int
    {
        $depart = AdminDepart::create(['name' => $name,'status'=>AdminDepart::VALID]);
        return $depart->id;
    }

    /**
     *获取部门列表
     */
    public function departList()
    {
        return AdminDepart::fetchArray()
            ->field('id as depart_id,name')
            ->where('status',AdminDepart::VALID)
            ->select();
    }
}