<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\common\service;


use app\common\model\AuthGroup;
use app\common\model\AuthGroupAccess;
use app\common\model\AuthRule;

class AuthService
{
    public function getMenu(int $uid)
    {
        $group_id = AuthGroupAccess::where('uid',$uid)->value('group_id');
        if(!$group_id) return [];
        $rules = AuthGroup::where('id',$group_id)
            ->where('status',AuthGroup::VALID)
            ->value('rules');
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
}