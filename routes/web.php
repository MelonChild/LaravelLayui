<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */





Route::group([], function () {

    //极验验证
    Route::get('geetest', 'LoginController@geetest')->name('index.geetest');
    Route::post('verifyGt', 'LoginController@verifyGeetest')->name('index.verifyGeetest');


    //登录首页
    Route::get('login', 'LoginController@login')->name('index.login');
    Route::get('test', 'LoginController@test')->name('index.test');

    //登出
    Route::get('logout', 'LoginController@logout')->name('index.logout');

    //上传
    Route::post('uploadCropPic', 'ArticleController@uploadCropPic')->name('index.upload');

    //后台
    Route::group(['middleware' => ['userAuth']], function () {
        //首页
        Route::get('/', 'IndexController@index')->name('index.index');

        //主页
        Route::get('dashboard', 'IndexController@dashboard')->name('index.dashboard');

        //菜单栏
        Route::get('navs', 'IndexController@navs')->name('index.navs');

        //管理员信息
        Route::match(['get', 'post'], 'info', 'UserController@info')->name('index.user.info');
        Route::get('pwd', 'UserController@pwd')->name('index.user.pwd');
        Route::post('checkUsername', 'UserController@checkUsername')->name('index.user.checkUsername');



        //上传头像
        Route::post('avatarStore', 'UserController@avatarStore')->name('index.user.avatarStore');

        //生成图片
        Route::post('generatePic', 'PictureController@generatePic')->name('index.picture.generatePic');


        //资讯
        Route::resource('article', 'ArticleController', ['as' => 'index']);

        //资讯
        Route::resource('banner', 'BannerController', ['as' => 'index']);

        //用户管理
        Route::resource('user', 'UserController', ['as' => 'index']);
    });
});

