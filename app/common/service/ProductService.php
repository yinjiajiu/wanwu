<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/15 0015
 * Email: <1401128990@qq.com>
 */

namespace app\common\service;

use app\common\model\ProductCategory;
use app\common\model\ProductAttribute;
use app\common\model\AttributeOption;

class ProductService
{
    /**
     * 获取商品大类
     * @param string $field
     * @return object
     */
    public function getCategory(string $field) :object
    {
        return ProductCategory::where('status',ProductCategory::STATUS_VALID)
            ->order('sort','asc')
            ->field($field)
            ->select();
    }

    /**
     * 获取商品属性
     * @param int $category_id
     * @param string $asc
     * @return object
     */
    public function getAttribute(int $category_id ,string $asc = 'asc') :object
    {
        return ProductAttribute::where('category_id',$category_id)
            ->field('id as attribute_id,category_id,name,is_sale')
            ->order('sort',$asc)
            ->select();
    }

    /**
     * 获取商品属性
     * @param int $category_id
     * @param string $asc
     * @return object
     */
    public function getAttrValue(int $attribute_id ,string $asc = 'asc') :object
    {
        return AttributeOption::where('attr_id',$attribute_id)
            ->field('id as option_id,name,has_src')
            ->order('sort',$asc)
            ->select();
    }
}