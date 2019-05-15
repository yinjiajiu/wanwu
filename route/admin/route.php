<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/12 0012
 * Email: <1401128990@qq.com>
 */
use think\facade\Route;

Route::get('/', function (){
   result(['phone'=>'18895625589','email'=>'1401128990@qq.com','dec'=>'@……@'],'200','联系我们');
});

Route::group('product', function () {
    Route::post('/upload','Product/upload');
    Route::get('/class','Product/class');
});


Route::get('static', response()->code(404));