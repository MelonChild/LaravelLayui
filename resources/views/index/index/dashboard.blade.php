@extends('index.layout.index')

<!--title-->
@section('title', '首页')

<!--样式-->
@section('head')
    
@endsection

<!--内容-->
@section('content')
    <div class="panel_box row " id="panel_box">

		<div class="panel col">
			<a href="javascript:;" data-url="page/img/images.html">
				<div class="panel_icon" style="background-color:#5FB878;">
					<i class="layui-icon" data-icon="&#xe64a;">&#xe64a;</i>
				</div>
				<div class="panel_word imgAll">
					<span>11</span>
					<cite>图片总数</cite>
				</div>
			</a>
		</div>
	</div>
	<blockquote class="layui-elem-quote explain">
		<p>公众号文章底部引导图片</p>
        <p>生成地址：点击左侧“图片管理”</p>
	</blockquote>
	<div class="row">
        <div class="sysNotice col">
			<blockquote class="layui-elem-quote title">系统信息</blockquote>
			<table class="layui-table">
				<colgroup>
					<col width="150">
					<col>
				</colgroup>
				<tbody>
					<tr>
						<td>当前版本</td>
						<td class="version">1.0</td>
					</tr>
					<tr>
						<td>开发作者</td>
						<td class="author">Melon</td>
					</tr>
					<tr>
						<td>最大上传限制</td>
						<td class="maxUpload">5M</td>
					</tr>
					<tr>
						<td>当前用户权限</td>
						<td class="userRights">编辑</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="sysNotice col">
			<blockquote class="layui-elem-quote title">注意事项</blockquote>
			<div class="layui-elem-quote layui-quote-nm">
				<h3># v0.1.0（开发） - {{date('Y-m-d')}}</h3>
				<p>* 开发中</p>
				
			</div>
		</div>
	</div>
@endsection

<!--js-->
@section('script')
    <script type="text/javascript" src="/index/js/main.js"></script>
@endsection