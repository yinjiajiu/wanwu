<?php
namespace app\admin\controller;
use app\common\service\FileService;
class Index extends BaseController
{
    public function hello($name = 'ThinkPHP6')
    {
        return 'hellggggggggo,' . $name;
    }
}
