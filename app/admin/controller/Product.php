<?php
namespace app\admin\controller;
use app\common\service\ProductService;
use think\Facade\App;
class Product extends BaseController
{
    /**
     * 上传商品
     */
    public function upload()
    {

    }

    public function class()
    {
        $result = (new ProductService)->getProductCategory('id,name');
        $this->success($result);
    }
}
