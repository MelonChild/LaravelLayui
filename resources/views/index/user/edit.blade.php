@extends('index.layout.index')

<!--title-->
@section('title', '用户')

<!--样式-->
@section('head')
<link rel="stylesheet" type="text/css" href="/index/css/multi-select.css">
<style>
    html,body{height: 100%}
    .book-select .layui-form-select{
        display: none;
    }
</style>
@endsection

<!--内容-->
@section('content')

<form class="layui-form" id="userAddForm" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT">
    <div class="layui-form-item">
        <div class="layui-inline">		
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-inline">
                <input type="text" class="layui-input input-title " value="{{$user['username']}}" name="username" lay-verify="required|username" placeholder="用户名不重复">
            </div>
        </div>
        <div class="layui-inline">		
            <label class="layui-form-label">昵称</label>
            <div class="layui-input-inline">
                <input type="text" name="nickname" value="{{$user['nickname']}}" class="layui-input input-info" lay-verify="required" maxlength="6" value="" placeholder="用户昵称">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">		
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-inline">
                <input type="text" name="phone" value="{{$user['phone']}}" class="layui-input input-info" lay-verify="" maxlength="12" value="" placeholder="手机号">
            </div>
        </div>
        <div class="layui-inline">		
            <label class="layui-form-label">密码</label>
            <div class="layui-input-inline">
                <input type="text" name="password" class="layui-input input-info" lay-verify="" maxlength="12" value="" placeholder="填写即改变密码">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">用户角色</label>
        <div class="layui-input-block">
            @forelse($roles as $role)
            <input type="checkbox" name="roles[]" value='{{$role['id']}}' title="{{$role['name']}}" @if(in_array($role['id'],$roleIds)) checked @endif>
            @empty
            @endforelse
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">状态</label>
        <div class="layui-input-block">
            <input type="checkbox" name="active" value="1" lay-text="激活|禁用" lay-skin="switch" @if($user['active']==1) checked @endif>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="addUsers">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

@endsection

<!--js-->
@section('script')
<script type="text/javascript">
    var add_url = "{{route('index.user.update',['id'=>$user['id']])}}"; //提交编辑路径
    var add_show_url = "{{route('index.user.create')}}"; //提交编辑路径
</script>
<script type="text/javascript" src="/index/js/users.js"></script>
<script type="text/javascript">
    $(function () {
        $('body').userAdd();
    });
</script>
@endsection