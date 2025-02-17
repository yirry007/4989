@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">模板消息管理</a>
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->
    <form action="#" method="post">
        <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <?php if(count($template) < 25): ?>
                    <a href="<?php echo e(url('weixin/template_add')); ?>"><i class="fa fa-plus"></i>添加消息模板</a>
                    <?php endif; ?>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">模板ID</th>
                        <th class="tc">模板标题</th>
                        <th class="tc">一级行业</th>
                        <th class="tc">二级行业</th>
                        <th class="tc" width="20%">详细</th>
                        <th class="tc" width="8%;">操作</th>
                    </tr>
                    <?php
                        foreach($template as $k=>$v):
                        if($k == 0)continue;
                    ?>
                    <tr>
                        <td class="tc"><?php echo e($v['template_id']); ?></td>
                        <td class="tc"><?php echo e($v['title']); ?></td>
                        <td class="tc"><?php echo e($v['primary_industry']); ?></td>
                        <td class="tc"><?php echo e($v['deputy_industry']); ?></td>
                        <td class="tc"><?php echo e($v['content']); ?></td>
                        <td>
                            <a href="javascript:void(0);" template_id="<?php echo e($v['template_id']); ?>" onclick="del(this);">删除</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>

            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->
    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/we/layer/layer.js')); ?>"></script>
    <script>
        function del(obj){
            if(confirm('确定要删除吗？')){
                var template_id = $(obj).attr('template_id');

                $.post('<?php echo e(url('weixin/template_del')); ?>/'+template_id, {'_token':'<?php echo e(csrf_token()); ?>'}, function(data){
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
