<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/15 0015
 * Email: <1401128990@qq.com>
 */
namespace app\common\model;

use think\Model;

class UploadLog extends Model
{
    // 设置字段信息
    protected $schema = [
        'id'        => 'int',
        'path'      => 'string',  //文件存储路径
        'size'      => 'string',  //文件大小
        'ext'       => 'string',  //文件扩展名
        'old_name'  => 'string',  //文件原名
        'hash'      => 'string',  //文件hash值
        'date'      => 'int',     //Ymd日期
    ];
}