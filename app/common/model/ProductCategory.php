<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/15 0015
 * Email: <1401128990@qq.com>
 */
namespace app\common\model;

use think\Model;

class ProductCategory extends Model
{
    //无效分类
    const STATUS_INVALID = 0;
    //有效分类
    const STATUS_VALID   = 1;
    // 设置字段信息
    protected $schema = [
        'id'          => 'int',
        'name'        => 'string',  //分类名称
        'pid'         => 'int',     //0=>为最高分类 1=>为二级分类  数字越大,分类越后
        'code'        => 'string',  //分类码
        'sort'        => 'int',     //类别优先级
        'bar_code'    => 'string',  //二维码地址
        'img'         => 'string',  //首页展示图
        'status'      => 'int',     //分类状态 0=>禁用，1=>启用
        'create_time' => 'string',
        'update_time' => 'string',
    ];
}