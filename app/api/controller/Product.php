<?php
namespace app\api\controller;
class Product extends BaseController
{
    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }

    public function idx()
    {
        $this->success(['y'=>$this->request->get()]);
    }
}
