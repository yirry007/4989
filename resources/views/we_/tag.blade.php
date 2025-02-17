@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">标签管理</a>
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->
    <form action="#" method="post">
        <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="<?php echo e(url('weixin/tag_add')); ?>"><i class="fa fa-plus"></i>新增标签</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID</th>
                        <th class="tc">标签名称</th>
                        <th class="tc">用户数</th>
                        <th class="tc" width="8%;">操作</th>
                    </tr>
                    <?php foreach($tagList as $v): ?>
                    <tr>
                        <td class="tc"><?php echo e($v['id']); ?></td>
                        <td class="tc"><?php echo e($v['name']); ?></td>
                        <td class="tc"><?php echo e($v['count']); ?></td>
                        <td>
                            <?php if($v['id'] >= 100): ?>
                            <a href="<?php echo e(url('weixin/tag_edit/'.$v['id'].'?name='.$v['name'].'&count='.$v['count'])); ?>">修改</a>
                            <a href="javascript:void(0);" tag_id="<?php echo e($v['id']); ?>" onclick="del(this);">删除</a>
                            <?php else: ?>
                            -
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
                var tag_id = $(obj).attr('tag_id');

                $.post('<?php echo e(url('weixin/tag_delete')); ?>', {'tag_id':tag_id, '_token':'<?php echo e(csrf_token()); ?>'}, function(data){
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
