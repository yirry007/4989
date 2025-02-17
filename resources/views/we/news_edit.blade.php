@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')); ?>">首页</a> &raquo; <a href="javascript:void(0);">素材管理</a> &raquo; 图文素材
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="<?php echo e(url('weixin/news_edit')); ?>" method="post">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <input type="hidden" name="media_id" value="<?php echo e($mediaId); ?>" />
            <input type="hidden" name="media_index" value="<?php echo e($mediaIndex); ?>" />
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="160"><i class="require">*</i>标题：</th>
                    <td>
                        <input type="text" class="lg" name="title" value="<?php echo e($newsData->title); ?>" />
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>封面图片ID：</th>
                    <td>
                        <input type="text" class="lg" name="thumb_media_id" value="<?php echo e($newsData->thumb_media_id); ?>" />
                        <input type="button" value="查找资源" onclick="viewImage();" />
                    </td>
                </tr>
                <tr>
                    <th>作者：</th>
                    <td>
                        <input type="text" class="lg" name="author" value="<?php echo e($newsData->author); ?>" />
                    </td>
                </tr>
                <tr>
                    <th>摘要：</th>
                    <td>
                        <textarea class="lg" name="digest"><?php echo e($newsData->digest); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th>是否显示封面：</th>
                    <td>
                        <label><input type="radio" name="show_cover_pic" value="1" <?php echo $newsData->show_cover_pic == '1' ? 'checked' : ''; ?> />显示</label>
                        <label><input type="radio" name="show_cover_pic" value="0" <?php echo $newsData->show_cover_pic == '0' ? 'checked' : ''; ?> />不显示</label>
                    </td>
                </tr>
                <tr>
                    <th>原文地址：</th>
                    <td>
                        <input type="text" class="lg" name="content_source_url" value="<?php echo e($newsData->content_source_url); ?>" />
                    </td>
                </tr>
                <tr>
                    <th>图文消息：</th>
                    <td>
                        <textarea id="content" name="content"><?php echo e($newsData->content); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <input type="submit" value="提交">
                        <input type="button" class="back" onclick="history.go(-1)" value="返回">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>
    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/public/we/ueditor/ueditor.config.js')); ?>"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/public/we/ueditor/ueditor.all.min.js')); ?>"> </script>
    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/public/we/ueditor/lang/zh-cn/zh-cn.js')); ?>"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/public/we/layer/layer.js')); ?>"></script>
    <script>
        UE.getEditor('content', {
            "initialFrameWidth" : "100%",   // 宽
            "initialFrameHeight" : 400,      // 高
            "maximumWords" : 65535            // 最大可以输入的字符数量
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
    </script>
@endsection
