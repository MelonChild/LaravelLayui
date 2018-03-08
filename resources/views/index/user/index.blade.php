@extends('index.layout.index')

<!--title-->
@section('title', '用户')

<!--样式-->
@section('head')

@endsection

<!--内容-->
@section('content')
<blockquote class="layui-elem-quote news_search">
    <div class="layui-inline">
        <div class="layui-input-inline">
            <input type="text" name="search" value="{{$search}}" placeholder="请输入关键字" class="layui-input search_input">
        </div>
        <a class="layui-btn search_btn dont-touch-my-class-search">查询</a>
    </div>
    <div class="layui-inline">
        <a class="layui-btn layui-btn-normal newsAdd_btn">新增用户</a>
    </div>
</blockquote>
<div class="layui-form news_list">
    <table class="layui-table">
        <colgroup>
            <col width="9%">
            <col>
            <col width="10%">
            <col width="15%">
        </colgroup>
        <thead>
            <tr>
                <th style="text-align:left;">用户名</th>
                <th>昵称</th>
                <th>手机号</th>
                <th>头像</th>
                <th>状态</th>
                <th>创建日期</th>
                <th>操作</th>
            </tr> 
        </thead>
        <tbody class="news_content">
            @forelse($datas as $data)
            <tr>
                <td align="left">{{$data['username']}}</td>
                <td align="left">{{$data['nickname']}}</td>
                <td align="left">{{$data['phone']}}</td>
                <td><img style="max-width: 100px;max-height: 50px;" src="{{$data['avatar']}}"/></td>
                <th> {{$data['active']?'正常':'禁用'}}</th> 
                <td>{{$data['created_at']}}</td>
                <td>
                    <a class="layui-btn layui-btn-normal layui-btn-mini usersEdit_btn" data-url="{{route('index.user.edit',['id'=>$data['id']])}}">
                        <i class="layui-icon"></i> 编辑
                    </a>
                </td>
            </tr>
            @empty

            @endforelse
        </tbody>
    </table>
</div>
<div id="nav">{{$datas->links()}}</div>

@endsection

<!--js-->
@section('script')
<script type="text/javascript">
    var add_url = "{{route('index.user.store')}}"; //提交编辑路径
    var add_show_url = "{{route('index.user.create')}}"; //提交编辑路径
    var update_url = "{{route('index.user.edit','')}}"; //提交编辑路径
</script>
<script type="text/javascript" src="/index/js/users.js"></script>
<script type="text/javascript">
    $(function () {
        $('body').userIndex();
    });
</script>
@endsection