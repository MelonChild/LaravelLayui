<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//api 路由租 prefix api/ptec  中间件  PtecApiVerify
Route::group(["prefix" => "api/ptec","middleware"=>['PTEApiVerify']], function () {
   Route::match(["get", "post"],'getcollege', 'CollegeController@index');//院校控制器
   Route::get('getcountry', 'CountryController@index');//查询地区
   Route::post('saveUserInfo', 'MailController@saveUserInfo');//提交的信息  
});
 
 //采集路由
    Route::get('collect/index',"CollectController@start");

