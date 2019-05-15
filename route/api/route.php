<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/12 0012
 * Email: <1401128990@qq.com>
 */
use think\facade\Route;

Route::rule('/', function (){
    result(['phone'=>'18895625589','email'=>'1401128990@qq.com','dec'=>'@……@'],'200','联系我们');
});


Route::get('home/index', 'Home/index');

//Route::get('hello', 'Index/hello');
Route::get('/<ii?>', 'Index/idx');
Route::get('hello/:name', function ($name) {
    return 'Hello,' . $name;
});