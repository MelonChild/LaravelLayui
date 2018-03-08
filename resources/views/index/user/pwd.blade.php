@extends('index.layout.index')

<!--title-->
@section('title', '个人中心')

<!--样式-->
@section('head')

@endsection

<!--内容-->
@section('content')
<form class="layui-form">
    {{ csrf_field() }}
    <div class="user_left">
        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-block">
                <input type="text" value="{{$userInfo->username}}" disabled class="layui-input layui-disabled">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label"> 旧密码</label>
            <div class="layui-input-block">
                <input type="password" name='oldpwd' value="" placeholder="请输入旧密码" lay-verify="required" class="layui-input oldPwd">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label"> 新密码</label>
            <div class="layui-input-block">
                <input type="password"  value="" placeholder="请输入旧密码" lay-verify="required|newPwd" id="newPwd" class="layui-input newPwd1">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label"> 确认密码</label>
            <div class="layui-input-block">
                <input type="password" name='password' value="" placeholder="请输入旧密码" lay-verify="required|confirmPwd" class="layui-input newPwd2">
            </div>
        </div>

    </div>
    
    <div class="layui-form-item" style="margin-left: 5%;">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="changePwd">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
@endsection

<!--js-->
@section('script')
<script type="text/javascript" src="/index/login/js/jquery-1.8.2.min.js"></script>
<script type="text/javascript">
var saveUserUrl = "{{route('index.user.info')}}"
</script>
<script type="text/javascript" src="/statics/cropbox.js"></script>
<script type="text/javascript" src="/index/js/user.js"></script>
@endsection