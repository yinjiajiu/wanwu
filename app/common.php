<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * PHP格式化字节大小
 * @param number $size 字节数
 * @param string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function formatSize($size, $delimiter = '')
{
    $units = array('byte', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 * 生成树结构
 * @param $items
 * @return array
 */
function tree(array $items) :array
{
    $tree = array();
    foreach ($items as $item) {
        if (isset($items[$item['pid']])) {
            $items[$item['pid']]['son'][] = &$items[$item['id']];
        } else {
            $tree[] = &$items[$item['id']];
        }
    }
    return $tree;
}

/**
 * 生成一个订单号
 * @return string
 */
function OrderNum(){
    $order_number = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    return $order_number;
}