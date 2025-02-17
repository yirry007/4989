@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')); ?>">首页</a> &raquo; <a href="javascript:void(0);">素材管理</a> &raquo; 图文素材
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->
    <form action="<?php echo e(url('weixin/news_add')); ?>" method="post">
        <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="<?php echo e(url('weixin/news_add')); ?>"><i class="fa fa-plus"></i>新增图文</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <?php foreach($newsData as $v): ?>
                <table class="list_tab">
                    <tr>
                        <th colspan="7" style="position:relative;">
                            ID: <?php echo e($v['media_id']); ?>
                            更新时间: <?php echo e(date('Y-m-d H:i', $v['update_time'])); ?>
                            <a href="javascript:void(0);" style="position:absolute;right:10px;top:5px;" media_id="<?php echo e($v['media_id']); ?>" onclick="del(this);">删除</a>
                        </th>
                    </tr>
                    <tr>
                        <th class="tc">标题</th>
                        <th class="tc">封面图片</th>
                        <th class="tc">作者</th>
                        <th class="tc">摘要</th>
                        <th class="tc">是否显示封面</th>
                        <th class="tc">原文地址</th>
                        <th class="tc" width="8%;">操作</th>
                    </tr>
                    <?php foreach($v['content']['news_item'] as $k1=>$v1): ?>
                    <tr>
                        <td class="tc"><?php echo e($v1['title']); ?></td>
                        <td class="tc"><img src="<?php echo e($v1['thumb_url']); ?>" width="100" /></td>
                        <td class="tc"><?php echo e($v1['author']); ?></td>
                        <td class="tc"><?php echo e($v1['digest']); ?></td>
                        <td class="tc"><?php echo $v1['show_cover_pic'] ? '显示' : '不显示'; ?></td>
                        <td class="tc"><a href="<?php echo e($v1['url']); ?>" target="_blank" style="float:none;">查看详细</a></td>
                        <td>
                            <a href="javascript:void(0);" media_index="<?php echo e($k1); ?>" media_id="<?php echo e($v['media_id']) ?>" data="<?php echo e(json_encode($v1)); ?>" onclick="editNews(this);">修改</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <div style="height:10px;"></div>
                <?php endforeach; ?>

                <div class="page_nav"><?php echo $pageShow; ?></div>

            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->
    <script>
        function editNews(obj){
            var media_index = $(obj).attr('media_index');
            var media_id = $(obj).attr('media_id');
            var data = $(obj).attr('data');

            $.post('<?php echo e(url('weixin/store_news')); ?>', {'media_index':media_index, 'media_id':media_id, 'data':data, '_token':'<?php echo e(csrf_token()); ?>'}, function(data){
                if(data.status == 0){
                    window.location.href = '<?php echo e(url('weixin/news_edit')); ?>';
                }else{
                    alert(data.msg);
                }
            });
        }

        function del(obj){
            if(confirm('确定要删除吗？')){
                var media_id = $(obj).attr('media_id');

                $.post('<?php echo e(url('weixin/video_delete')); ?>', {'media_id':media_id, '_token':'<?php echo e(csrf_token()); ?>'}, function(data){
                    if(data.status == 0){
                        window.location.reload();
                    }else{
                        alert(data.msg);
                    }
                });
            }
        }
    </script>
@endsection
