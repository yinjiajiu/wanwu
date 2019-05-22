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
        $where[] = ['pid','=',0];
        if($rules !== '*'){
            $where[] = ['id','in', $rules];
        }
        $rules = AuthRule::where($where)
            ->field('title,no')
            ->order('id')
            ->select();
        return $rules;
    }

    /**
     * 获取权限列表
     */
    public function ruleList(int $pid = 0)
    {
        return AuthRule::where('pid',$pid)
            ->where('status',AuthRule::VALID)
            ->field('id as rid,title,no')
            ->select();
    }

    /**
     * 添加权限
     */
    public function userAuth(string $ris ,int $uid)
    {
        $list = $this->ruleList();
        foreach ($list  as $v) {
            $rds[] = $v->rid;
        }
        $rs =  explode(',',$ris);
        if(array_diff($rs,$rds)){
            return false;
        }
        $auth = AuthGroupAccess::where('uid',$uid)->findOrEmpty();
        if($auth->isEmpty()){
            $auth         = new AuthGroupAccess;
            $auth->uid    = $uid;
            $auth->rules  = $ris;
            $auth->save();
        }else{
            $auth->rules = $ris;
        }
        return true;
    }

    /**
     * 显示权限
     * @param int $uid
     */
    public function showAuth(int $uid)
    {
        $list = $this->ruleList();
        $rules = AuthGroupAccess::where('uid',$uid)->value('rules');
        $rule = [];
        if($rules == '*'){
            foreach($list as $v){
                $rule[] = [
                    'rid'    => $v->rid,
                    'no'     => $v->no,
                    'title'  => $v->title,
                    'checked'=>true
                ];
            }
        }else{
            $rules = explode(',',$rules);
            foreach($list as $v){

                $rule[] = [
                    'rid'    => $v->rid,
                    'no'     => $v->no,
                    'title'  => $v->title,
                    'checked'=> in_array($v->rid,$rules) ? true : false
                ];
            }
        }
        return $rule;
    }

}