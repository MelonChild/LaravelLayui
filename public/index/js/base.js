editor = '';
!function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    /**列表页**/
    $.fn.pageIndex = function () {
        return this.each(function () {
            layui.config({
                base: "/index/js/"
            }).use(['layer', 'laypage'], function () {
                var layer = parent.layer === undefined ? layui.layer : parent.layer,
                        laypage = layui.laypage;

                //点击编辑按钮
                $('.base-btn-add-edit').click(function () {
                    var url = $(this).data('url');
                    var index = layui.layer.open({
                        title: "新增/编辑",
                        type: 2,
                        content: url,
                        success: function (layero, index) {
                            setTimeout(function () {
                                layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                                    tips: 3
                                });
                            }, 500)
                        }
                    });
                    layui.layer.full(index);
                });
                //点击删除按钮
                $('.base-btn-delete').click(function () {
                    var url = $(this).data('url');
                    layer.confirm('确定要删除吗？', {
                        btn: ['是啊', '算了'] //按钮
                    }, function () {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            dataType: 'json',
                            data: {_method: 'DELETE'},
                            success: function (data) {
                                if (data.status) {
                                    layer.msg("删除成功！");
                                    location.reload();
                                } else {
                                    layer.msg("删除失败，请稍候重试！");
                                }
                            }
                        });
                    }, function () {
                        layer.msg('怂');
                    });

                });
            })

        })
    },
            /**新增页**/
            $.fn.pageAdd = function () {
                return this.each(function () {
                    layui.config({
                        base: "/admin/js/"
                    }).use(['layer', 'form', 'upload'], function () {
                        var layer = parent.layer === undefined ? layui.layer : parent.layer,
                                form = layui.form, upload = layui.upload;
                        if (editor != '') {
                            CKEDITOR.replace(editor);
                        }
                        //图片上传
                        upload.render({
                            elem: '#uploadDiv',
                            url: '/Melon/upload',
                            done: function (res) {
                                if (res.errno) {
                                    return layer.msg('上传失败');
                                } else {
                                    layer.msg('上传成功');
                                    $("#uploadDivShow").attr('src', res.path).show();
                                    $("#uploadDivInput").attr('value', res.path);
                                    $("#uploadDivShow").next().hide();
                                    return false;
                                }
                                //上传成功
                            },
                            error: function () {
                                return layer.msg('上传失败1');
                            }
                        });

                        //更新提交
                        form.on('submit(formSubmit)', function (data) {
                            var url = $(".form-submit-btn").data('url');
                            if (editor == 'mark') {
                                data.field.mark = CKEDITOR.instances.mark.getData();
                            }
                            $.ajax({
                                url: url,
                                type: 'POST',
                                dataType: 'json',
                                data: data.field,
                                success: function (data) {
                                    if (data) {
                                        top.layer.msg("修改成功！");
                                        parent.layer.closeAll("iframe");
                                        //刷新父页面
                                        parent.location.reload();
                                    } else {
                                        top.layer.msg("修改失败，请稍候重试！");
                                    }
                                }
                            });
                            return false;
                        });


                    })

                })
            },
            $.fn.pageShow = function () {
                return this.each(function () {
                    layui.config({
                        base: "/admin/js/"
                    }).use(['layer', 'form'], function () {
                        var layer = parent.layer === undefined ? layui.layer : parent.layer,
                                form = layui.form;

                        //点击播放
                        $(".listening").click(function () {
                            var url = $(this).data('url');
                            layer.open({
                                type: 2,
                                title: false,
                                area: ['300px', '50px'],
                                shade: 0.8,
                                closeBtn: 0,
                                shadeClose: true,
                                content: url
                            });
                        })
                        //点击放大
                        $(".writing").click(function () {
                            layer.open({
                                type: 1,
                                title: false,
                                closeBtn: 0,
                                area: '35%',
                                skin: 'layui-layer-nobg', //没有背景色
                                shadeClose: true,
                                content: $(this)
                            });
                        })



                    })

                })
            }

}(jQuery)
