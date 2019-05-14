<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/12 0012
 * Email: <1401128990@qq.com>
 */
use think\facade\Route;

Route::get('/', function (){
    return response('hello world');
});

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('index', 'api/index/index');