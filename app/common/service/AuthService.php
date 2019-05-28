<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\common\service;

use app\common\model\AuthGroupAccess;
use app\common\model\AuthRule;

class AuthService
{
    /**
     * 登录获取权限菜单
     * @param int $uid
     * @return array|mixed|\think\Collection|\think\model\Collection
     */
    public function getMenu(int $uid)
    {
        $rules = AuthGroupAccess::where('uid',$uid)->value('rules');
        if(!$rules) return [];
        $where[] = ['status','=',AuthRule::VALID];
        if($rules !== '*'){
            $where[] = ['id','in', $rules];
        }
        $rules = AuthRule::where($where)
            ->field('id,title,no,pid')
            ->order('id')
            ->fetchArray()
            ->select();
        //var_dump($rules);exit;
        $rule = [];
        foreach ($rules as $item) {
            if (isset($rule[$item['pid']])) {
                $rule[$item['pid']]['son'][] = $item;
            } else {
                $rule[$item['id']] = $item;
            }
        }
        return array_values($rule);
    }

    /**
     * 获取权限列表
     */
    public function ruleList()
    {
        return AuthRule::where('status',AuthRule::VALID)
            ->column('id,title,no,pid','id');
    }

    /**
     * 添加权限
     */
    public function userAuth(string $ris ,int $uid)
    {
        if($ris != '*'){
            $list = $this->ruleList();
            if(!$list) return false;
            $rds = array_column($list,'id');
            $rs =  explode(',',$ris);
            if(array_diff($rs,$rds)){
                return false;
            }
        }
        $auth = AuthGroupAccess::where('uid',$uid)->findOrEmpty();
        if($auth->isEmpty()){
            $auth         = new AuthGroupAccess;
            $auth->uid    = $uid;
            $auth->rules  = $ris;
        }else{
            $auth->rules = $ris;
        }
        $auth->save();
        return true;
    }

    /**
     * 显示权限
     * @param int $uid
     */
    public function showAuth(int $uid)
    {
        $items = $this->ruleList();
        $rules = AuthGroupAccess::where('uid',$uid)->value('rules');
        $rule = [];
        if($rules == '*'){
            foreach($items as $item){
                $item['checked'] = true;
                if (isset($rule[$item['pid']])) {
                    $rule[$item['pid']]['son'][] = $item;
                } else {
                    $rule[$item['id']] = $item;
                }
            }
        }else {
            $rules = explode(',', $rules);
            foreach ($items as $item) {
                $item['checked'] = in_array($item['id'], $rules) ? true : false;
                if (isset($rule[$item['pid']])) {
                    $rule[$item['pid']]['son'][] = $item;
                } else {
                    $rule[$item['id']] = $item;
                }
            }
        }
        return array_values($rule);
    }

}