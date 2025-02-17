@extends('we.layout')


@section('content')
    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/we/ueditor/ueditor.config.js')); ?>"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/we/ueditor/ueditor.all.min.js')); ?>"> </script>
    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/we/ueditor/lang/zh-cn/zh-cn.js')); ?>"></script>
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')); ?>">首页</a> &raquo; <a href="javascript:void(0);">素材管理</a> &raquo; 图文素材
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <div class="news_count" style="padding:10px 0;">
            <?php for($i=1;$i<=8;$i++): ?>
            <input type="button" value="图文数量（<?php echo e($i); ?>）" onclick="resetForm('<?php echo e($i); ?>');" />
            <?php endfor; ?>
        </div>
        <form action="<?php echo e(url('weixin/news_add')); ?>" method="post">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <div id="news_add"></div>

            <div class="news_submit" style="padding:10px 0 10px 172px;">
                <input type="submit" value="提交">
                <input type="button" class="back" onclick="history.go(-1)" value="返回">
            </div>
        </form>
    </div>
    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/we/layer/layer.js')); ?>"></script>
    <script>
        $(function(){
            resetForm(1);
        });

        function viewImage(){
            layer.open({
                type: 2,
                title: '查找图片',
                shadeClose: false,
                shade: 0.6,
                area: ['80%', '80%'],
                maxmin: false,
                content: '<?php echo e(url('weixin/image')); ?>'
            });
        }

        function resetForm(num){
            //先把之前元素绑定的百度编辑器解绑
            $('.content').each(function(k, v){
                UE.delEditor(v.id);
            });

            var html = '';

            for(var i=1;i<=num;i++){
                html += '\
            <h2 style="padding:10px 0;">图文消息-'+i+'</h2>\
            <table class="add_tab">\
                <tr>\
                    <th width="160"><i class="require">*</i>标题：</th>\
                    <td>\
                        <input type="text" class="lg" name="title[]" value="" />\
                    </td>\
                </tr>\
                <tr>\
                    <th><i class="require">*</i>封面图片ID：</th>\
                    <td>\
                        <input type="text" class="lg" name="thumb_media_id[]" value="" />\
                        <input type="button" value="查找资源" onclick="viewImage();" />\
                    </td>\
                </tr>\
                <tr>\
                    <th>作者：</th>\
                    <td>\
                        <input type="text" class="lg" name="author[]" value="" />\
                    </td>\
                </tr>\
                <tr>\
                    <th>摘要：</th>\
                    <td>\
                        <textarea class="lg" name="digest[]"></textarea>\
                    </td>\
                </tr>\
                <tr>\
                    <th>是否显示封面：</th>\
                    <td>\
                        <label><input type="radio" name="show_cover_pic['+(i-1)+']" value="1" checked />显示</label>\
                        <label><input type="radio" name="show_cover_pic['+(i-1)+']" value="0" />不显示</label>\
                    </td>\
                </tr>\
                <tr>\
                    <th>原文地址：</th>\
                    <td>\
                        <input type="text" class="lg" name="content_source_url[]" value="" />\
                    </td>\
                </tr>\
                <tr>\
                    <th>图文消息：</th>\
                    <td>\
                        <textarea id="content'+i+'" name="content[]" class="content"></textarea>\
                    </td>\
                </tr>\
            </table>\
            <div style="height:20px;"></div>';
            }

            $('#news_add').html(html);

            setEditor();
        }

        function setEditor(){
            $('.content').each(function(k, v){
                UE.getEditor(v.id, {
                    "initialFrameWidth" : "100%",
                    "initialFrameHeight" : 300,
                    "maximumWords" : 65535
                });
            });
        }
    </script>
@endsection
