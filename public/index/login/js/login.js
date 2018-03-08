//layui初始化
layui.use(['layer'],function(){
	var layer = parent.layer === undefined ? layui.layer : parent.layer,
		$ = layui.jquery;

		$(function (){

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			var handlerEmbed = function (captchaObj) {
				$("#embed-submit").click(function (e) {
					var validate = captchaObj.getValidate();
					if (!validate) {
						$("#notice")[0].className = "show";
						setTimeout(function () {
							$("#notice")[0].className = "hide";
						}, 2000);
						e.preventDefault();
						return false;
					}
					$.ajax({
						url: verifyGeetestUrl,
						type: 'POST',
						dataType: 'json',
						data: {
							username: $('#admin_username').val(),
							password: $('#admin_pwd').val(),
							geetest_challenge: validate.geetest_challenge,
							geetest_validate: validate.geetest_validate,
							geetest_seccode: validate.geetest_seccode
						},
						success: function (data) {
							if (data.status === 'success') {
								layer.msg('登录成功');
								location.href=dashbordUrl;
							} else if (data.status === 'fail') {
								layer.msg('登录失败，请完成验证');
								captchaObj.reset();
							}
						}
					});
				});
				// 将验证码加到id为captcha的元素里，同时会有三个input的值：geetest_challenge, geetest_validate, geetest_seccode
				captchaObj.appendTo("#embed-captcha");
				captchaObj.onReady(function () {
					$("#wait")[0].className = "hide";
				});
			};
			$.ajax({
				// 获取id，challenge，success（是否启用failback）
				url: geetestUrl + '?'+(new Date()).getTime(), // 加随机数防止缓存
				type: "get",
				dataType: "json",
				success: function (data) {
				// console.log(data);
					// 使用initGeetest接口
					// 参数1：配置参数
					// 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
					initGeetest({
						gt: data.gt,
						challenge: data.challenge,
						new_captcha: data.new_captcha,
						product: "float", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
						offline: !data.success, // 表示用户后台检测极验服务器是否宕机，一般不需要关注
						width: '100%',
					}, handlerEmbed);
				}
			});
		});
})

//设置cookie
function setCookie(cname,cvalue,exdays)
{
  var d = new Date();
  d.setTime(d.getTime()+(exdays*24*60*60*1000));
  var expires = "expires="+d.toGMTString();
  document.cookie = cname + "=" + cvalue + "; " + expires;
}

//获取cookie
function getCookie(cname)
{
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i=0; i<ca.length; i++) 
  {
    var c = ca[i].trim();
    if (c.indexOf(name)==0) return c.substring(name.length,c.length);
  }
  return "";
}

//vue 初始化
var videoBg=getCookie('videoBg');
document.getElementById('checkboxDiv').className=parseInt(videoBg)?'layui-unselect layui-form-checkbox layui-form-checked':'layui-unselect layui-form-checkbox';
console.log(videoBg);
var vue =new Vue({
	el:"#app",
	data:{
		videoBg:parseInt(videoBg),
	},
	methods:{
		changeBg:function(event) {
			this.videoBg=!this.videoBg;
			event.target.parentNode.className=this.videoBg?'layui-unselect layui-form-checkbox layui-form-checked':'layui-unselect layui-form-checkbox';
			setCookie('videoBg',(this.videoBg?1:0),30);
		}
	}
})
