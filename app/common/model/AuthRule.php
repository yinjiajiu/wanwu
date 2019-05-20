<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/19 0019
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class AuthRule extends Model
{
    //禁用
    const INVALID = 0;
    //有效
    const VALID   = 1;

    protected $schema = [
        'id'        => 'int',
        'name'      => 'string',    //规则名称,格式 为【模块名/控制器名/方法名】或【自定义规则】,多个规则之间用,隔开即可
        'title'     => 'sring',     //规则中文名称
        'no'        => 'string',    //编号
        'pid'       => 'int',       //0为顶级菜单
        'type'      => 'int',       //如果type为1,condition字段就可以定义规则表达式。如定义{score}>5 and {score}<100 表示用户的分数在5-100之间时这条规则才会通过。（默认为1）
        'status'    => 'int',       //1为正常,0为禁用
        'condition' => 'string',    //规则表达式，不为空and type字段=1 会按照条件验证
    ];
}