<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/15 0015
 * Email: <1401128990@qq.com>
 */

namespace app\common\service;

use app\common\model\ProductCategory;

class Product
{
    public function getProductCategory()
    {
        return ProductCategory::where('status',ProductCategory::STATUS_VALID)
            ->order('sort','asc')
            ->field('id,name,code,bar_code')
            ->select();
    }
}