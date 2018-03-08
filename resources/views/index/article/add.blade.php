@extends('index.layout.index')

<!--title-->
@section('title', '文章')

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
    <div class="layui-form-item">
        <div class="layui-block">		
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" class="layui-input input-title " lay-verify="required" placeholder="文章标题">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-block">		
            <label class="layui-form-label">文章路径</label>
            <div class="layui-input-block">
                <input type="text" name="articlepath" class="layui-input input-title " lay-verify="required" placeholder="文章路径">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">文章分类</label>
        <div class="layui-input-inline">
            <select name="cate_id" id="searchSelect" lay-verify="required">
                @forelse($cates as $cate)
                <option value="{{$cate['id']}}">{{$cate['name']}}</option>
                @empty
                @endforelse
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">简介</label>
        <div class="layui-input-block">
            <textarea name="desc" placeholder="请输入内容" class="layui-textarea"></textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-inline">		
            <label class="layui-form-label">缩略图</label>
            <div class="layui-form-mid layui-word-aux">请上传小于2M的图片格式文件，裁剪成200*150大小</div><br>
            <div class="layui-input-inline">
                <div class="user_right">
                    <div class="cropbox" style="display:none;">
                        <div class="imageBox" style="width: 350px;height: 210px;">
                            <div class="thumbBox" style="width: 250px;height: 150px; top:62%;left: 43%"></div>
                            <div class="spinner" style="display: none"></div>
                        </div>
                        <div class="action"> 
                                <!-- <input type="file" id="file" style=" width: 200px">-->
                            <button type="button" class="layui-btn" id="avatarUpload"><i class="layui-icon"></i>选择文件</button>
                            <a  id="btnCrop"  class="layui-btn"  style="curosr: pointer">裁切</a>
                            <a  id="btnCropCancel"  class="layui-btn"  style="curosr: pointer">取消</a><br>
                            <input type="range" id="myRange" value="50">
                        </div>
                        <div class="cropped">
                        </div>

                    </div>
                            <!--<input class="layui-upload-file" type="file" name="file">-->

                    <img src="/index/images/demo.jpg" id="imageShow">
                    <input type="hidden" value="" name="thumbnail" id="imageShowInput">
                </div>
            </div>
        </div>

    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="addArticle">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

@endsection

<!--js-->
@section('script')
<script type="text/javascript">
    var add_url = "{{route('index.article.store')}}"; //提交编辑路径
    var add_show_url = "{{route('index.article.create')}}"; //提交编辑路径
    var minWidth=250;
    var minHeight=150;
</script>
<script type="text/javascript" src="/statics/cropbox.js"></script>
<script type="text/javascript" src="/index/js/article.js"></script>
<script type="text/javascript">
    $(function () {
        $('body').articleAdd();
    });
</script>
@endsection