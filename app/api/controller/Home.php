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
        $result = (new ProductService)->getProductCategory('id,name,code,bar_code,img');
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
