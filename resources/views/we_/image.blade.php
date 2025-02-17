@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')); ?>">首页</a> &raquo; <a href="javascript:void(0);">素材管理</a> &raquo; 图片素材
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->
    <form action="<?php echo e(url('weixin/image_add')); ?>" method="post">
        <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="<?php echo e(url('weixin/image_add')); ?>"><i class="fa fa-plus"></i>新增图片</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID</th>
                        <th class="tc">图片名称</th>
                        <th class="tc">更新时间</th>
                        <th class="tc">图片</th>
                        <th class="tc" width="8%;">操作</th>
                    </tr>
                    <?php foreach($imageData as $v): ?>
                    <tr>
                        <td class="tc"><?php echo e($v['media_id']); ?></td>
                        <td class="tc"><?php echo strstr($v['name'], '/') !== false ? e(ltrim(strrchr($v['name'], '/'), '/')) : e($v['name']); ?></td>
                        <td class="tc"><?php echo e(date('Y-m-d H:i', $v['update_time'])); ?></td>
                        <td class="tc"><img src="<?php echo e(str_replace('http', 'https', $v['url'])); ?>" width="100" /></td>
                        <td>
                            <?php if($v['name'] == 'CropImage'): ?>
                            -
                            <?php else: ?>
                            <a href="javascript:void(0);" media_id="<?php echo e($v['media_id']); ?>" onclick="del(this);">删除</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                <div class="page_nav"><?php echo $pageShow; ?></div>

            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->
    <script>
        function del(obj){
            if(confirm('确定要删除吗？')){
                var media_id = $(obj).attr('media_id');

                $.post('<?php echo e(url('weixin/image_delete')); ?>', {'media_id':media_id, '_token':'<?php echo e(csrf_token()); ?>'}, function(data){
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
