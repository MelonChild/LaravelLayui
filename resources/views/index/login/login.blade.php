<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>登录--课窝小程序</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="format-detection" content="telephone=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="/statics/layui/css/layui.css" media="all" />
        <link href="/index/login/css/bootstrap.min.css" rel="stylesheet">
        <link href="/index/login/css/style.min.css" rel="stylesheet">
        <link href="/index/login/css/login.min.css" rel="stylesheet">      
        <link href="/index/login/js/supersized.css" rel="stylesheet">
        <link href="/index/images/favicon.ico" mce_href="/index/images/favicon.ico" rel="bookmark" type="image/x-icon" /> 
        <link href="/index/images/favicon.ico" mce_href="/index/images/favicon.ico" rel="icon" type="image/x-icon" /> 
        <link href="/index/images/favicon.ico" mce_href="/index/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    </head>
    <body>
        <div id="app">
            <div id="video-div"  v-bind:class="{'video-div-show':videoBg}">
                <video class="video-player"   preload="auto" autoplay="autoplay" loop="loop" data-height="1080" data-width="1920" height="1080" >
                    <source src="/index/login.mp4" type="video/mp4">
                </video>
            </div>
            <div v-bind:class="{'video_mask':videoBg}"></div>
            <div class="login">
                <div class="signinpanel">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="signin-info" style="color:#ffffff">
                                <div class="logopanel m-b">
                                    <h1>[ 课窝小程序 ]</h1>
                                </div>
                                <div class="m-b"></div>
                                <h3>欢迎使用Melon管理系统</h3>
                                <ul class="m-b">
                                    <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 管理更便捷</li>
                                    <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 统计更全面</li>
                                    <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 界面更美观</li>
                                    <li><i class="fa fa-arrow-circle-o-right m-r-xs"></i> 操作更方便</li>
                                </ul>
                                <strong>验证码不显示，请刷新页面</strong>

                            </div>
                        </div>

                        <div class="col-sm-5" style="color:#ffffff">
                            <form id="runlogin" name="runlogin">

                                <p class="m-t-md">课窝小程序--登录</p>
                                <input type="text" class="form-control uname" name="admin_username" id="admin_username" placeholder="用户名" />
                                <input type="password" class="form-control pword m-b" name="admin_pwd" id="admin_pwd" placeholder="密码" />
                                <!--  <input type="text" name="code" class="form-control pword" id="code" placeholder="验证码" oncontextmenu="return false" onpaste="return false"  style="width: 60%;" />
                                                <img class="verifyimg reloadverify" alt="点击切换" src="{:U('verify')}" height="35px"; style="margin-top:-35px;width:80px;float:right;border:1px solid #cececf; " />
                                <br/> -->

                                <div id="embed-captcha"></div>
                                <p id="wait" class="show">正在加载验证码......</p>
                                <p id="notice" class="hide">请先完成验证</p>

                                <br/>
                                <!--<a href="" data-toggle="modal" data-target="#forget">忘记密码了？</a>                -->
                                <button id="embed-submit" type="button" class="btn btn-primary btn-block">登&nbsp;&nbsp;&nbsp;录</button>															
                            </form>
                        </div>
                    </div>
                    <div class="signup-footer">
                        <div class="pull-left" style="color:#ffffff">
                            Melon管理系统 Copyright © 2018
                        </div>
                        <div class="layui-form-item pull-right bg-choose">
                            <div class="layui-input-block">
                                <div v-on:click="changeBg" id="checkboxDiv" class="layui-unselect layui-form-checkbox layui-form-checked"  lay-skin="">
                                    <span>video</span><i class="layui-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var verifyGeetestUrl = "{{route('index.verifyGeetest')}}",
                    geetestUrl = "{{route('index.geetest')}}",
                    dashbordUrl = "{{route('index.index')}}"
        </script>
        <script type="text/javascript" src="/index/login/js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="/statics/layui/layui.js"></script>
        <script type="text/javascript" src="/statics/gt.js"></script>
        <script type="text/javascript" src="/statics/vue.min.js"></script>
        <script type="text/javascript" src="/index/login/js/login.js"></script>
        <script type="text/javascript" src="/index/login/js/supersized.3.2.7.min.js" ></script>
        <script type="text/javascript" src="/index/login/js/supersized-init.js" ></script>  

    </body>
</html>

