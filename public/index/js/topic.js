!function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    /**列表页**/
    $.fn.topicIndex = function () {
        return this.each(function () {
            layui.config({
                base: "/admin/js/"
            }).use(['layer', 'laypage'], function () {
                var layer = parent.layer === undefined ? layui.layer : parent.layer,
                        laypage = layui.laypage;

                //点击编辑按钮
                $('.dont-touch-my-class-edit').click(function () {
                    var url = $(this).data('url');
                    var index = layui.layer.open({
                        title: "查看修改",
                        type: 2,
                        content: url,
                        success: function (layero, index) {
                            setTimeout(function () {
                                layui.layer.tips('点击此处返回题目列表', '.layui-layer-setwin .layui-layer-close', {
                                    tips: 3
                                });
                            }, 500)
                        }
                    });
                    layui.layer.full(index);
                });
            })

        })
    },
            /**编辑页面**/
            $.fn.topicEdit = function () {
                return this.each(function () {
                    layui.config({
                        base: "/admin/js/"
                    }).use(['layer', 'laypage', 'form', 'layedit'], function () {
                        var form = layui.form,
                                layedit = layui.layedit,
                                layer = parent.layer === undefined ? layui.layer : parent.layer,
                                laypage = layui.laypage;

                        //富文本
//                        var topicdesc = layedit.build('description');
//                        var stem = layedit.build('stem');
//                        var article = layedit.build('article');
                        CKEDITOR.replace('description');
                        CKEDITOR.replace('stem');
                        CKEDITOR.replace('article');
//
//                        $('.dont-touch-my-class-submit').click(function () {
//                            var url = $(this).data('url');
//                            $.post(url, $('form').serialize(), function (data) {
//                                parent.layer.closeAll();
//                                if (data) {
//                                    top.layer.msg("修改成功！");
//                                    parent.layer.closeAll("iframe");
//                                    //刷新父页面
//                                    parent.location.reload();
//                                } else {
//                                    top.layer.msg("修改失败，请稍候重试！");
//                                }
//                            });
//                        });

                        // 题目类型的选择  
                        var types = {1: "词汇", 2: "听力", 3: "口语", 4: "阅读", 5: "写作"};
                        form.on('select(selectType)', function (data) {
                            console.log(data);
                            $(".type-hide").hide();
                            $(".type-hide-" + data.value).show();
                            if (data.value == 5) {
                                console.log(1);
                                $(".write-div-hide").show();
                                $(".type-div-hide").hide();
                                $(".topic-title-list").html('写作');
                            } else {
                                console.log(2);
                                $(".topic-title-list").html(types[data.value]);
                                $(".write-div-hide").hide();
                                $(".type-div-hide").show();
                                $(".type-hide-other").show();
                            }
                        });

                        //更新提交
                        form.on('submit(updateEdit)', function (data) {
                            // layer.msg(JSON.stringify(data.field));
                            data.field.description5 = CKEDITOR.instances.description.getData();
                            data.field.stem4 = CKEDITOR.instances.stem.getData();
                            data.field.article = CKEDITOR.instances.article.getData();
                            data.field.scene = selectedValue;
                            console.log(data.field.scene);
                            $.ajax({
                                url: update_url,
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

                        //同步文章查看
                        $(".sync-passage").click(function () {
                            var editor = $(this).data('editor');
                            var html = editor == 'stem' ? CKEDITOR.instances.stem.getData() : CKEDITOR.instances.article.getData();
                            console.log(html);
                            $(".article-show").html(html);
                        });

                        //试听听力
                        $(".sync-listening").click(function () {
                            $(this).prev().val() && window.open($(this).prev().val());
                        })

                    })
                    //题目类型
                    var topicType = 1;
                    var typeShow = topicType == 5 ? 1 : 0;
                    var vue = new Vue({
                        el: "#app",
                        data: {
                            topicName: topic_name,
                            topicTime: topic_time,
                            desc: desc,
                            typeShow: typeShow
                        },
                        methods: {
                            changeType: function (event) {
                                this.typeShow = !this.typeShow;
                                alert(this.typeShow);
                            }
                        }
                    })

                })
            },
            /**问题列表页**/
            $.fn.questionIndex = function () {
                return this.each(function () {
                    layui.config({
                        base: "/admin/js/"
                    }).use(['layer'], function () {
                        var layer = parent.layer === undefined ? layui.layer : parent.layer;

                        //点击编辑按钮
                        $('.dont-touch-my-class-edit').click(function () {
                            var url = $(this).data('url');
                            var index = layui.layer.open({
                                title: "修改问题",
                                type: 2,
                                content: url,
                                success: function (layero, index) {
                                    setTimeout(function () {
                                        layui.layer.tips('点击此处返回问题列表', '.layui-layer-setwin .layui-layer-close', {
                                            tips: 3
                                        });
                                    }, 500)
                                }
                            });
                            layui.layer.full(index);
                        });
                    })

                })
            },
            /**编辑页面**/
            $.fn.questionEdit = function () {
                return this.each(function () {
                    layui.config({
                        base: "/admin/js/"
                    }).use(['layer', 'laypage', 'form', 'layedit'], function () {
                        var form = layui.form,
                                layedit = layui.layedit,
                                layer = parent.layer === undefined ? layui.layer : parent.layer,
                                laypage = layui.laypage;

                        //富文本
                        var topicdesc = layedit.build('description');
                        CKEDITOR.replace('content');
                        CKEDITOR.replace('answers6');
//
//                        $('.dont-touch-my-class-submit').click(function () {
//                            var url = $(this).data('url');
//                            $.post(url, $('form').serialize(), function (data) {
//                                parent.layer.closeAll();
//                                if (data) {
//                                    top.layer.msg("修改成功！");
//                                    parent.layer.closeAll("iframe");
//                                    //刷新父页面
//                                    parent.location.reload();
//                                } else {
//                                    top.layer.msg("修改失败，请稍候重试！");
//                                }
//                            });
//                        });

                        // 题目类型的选择  
                        var types = {1: "单选练习", 2: "多选练习", 3: "多选练习", 4: "填空练习", 5: "是非练习", 6: "写作练习", 7: "口语练习"};
                        form.on('select(selectType)', function (data) {
                            console.log(data);
                            $(".type-hide").hide();
                            $(".type-hide-" + data.value).show();
                            $(".question-answer-type").html(types[data.value]);
                            if (data.value == 5) {
                                console.log(1);
                                $(".write-div-hide").show();
                                $(".type-div-hide").hide();
                                $(".topic-title-list").html('写作');
                            } else {
                                console.log(2);
                                $(".write-div-hide").hide();
                                $(".type-div-hide").show();
                                $(".type-hide-other").show();
                            }
                        });

                        //更新提交
                        form.on('submit(updateEdit)', function (data) {
                            // layer.msg(JSON.stringify(data.field));
                            var formData = $('#questionForm').serializeArray();
                            console.log(formData);
                            console.log(formData['type']);
                            data.field.description = layedit.getContent(topicdesc);
                            data.field.content = CKEDITOR.instances.content.getData();
                            data.field.answers6 = CKEDITOR.instances.answers6.getData();
                            console.log(data.field);
                            layer.load(1, {
                                shade: [0.1, '#fff'] //0.1透明度的白色背景
                            });
                            $.ajax({
                                url: update_url,
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

                        //同步文章查看
                        $(".sync-passage").click(function () {
                            var editor = $(this).data('editor');
                            var showObj = $(this).data('show');
                            var html = (editor == 'content' ? CKEDITOR.instances.content.getData() : (editor == 'answers6' ? CKEDITOR.instances.answers6.getData() : layedit.getContent(topicdesc)));
                            // console.log(html);
                            $("." + showObj).html(html);
                        });

                        //图片查看
                        $(".sync-pic").click(function () {
                            $(this).prev().val() && window.open($(this).prev().val());
                        })

                        //新增一条小问题
                        $("body").on("click", ".question-li-add", function () {
                            var select = $(this).data('html');
                            var append = $(this).data('append');
                            var outerHtml = $("." + select).eq(0).prop("outerHTML");
                            if (append == 'question-title-bottom') {
                                $(this).parents('.question-li').find("." + append).before(outerHtml);
                            } else {
                                $("." + append).before(outerHtml);
                            }
                            //console.log(outerHtml);
                            initFormName();
                        })

                        //删除小问题
                        $("body").on("click", ".question-li-del", function () {
                            var select = $(this).data('html');
                            $("." + select).length > 1 ? $(this).parent().remove() : layer.msg('只剩一条了,要不咱留着吧');
                            console.log($("." + select).length);
                            initFormName();
                        })

                        //重置表单name 
                        function initFormName() {
                            //循环小问题
                            $(".question-li").each(function (index, obj) {
                                console.log(index);
                                $(obj).find('input').each(function (index1, obj1) {
                                    console.log(obj1);
                                    var prev = $(obj1).attr('data-prev');
                                    var next = $(obj1).attr('data-next');
                                    prev && next && $(obj1).attr('name', prev + index + next);
                                })
                                $(obj).find('.question-title-li').each(function (index3, obj3) {
                                    $(obj3).find('textarea').each(function (index2, obj2) {
                                        console.log(obj2);
                                        var prev = $(obj2).attr('data-prev');
                                        var next = $(obj2).attr('data-next');
                                        prev && next && $(obj2).attr('name', prev + index + next + '[' + index3 + ']');
                                    })
                                })

                            })
                        }

                    })
                    //题目类型
                    var topicType = 1;
                    var typeShow = topicType == 5 ? 1 : 0;
                    var vue = new Vue({
                        el: "#app",
                        data: {
                            questionName: question_name,
                            topicTime: 0,
                            desc: '',
                            typeShow: typeShow
                        },
                        methods: {
                            changeType: function (event) {
                                this.typeShow = !this.typeShow;
                                alert(this.typeShow);
                            }
                        }
                    })

                })
            }

}(jQuery)
