<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/18 0018
 * Email: <1401128990@qq.com>
 */


namespace app\common\model;


use think\Model;

class AttributeRelate extends Model
{

    // 设置字段信息
    protected $schema = [
        'id'         => 'int',
        'pid'        => 'int',  //关联商品id
        'attr_id'    => 'int',  //关联属性id
        'option_id'  => 'int',  //关联属性选项id
        'path'       => 'string'//文件路径
    ];
}