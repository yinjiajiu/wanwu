<?php
/**
 *
 * User: yinjiajiu(尹家久)
 * Date: 2019/5/12 0012
 * Email: <1401128990@qq.com>
 */
use think\facade\Route;
Route::group('',function(){
    /**
     * 初始默认路由
     */
    Route::get('/', function (){
        result(['phone'=>'18895625589','email'=>'1401128990@qq.com','dec'=>'@……@'],'200','联系我们');
    });

    /**
     * 首页操作相关
     */
    Route::group('index', function () {
        //登录
        Route::rule('login','Index/login','POST|OPTIONS');
    });

    /**
     * 权限操作相关
     */
    Route::group('auth', function () {
        //权限列表
        Route::rule('list','Auth/list','GET|POST');
        //添加权限
        Route::post('add','Auth/add');
        //个人权限详情
        Route::post('show','Auth/showAuth');
    });


    /**
     * 文件操作相关
     */
    Route::group('file', function () {
        //文件删除
        Route::post('upload','File/upload');
        //本地垃圾文件删除
        Route::get('delete','File/delete');
    });

    /**
     * 管理人员相关
     */
    Route::group('user', function () {
        //展示管理人员列表
        Route::rule('list','User/list','GET|POST');
        //删除管理人员
        Route::post('delete','User/delete');
        //添加管理人员
        Route::post('add','User/add');
        //修改管理人员
        Route::post('edit','User/edit');
        //添加部门
        Route::rule('addDepart','User/addDepart','GET|POST');
        //部门列表
        Route::rule('departList','User/departList','GET|POST');
    });

    /**
     * 商户操作相关
     */
    Route::group('business', function () {
        //展示商户列表
        Route::rule('list','Business/list','GET|POST');
        //添加供应商
        Route::post('add','Business/add');
        //删除供应商
        Route::rule('delete','Business/delete','GET|POST');
        //重置供应商密码
        Route::post('reset','Business/reset');
        //修改供应商
        Route::post('edit','Business/edit');
        //添加供应商编码
        Route::post('addCode','Business/addCode');
        //修改供应商编码
        Route::post('editCode','Business/editCode');
        //删除供应商编码
        Route::post('deleteCode','Business/deleteCode');
        //供应商编码列表
        Route::rule('codeList','Business/codeList','GET|POST');

    });

    /**
     * 商品操作相关
     */
    Route::group('product', function () {
        //获取商品大类
        Route::rule('category','Product/category','GET|POST');
        //获取商品属性分类
        Route::rule('attribute','Product/attribute','GET|POST');
        //添加商品属性分类
        Route::rule('addAttr','Product/addAttr','GET|POST');
        //获取商品属性值
        Route::rule('attrValue','Product/attrValue','GET|POST');
        //添加商品属性值
        Route::rule('addOption','Product/addOption','GET|POST');
        //商品列表
        Route::any('list','Product/list');
        //发布商品
        Route::post('publish','Product/publish');
        //修改商品
        Route::post('edit','Product/edit');
        //单个商品详情
        Route::rule('detail','Product/detail','GET|POST');
        //修改商品状态
        Route::post('change','Product/change');
    });
    Route::get('static', response()->code(404));
})->allowCrossDomain([
    'Access-Control-Allow-Origin'      => '*',
    'Access-Control-Allow-Credentials' => 'false',
    'Access-Control-Allow-Methods'     => 'GET, POST, PATCH, PUT, DELETE,OPTIONS',
    'Access-Control-Allow-Headers'     => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With ,token',
]);
