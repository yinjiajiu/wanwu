<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/18 0018
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class AttributeOption extends Model
{
    //已删除
    const HAS_DELETE = 1;
    //未删除
    const NO_DELETE  = 0;
    // 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'attr_id'     => 'int',    //商品类别编号
        'name'        => 'string', //属性名称
        'sort'        => 'int',    //属性优先级
        'has_delete'  => 'int',    //是否已删除 0=>否，1=>是
    ];
}