
var $form;
var form;
var $;
layui.config({
	base : "/index/js/"
}).use(['form','layer','upload','laydate'],function(){
	form = layui.form;
	var layer = parent.layer === undefined ? layui.layer : parent.layer;
		$ = layui.jquery;
		$form = $('form');
		laydate = layui.laydate;
        upload=layui.upload;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var avatar = $("#userFaceInput").val();
	var options =
	{
		thumbBox: '.thumbBox',
		spinner: '.spinner',
		imgSrc: avatar
	}

     //图片裁剪
    // $(window).load(function() {

    //     var cropper = $('.imageBox').cropbox(options);
    //     $('#upload-file').on('change', function(){
    //         var reader = new FileReader();
    //         reader.onload = function(e) {
    //             options.imgSrc = e.target.result;
    //             cropper = $('.imageBox').cropbox(options);
    //         }
            
    //         reader.readAsDataURL(this.files[0]);
    //         //this.files = '';
    //     })
  
       
    // });
    var cropper=$('.imageBox').cropbox(options);
    var uploadAvatar= upload.render({
            elem: '#avatarUpload',
            url: '/upload/',
            size:500, //上传大小限制500K
            data:{dirName:'avatar'},
            choose: function(obj){ //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        options.imgSrc = e.target.result;
                        console.log(e);
                        cropper = $('.imageBox').cropbox(options);
                    }
                    reader.readAsDataURL(file);
                });
               
            },
             auto:false, //不自动上传
        //     bindAction:"#uploadStart", //点击上传按钮
        //     done: function(res, index, upload){
        //     //上传完毕
        //     },
        //     error: function(res, index, upload){
        //     //上传失败,重新上传
        //    // uploadAvatar.upload();
        //     },
    });

    //变换大小
    $('#myRange').on('change', function(){
        cropper.zoomChange($(this).val());
    })

    //裁剪
    $('#btnCrop').on('click', function(){
        var img = cropper.getDataURL();
        $.ajax({
            url: '/avatarStore',
            type: "post",
            dataType: "json",
            data:{file:img},
            success: function (data) {
                console.log(data);
                //上传成功，展示图片
                if(!data['errno']){
                    $(".cropbox").hide();
                    $("#userFaceInput").val(data['path']);
                    $("#userFace").attr('src',data['path']).show();
                } else {
                    layer.msg('上传失败，请重新上传!');
                }
            
            }
        });
    })

    $("#userFace").click(function(){
        $(".cropbox").show();
        $("#userFace").hide();
    })

    $("#btnCropCancel").click(function () {
        $(".cropbox").hide();
        $("#userFace").show();
    })

    //添加验证规则
    form.verify({

        newPwd : function(value, item){
            if(value.length < 6){
                return "密码长度不能小于6位";
            }
        },
        confirmPwd : function(value, item){
            if($("#newPwd").val()!==value){
                return "两次输入密码不一致，请重新输入！";
            }
        }
    })

    //判断是否修改过用户信息，如果修改过则填充修改后的信息
    if(window.sessionStorage.getItem('userInfo')){
        var userInfo = JSON.parse(window.sessionStorage.getItem('userInfo'));
        var citys;
        $(".realName").val(userInfo.nickname); //用户名
        $(".userPhone").val(userInfo.phone); //手机号
        //判断是否修改过头像，如果修改过则显示修改后的头像，否则显示默认头像
        if(userInfo.avatar){
            $("#userFace").attr("src",userInfo.avatar);
        }else{
            $("#userFace").attr("src","/index/images/face.jpg");
        }

        form.render();
    }

    //提交个人资料
    form.on("submit(changeUser)",function(data){
    	var index = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
        //将填写的用户信息存到session以便下次调取
        var key,userInfoHtml = '';
        userInfoHtml = {
            'nickname' : $(".realName").val(),
            'phone' : $(".userPhone").val(),
            'avatar' : $("#userFaceInput").val(),
        };
        for(key in data.field){
            if(key.indexOf("like") != -1){
                userInfoHtml[key] = "on";
            }
        }

        //表单提交
        $.ajax({
            url: '/info',
            type: "post",
            dataType: "json",
            data:userInfoHtml,
            success: function (data) {
                layer.close(index);
                console.log(data);
                //上传成功
                if(data['errno']){
                    layer.msg(data['message']);
                } else {
                    window.sessionStorage.setItem("userInfo",JSON.stringify(userInfoHtml));
                    layer.msg(data['message']);
                }
            
            }
        });
       
    	return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    })

    //修改密码
    form.on("submit(changePwd)",function(data){
        console.log(data['field']);
    	var index = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
         //表单提交
        $.ajax({
            url: '/info',
            type: "post",
            dataType: "json",
            data:data['field'],
            success: function (data) {
                layer.close(index);
                console.log(data);
                //上传成功
                if(data['errno']){
                    layer.msg(data['message']);
                } else {
                    layer.msg(data['message']);
                }
            
            }
        });
        
    	return false;
    })

})
