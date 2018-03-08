<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//api 路由租 prefix api/ptec  中间件  PtecApiVerify
Route::group(["prefix" => "api/news"], function () {
    Route::group(["middleware" => ['ApiVerify']], function () {
        //获取轮播信息
        Route::post('getAppInitData', 'IndexController@getAppInitData');
        //获取新闻
        Route::post('getNews', 'IndexController@getNews');
    });
    //获取新闻详情
    Route::get('getNewsDetail/{id}', 'IndexController@getNewsDetail');
});

