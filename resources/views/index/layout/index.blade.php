<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>@yield('title')课窝小程序</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="format-detection" content="telephone=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="/statics/layui/css/layui.css" media="all" />
        <link rel="stylesheet" href="/index/css/font_eolqem241z66flxr.css" media="all" />

        <link rel="stylesheet" href="/index/css/main.css" media="all" />
        <link rel="stylesheet" href="/index/css/common.css" media="all" />
        <link href="/index/images/favicon.ico" mce_href="/index/images/favicon.ico" rel="bookmark" type="image/x-icon" /> 
        <link href="/index/images/favicon.ico" mce_href="/index/images/favicon.ico" rel="icon" type="image/x-icon" /> 
        <link href="/index/images/favicon.ico" mce_href="/index/images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        @yield('head')
    </head>
    <body class="childrenBody" id="childFrame">

        @yield('content')

        <script type="text/javascript" src="/index/login/js/jquery-1.8.2.min.js"></script>
        <script type="text/javascript" src="/index/js/common.js"></script>
        <script type="text/javascript" src="/statics/layui/layui.js"></script>
        <script type="text/javascript" src="/statics/vue.min.js"></script>

        @yield('script')

    </body>
</html>