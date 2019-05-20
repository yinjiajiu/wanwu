<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/17 0017
 * Email: <1401128990@qq.com>
 */
namespace app\common\exception;

class ParamNotExistException extends \Exception
{
    public function __construct(string $message = "必要参数缺失或为空")
    {
        parent::__construct($message, 102);
    }
}