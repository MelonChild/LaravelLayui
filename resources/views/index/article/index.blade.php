@extends('index.layout.index')

<!--title-->
@section('title', '文章')

<!--样式-->
@section('head')

@endsection

<!--内容-->
@section('content')
<blockquote class="layui-elem-quote news_search">
    <div class="layui-inline layui-form">
        <div class="layui-input-inline">
            <input type="text" name="search" value="{{$search}}" placeholder="请输入关键字" class="layui-input search_input">
        </div>
        <div class="layui-input-inline">
            <select name="city" id="searchSelect" lay-verify="required">
                <option value=""></option>
                @forelse($cates as $cate)
                <option value="{{$cate['id']}}" @if($cate['id']==$selectId) selected @endif>{{$cate['name']}}</option>
                @empty
                @endforelse
            </select>
        </div>
        <a class="layui-btn search_btn dont-touch-my-class-search">查询</a>
    </div>
    <div class="layui-inline">
        <a class="layui-btn layui-btn-normal newsAdd_btn">新增文章</a>
    </div>
</blockquote>
<div class="layui-form news_list">
    <table class="layui-table">
        <colgroup>
            <col>
            <col width="9%">
            <col width="10%">
            <col width="15%">
        </colgroup>
        <thead>
            <tr>
                <th style="text-align:left;">ID</th>
                <th style="text-align:left;">文章</th>
                <th>缩略图</th>
                <th>详情</th>
                <th>创建日期</th>
                <th>操作</th>
            </tr> 
        </thead>
        <tbody class="news_content">
            @forelse($datas as $data)
            <tr>
                <td align="left">{{$data['id']}}</td>
                <td align="left">{{$data['title']}}</td>
                <td><img style="max-width: 100px;max-height: 50px;" src="{{$data['thumbnail']}}"/></td>
                <th> <a class="layui-btn layui-btn-normal layui-btn-mini usersEdit_btn" data-url="{{$data['articlepath']}}">查看</a></th> 
                <td>{{$data['created_at']}}</td>
                <td>
                    <a class="layui-btn layui-btn-normal layui-btn-mini usersEdit_btn" data-url="{{route('index.article.edit',['id'=>$data['id']])}}">
                        <i class="layui-icon"></i> 编辑
                    </a>
                   <a class="layui-btn layui-btn-danger layui-btn-mini dont-touch-my-class-del" data-url="{{route('index.article.destroy',['id'=>$data['id']])}}">
                        <i class="layui-icon"></i> 删除
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
    var add_url = "{{route('index.article.store')}}"; //提交编辑路径
    var add_show_url = "{{route('index.article.create')}}"; //提交编辑路径
    var update_url = "{{route('index.article.edit','')}}"; //提交编辑路径
</script>
<script type="text/javascript" src="/index/js/article.js"></script>
<script type="text/javascript">
    $(function () {
        $('body').articleIndex();
    });
</script>
@endsection