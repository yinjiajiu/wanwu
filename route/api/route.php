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

/**
 * 商品系列
 */
Route::group('product', function () {
    //商品列表
    Route::rule('list','Product/list','GET|POST');
    //商品详情
    Route::rule('detail','Product/detail','GET|POST');
    //商品属性
    Route::rule('option','Product/option','GET|POST');
    //单个商品基础信息
    Route::rule('baseInfo','Product/baseInfo','GET|POST');
});

/**
 * 交易系列
 */
Route::group('order', function () {
    //添加到购物车
    Route::post('cardAdd','Order/cardAdd');
    //修改购物车数据
    Route::post('cardEdit','Order/cartEdit');
    //购物车列表
    Route::post('cartList','Order/cartList');
    //删除购物车
    Route::post('cartDelete','Order/cartDelete');
    //购物车数量++
    Route::post('increase','Order/increase');
});

/**
 * 商户系列
 */
Route::group('business', function () {
    //添加到购物车
    Route::post('login','Business/login');
});