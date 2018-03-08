<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>课窝小程序</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta http-equiv="Access-Control-Allow-Origin" content="*">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="format-detection" content="telephone=no">
        <link rel="icon" href="favicon.ico">
        <link rel="stylesheet" href="/statics/layui/css/layui.css" media="all" />
        <link rel="stylesheet" href="//at.alicdn.com/t/font_tnyc012u2rlwstt9.css" media="all" />
        <link rel="stylesheet" href="/index/css/main.css" media="all" />
        <link href="/index/images/favicon.ico" mce_href="/index/images/favicon.ico" rel="bookmark" type="image/x-icon" /> 
        <link href="/index/images/favicon.ico" mce_href="/index/images/favicon.ico" rel="icon" type="image/x-icon" /> 
        <link href="/index/images/favicon.ico" mce_href="/index/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    </head>
    <body class="main_body">
        <div class="layui-layout layui-layout-admin">
            <!-- 顶部 -->
            <div class="layui-header header">
                <div class="layui-main">
                    <a href="{{url()->current()}}" class="logo">课窝小程序 </a>
                    <!-- 显示/隐藏菜单 -->
                    <a href="javascript:;" class="iconfont hideMenu icon-menu"><i class="layui-icon">&#xe60f;</i></a>
                    <!-- 搜索 -->

                    <!-- 天气信息 -->
                    <div class="weather" pc>
                        <div id="tp-weather-widget"></div>
                        <script>(function (T, h, i, n, k, P, a, g, e) {
                                g = function () {
                                    P = h.createElement(i);
                                    a = h.getElementsByTagName(i)[0];
                                    P.src = k;
                                    P.charset = "utf-8";
                                    P.async = 1;
                                    a.parentNode.insertBefore(P, a)
                                };
                                T["ThinkPageWeatherWidgetObject"] = n;
                                T[n] || (T[n] = function () {
                                    (T[n].q = T[n].q || []).push(arguments)
                                });
                                T[n].l = +new Date();
                                if (T.attachEvent) {
                                    T.attachEvent("onload", g)
                                } else {
                                    T.addEventListener("load", g, false)
                                }
                            }(window, document, "script", "tpwidget", "/statics/widget.js"))</script>
                        <script>tpwidget("init", {
                                "flavor": "slim",
                                "location": "WX4FBXXFKE4F",
                                "geolocation": "enabled",
                                "language": "zh-chs",
                                "unit": "c",
                                "theme": "chameleon",
                                "container": "tp-weather-widget",
                                "bubble": "disabled",
                                "alarmType": "badge",
                                "color": "#FFFFFF",
                                "uid": "U9EC08A15F",
                                "hash": "039da28f5581f4bcb5c799fb4cdfb673"
                            });
                            tpwidget("show");</script>
                    </div>
                    <!-- 顶部右侧菜单 -->
                    <ul class="layui-nav top_menu">
                        <li class="layui-nav-item showNotice" id="showNotice" pc>
                            <a href="javascript:;"><i class="iconfont icon-gonggao"></i><cite>系统公告</cite></a>
                        </li>
                        <li class="layui-nav-item" mobile>
                            <a href="javascript:;" class="mobileAddTab" data-url="page/user/changePwd.html"><i class="iconfont icon-shezhi1" data-icon="icon-shezhi1"></i><cite>设置</cite></a>
                        </li>
                        <li class="layui-nav-item" mobile>
                            <a href="{{route('index.logout')}}" class="signOut"><i class="iconfont icon-loginout"></i> 退出</a>
                        </li>
                        <li class="layui-nav-item lockcms" pc>
                            <a href="javascript:;"><i class="iconfont icon-lock1"></i><cite>锁屏</cite></a>
                        </li>
                        <li class="layui-nav-item" pc>
                            <a href="javascript:;">
                                <img src="{{$userInfo['avatar']}}" class="layui-circle" width="35" height="35">
                                <cite>{{$userInfo['nickname']}}</cite>
                            </a>
                            <dl class="layui-nav-child">
                                <dd><a href="javascript:;" data-url="{{route('index.user.info')}}"><i class="iconfont icon-zhanghu" data-icon="icon-zhanghu"></i><cite>个人资料</cite></a></dd>
                                <dd><a href="javascript:;" data-url="{{route('index.user.pwd')}}"><i class="iconfont icon-shezhi1" data-icon="icon-shezhi1"></i><cite>修改密码</cite></a></dd>
                                <dd><a href="javascript:;" class="changeSkin"><i class="iconfont icon-huanfu"></i><cite>更换皮肤</cite></a></dd>
                                <dd><a href="{{route('index.logout')}}" class="signOut"><i class="iconfont icon-loginout"></i><cite>退出</cite></a></dd>
                            </dl>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- 左侧导航 -->
            <div class="layui-side layui-bg-black">
                <div class="user-photo">
                    <a class="img" title="{{$userInfo['nickname']}}" ><img src="{{$userInfo['avatar']}}"></a>
                    <p>Hi！<span class="userName">{{$userInfo['nickname']}}</span></p>
                </div>
                <div class="navBar layui-side-scroll"></div>
            </div>
            <!-- 右侧内容 -->
            <div class="layui-body layui-form">
                <div class="layui-tab marg0" lay-filter="bodyTab" id="top_tabs_box">
                    <ul class="layui-tab-title top_tab" id="top_tabs">
                        <li class="layui-this" lay-id="1"><i class="iconfont icon-computer"></i> <cite>首页</cite></li>
                    </ul>
                    <ul class="layui-nav closeBox">
                        <li class="layui-nav-item">
                            <a href="javascript:;"><i class="iconfont icon-caozuo"></i> 页面操作</a>
                            <dl class="layui-nav-child">
                                <dd><a href="javascript:;" class="refresh refreshThis"><i class="layui-icon">&#x1002;</i> 刷新当前</a></dd>
                                <dd><a href="javascript:;" class="closePageOther"><i class="layui-icon">&#x1007;</i> 关闭其他</a></dd>
                                <dd><a href="javascript:;" class="closePageAll"><i class="layui-icon">&#x1006;</i> 关闭全部</a></dd>
                            </dl>
                        </li>
                    </ul>
                    <div class="layui-tab-content clildFrame">
                        <div class="layui-tab-item layui-show ">
                            <iframe src="{{route('index.dashboard')}}" data-id='1'></iframe>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 底部 -->
            <div class="layui-footer footer">
                <p>copyright @2017 Melon - KEWO</p>
            </div>
        </div>

        <!-- 移动导航 -->
        <div class="site-tree-mobile layui-hide"><i class="layui-icon">&#xe602;</i></div>
        <div class="site-mobile-shade"></div>

        <!--系统通知-->
        <div class="notify">
            <div class="notify-show">
                <p>哦，这样啊，好吧，就这样，哎</p>
            </div>
        </div>

        <!--锁屏-->
        <div class="lock">
            <div class="admin-header-lock" id="lock-box">
                <div class="admin-header-lock-img"><img src="{{$userInfo['avatar']}}"/></div>
                <div class="admin-header-lock-name" id="lockUserName">{{$userInfo['nickname']}}</div>
                <div class="input_btn">
                    <input type="password" class="admin-header-lock-input layui-input" autocomplete="off" placeholder="请输入密码解锁.." name="lockPwd" id="lockPwd" />
                    <button class="layui-btn" id="unlock">解锁</button>
                </div>
                <p>请输入“123456”</p>
            </div>
        </div>

        <!--更换皮肤-->
        <div class="skin">
            <div class="skins_box">
                <form class="layui-form">
                    <div class="layui-form-item">
                        <input type="radio" name="skin" value="默认" title="默认" lay-filter="default" checked="">
                        <input type="radio" name="skin" value="橙色" title="橙色" lay-filter="orange">
                        <input type="radio" name="skin" value="蓝色" title="蓝色" lay-filter="blue">
                        <input type="radio" name="skin" value="自定义" title="自定义" lay-filter="custom">
                        <div class="skinCustom">
                            <input type="text" class="layui-input topColor" name="topSkin" placeholder="顶部颜色" />
                            <input type="text" class="layui-input leftColor" name="leftSkin" placeholder="左侧颜色" />
                            <input type="text" class="layui-input menuColor" name="btnSkin" placeholder="顶部菜单按钮" />
                        </div>
                    </div>
                    <div class="layui-form-item skinBtn">
                        <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-normal" lay-submit="" lay-filter="changeSkin">确定更换</a>
                        <a href="javascript:;" class="layui-btn layui-btn-small layui-btn-primary" lay-submit="" lay-filter="noChangeSkin">我再想想</a>
                    </div>
                </form>
            </div>
        </div>

        <script type="text/javascript">
            var getNavsUrl = "{{route('index.navs')}}";
        </script>

        <script type="text/javascript" src="/statics/layui/layui.js"></script>
        <script type="text/javascript" src="/index/js/leftNav.js"></script>
        <script type="text/javascript" src="/index/js/index.js"></script>
    </body>
</html>