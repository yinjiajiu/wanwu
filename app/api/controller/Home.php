<?php
namespace app\api\controller;
use app\common\service\ProductService;
class Home extends BaseController
{
    /**
     * 获取首页列表分类入口
     */
    public function index()
    {
        $result = (new ProductService)->getCategory('id as category_id,name,code,bar_code,img');
        $this->success([$result]);
    }
}
