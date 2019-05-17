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
    //无图片链接
    const NOT_SRC = 0;
    //有图片链接
    const IS_SRC  = 1;

    // 设置字段信息
    protected $schema = [
        'id'      => 'int',
        'attr_id' => 'int',    //商品类别编号
        'name'    => 'string', //属性名称
        'sort'    => 'int',    //属性优先级
        'has_src' => 'int',    //是否图片链接' 0=>否 1=>是
    ];
}