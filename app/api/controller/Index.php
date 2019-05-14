<?php
namespace app\api\controller;
use app\common\service\Product;
class Index extends BaseController
{
    /**
     * 获取首页列表分类入口
     */
    public function index()
    {
        $result = (new Product)->getProductCategory();
        $this->success([$result]);
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }

    public function idx()
    {
        $this->success(['y'=>$this->request->get()]);
    }
}
