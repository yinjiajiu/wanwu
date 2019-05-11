<?php
namespace app\api\controller;

class Index extends BaseController
{
    public function index()
    {
        $this->success(['y'=>'yinjiajiu']);

        $this->validate(['name'=>'gg','email'=>'gg'],[
            'name'  =>  'checkName:thinkphp',
            'email' =>  'email',
        ],[],true);
        $this->error('gg');
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
