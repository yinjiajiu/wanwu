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
Route::group('file', function () {
    //文件删除
    Route::post('upload','File/upload');
    //本地垃圾文件删除
    Route::get('delete','File/delete');
});
Route::group('product', function () {
    Route::post('upload','Product/upload');
    //获取商品大类
    Route::rule('category','Product/category','GET|POST');
    //获取商品属性分类
    Route::rule('attribute','Product/attribute','GET|POST');
    //获取商品属性值
    Route::rule('attrValue','Product/attrValue','GET|POST');
});

Route::get('static', response()->code(404));