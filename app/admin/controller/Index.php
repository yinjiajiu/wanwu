<?php
namespace app\admin\controller;

class Index extends BaseController
{
    public function index()
    {
        return json(['y'=>'yinjiajiu']);
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
