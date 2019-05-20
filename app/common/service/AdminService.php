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
            ->field('id,account,password,name,avatar,phone,email,sex,depart')
            ->find();
        if(!$admin) return false;
        if(!password_verify($password,$admin->password)) return false;
        unset($admin->password);
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
        $where[] = ['a.status','=',Admin::VALID];
        $result = Db::table('wu_admin')
            ->alias('a')
            ->leftJoin('wu_admin_depart d','a.depart_id = d.id' )
            ->where($where)
            ->field('a.id as uid,a.name,a.account,a.sex,a.phone,a.email,a.entry_date,d.name as depart')
            ->select();
        return $result;
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
        Admin::create($param,['account','password','name','avatar','phone',
            'email','sex','status','depart_id','level','desc','entry_date','create_time','update_time']);
        return true;
    }

    /**
     * 添加管理人员
     * @param array $param
     * @return bool
     */
    public function edit(array $param) :bool
    {
        $user = Admin::findOrEmpty($param['aid']);
        if ($user->isEmpty()) {
            return false;
        }
        if(isset($param['password'])){
            $param['password'] = password_hash(trim($param['password']),PASSWORD_DEFAULT);
        }
        $user->save($param);
        return true;
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