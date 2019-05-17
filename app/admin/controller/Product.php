<?php
namespace app\admin\controller;
use app\common\exception\InvalidParamException;
use app\common\service\ProductService;
class Product extends BaseController
{
    /**
     * 商品大类
     */
    public function category()
    {
        $result = (new ProductService)->getCategory('id as category_id,name');
        $this->success($result);
    }

    /**
     * 商品属性
     */
    public function attribute()
    {
        $category_id = $this->request->param('category_id',0);
        if(!is_numeric($category_id)){
            throw new InvalidParamException('分类id必须为整数');
        }
        $result = (new ProductService)->getAttribute($category_id);
        $this->success($result);
    }

    /**
     * 商品属性值
     */
    public function attrValue()
    {
        $attribute_id = $this->request->param('attribute_id');
        if(!$attribute_id || !is_numeric($attribute_id)){
            throw new InvalidParamException('属性必传且必须为整数');
        }
        $result = (new ProductService)->getAttrValue($attribute_id);
        $this->success($result);
    }

}
