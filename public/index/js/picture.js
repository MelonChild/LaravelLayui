!function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    /**列表页**/
    $.fn.pictureIndex = function () {
        return this.each(function () {
            layui.config({
                base: "/index/js/"
            }).use(['form', 'layer', 'jquery', 'upload', 'laypage', 'layedit', 'laydate'], function () {
                var form = layui.form,
                        layer = parent.layer === undefined ? layui.layer : parent.layer,
                        laypage = layui.laypage,
                        layedit = layui.layedit,
                        laydate = layui.laydate,
                        upload = layui.upload,
                        $ = layui.jquery;

                //添加
                //改变窗口大小时，重置弹窗的高度，防止超出可视区域（如F12调出debug的操作）
                $(window).one("resize", function () {
                    $(".newsAdd_btn").click(function () {
                        var index = layui.layer.open({
                            title: "新增图片",
                            type: 2,
                            content: add_show_url,
                            success: function (layero, index) {
                                setTimeout(function () {
                                    layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                        tips: 3
                                    });
                                }, 500)
                            }
                        })
                        layui.layer.full(index);
                    })
                }).resize();


                //删除
                $(".dont-touch-my-class-del").click(function () {
                    var delUrl = $(this).data('url');
                    layer.confirm('真的要删吗？', {
                        title: '危险操作',
                        btn: ['真的', '算了'] //按钮
                    }, function () {
                        $.ajax({
                            url: delUrl,
                            type: "post",
                            dataType: "json",
                            data: {_method: 'DELETE'},
                            success: function (data) {
                                console.log(data);
                                layer.closeAll();
                                layer.msg(data['hint']);
                                //刷新父页面
                                if (data['status']) {
                                    parent.location.reload();
                                }
                            }
                        });
                    }, function () {

                    });
                })

            })
        })
    },
            /**新增页面**/
            $.fn.pictureAdd = function () {
                return this.each(function () {
                    layui.config({
                        base: "/index/js/"
                    }).use(['form', 'layer', 'jquery', 'upload', 'laypage', 'layedit', 'laydate'], function () {
                        var form = layui.form,
                                layer = parent.layer === undefined ? layui.layer : parent.layer,
                                laypage = layui.laypage,
                                layedit = layui.layedit,
                                laydate = layui.laydate,
                                upload = layui.upload,
                                $ = layui.jquery;

                        //创建提交
                        form.on("submit(addNews)", function (data) {
                            var data = $("#picAddForm").serializeArray();
                            console.log(data);
                            var path = $("#imageShowInput").val();
                            if (!path) {
                                layer.msg('未设置图片');
                                return false;
                            }
                            //弹出loading
                              var index1 = layer.msg('提交中，请稍候',{icon: 16,time:false,shade:0.8});
                            $.ajax({
                                url: add_url,
                                type: "post",
                                dataType: "json",
                                data: data,
                                success: function (data) {
                                    console.log(data);
                                    layer.close(index1);
                                    if (data) {
                                        top.layer.msg("成功！");
                                        layer.closeAll("iframe");
                                        //刷新父页面
                                        parent.location.reload();
                                    } else {
                                        top.layer.msg("添加失败，请稍候重试！");
                                    }
                                }
                            });
                            return false;
                        });
                        var options =
                                {
                                    thumbBox: '.thumbBox',
                                    spinner: '.spinner',
                                    imgSrc: '/index/images/demo.jpg'
                                }

                        var cropper = $('.imageBox').cropbox(options);
                        var uploadAvatar = upload.render({
                            elem: '#avatarUpload',
                            url: '/upload/',
                            size: 500, //上传大小限制500K
                            data: {dirName: 'avatar'},
                            choose: function (obj) { //预读本地文件示例，不支持ie8
                                obj.preview(function (index, file, result) {
                                    var reader = new FileReader();
                                    reader.onload = function (e) {
                                        options.imgSrc = e.target.result;
                                        console.log(e);
                                        cropper = $('.imageBox').cropbox(options);
                                    }
                                    reader.readAsDataURL(file);
                                });

                            },
                            auto: false, //不自动上传
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
                        $('#myRange').on('change', function () {
                            cropper.zoomChange($(this).val());
                        })

                        //裁剪
                        $('#btnCrop').on('click', function () {
                            //判断其他数据是否填写
                            var title = $(".input-title").val();
                            var desc = $(".input-desc").val();
                            var info = $(".input-info").val();

                            if (!title || !desc || !info) {
                                layer.msg('请先完善图片信息');
                                return false;
                            }

                            var img = cropper.getDataURL();
                            $.ajax({
                                url: '/generatePic',
                                type: "post",
                                dataType: "json",
                                data: {file: img, title: title, desc: desc, info: info},
                                success: function (data) {
                                    console.log(data);
                                    //上传成功，展示图片
                                    if (!data['errno']) {
                                        $(".cropbox").hide();
                                        $("#imageShowInput").val(data['path']);
                                        $("#imageShow").attr('src', data['path']).show();
                                    } else {
                                        layer.msg('上传失败，请重新上传!');
                                    }

                                }
                            });
                        })

                        $("#imageShow").click(function () {
                            $(".cropbox").show();
                            $("#imageShow").hide();
                        })

                        $("#btnCropCancel").click(function () {
                            $(".cropbox").hide();
                            $("#imageShow").show();
                        })

                    })

                })
            }
}(jQuery)

