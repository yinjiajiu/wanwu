<?php
namespace app\common\handle;

use think\Response;
use Throwable;
use think\exception\Handle;

class CustomerHandle extends Handle
{
    public function render($request, Throwable $e)
    {
        if(!$this->app->isDebug()){
            if($e->getCode()){
                $data['code'] = $e->getCode();
                $data['msg']  = $e->getMessage();
            }else{
                $data['code'] = 500;
                $data['msg']  = '服务器异常';
            }
            return Response::create($data, 'json' );

        }
        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}