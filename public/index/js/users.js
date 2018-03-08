!function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    /**列表页**/
    $.fn.userIndex = function () {
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
                            title: "新增用户",
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

                $(window).one("resize", function () {
                    $(".usersEdit_btn").click(function () {
                        var edit_show_url = $(this).data('url');
                        var index = layui.layer.open({
                            title: "编辑用户",
                            type: 2,
                            content: edit_show_url,
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
            $.fn.userAdd = function () {
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

                        //添加验证规则
                        form.verify({
                            username: function (value, item) {
                                $.ajax({
                                    url: '/checkUsername',
                                    type: "post",
                                    dataType: "json",
                                    data: {id: 0, username: 'admin'},
                                    success: function (data) {
                                        return '用户名重复';
                                    }
                                });
                            }
                        })

                        //创建提交
                        form.on("submit(addUsers)", function (data) {
                            var data = $("#userAddForm").serializeArray();
                            console.log(data);
                            //弹出loading
                            var index1 = layer.msg('提交中，请稍候', {icon: 16, time: false, shade: 0.8});
                            $.ajax({
                                url: add_url,
                                type: "post",
                                dataType: "json",
                                data: data,
                                success: function (data) {
                                    console.log(data);
                                    layer.close(index1);
                                    top.layer.msg(data['message']);
                                    if (!data['errno']) {
                                        layer.closeAll("iframe");
                                        //刷新父页面
                                        parent.location.reload();
                                    }
                                }
                            });
                            return false;
                        });

                    })

                })
            }
}(jQuery)

