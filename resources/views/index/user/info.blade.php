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
            <label class="layui-form-label">用户组</label>
            <div class="layui-input-block">
                @forelse($userInfo->roles()->get() as $role)
                <label class="layui-form-label">{{$role['name']}}</label>
                @empty
                @endforelse
                
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"> 真实姓名</label>
            <div class="layui-input-block">
                <input type="text" name='nickname' value="{{$userInfo->nickname}}" placeholder="请输入真实姓名" lay-verify="required" class="layui-input realName">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号码</label>
            <div class="layui-input-block">
                <input type="tel" name="phone" value="{{$userInfo->phone}}" placeholder="请输入手机号码" class="layui-input userPhone">
            </div>
        </div>

    </div>
    <div class="user_right">
        <p>请上传小于2M的图片格式文件，最终会裁剪成200*200大小</p>
        <div class="cropbox" style="display:none;">
            <div class="imageBox">
                <div class="thumbBox"></div>
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

        <img src="{{$userInfo->avatar or '/index/images/face.jpg'}}" class="layui-circle" id="userFace">
        <input type="hidden" value="{{$userInfo->avatar}}" name="avatar" id="userFaceInput">
    </div>
    <div class="layui-form-item" style="margin-left: 5%;">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="changeUser">立即提交</button>
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