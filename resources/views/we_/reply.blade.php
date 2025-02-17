@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')); ?>">首页</a> &raquo; <a href="javascript:void(0);">自动回复</a>
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->
    <form action="<?php echo e(url('weixin/reply_add')); ?>" method="post">
        <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="<?php echo e(url('weixin/reply_add')); ?>"><i class="fa fa-plus"></i>新增回复消息</a>
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID</th>
                        <th class="tc">接收消息</th>
                        <th class="tc">回复类型</th>
                        <th class="tc" width="40%">回复内容</th>
                        <th class="tc" width="8%;">操作</th>
                    </tr>
                    <?php
                        $replyType = array(0=>'呼叫客服', 1=>'文本', 2=>'图片', 3=>'声音', 4=>'视频', 5=>'图文');
                        foreach($replyData as $v):
                    ?>
                    <tr>
                        <td class="tc"><?php echo e($v->id); ?></td>
                        <td class="tc"><?php echo e($v->msg); ?></td>
                        <td class="tc"><?php echo e($replyType[$v->event_type]); ?></td>
                        <td class="tc"><?php echo e($v->event_value); ?></td>
                        <td><a href="<?php echo e(url('weixin/reply_edit/'.$v->id.'?page='.app('request')->input('page'))); ?>">编辑</a><a href="javascript:void(0);" onclick="del('<?php echo e($v->id); ?>');">删除</a></td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                <div class="page_nav"><?php echo $pageShow; ?></div>

            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->
    <script>
        function del(reply_id){
            if(confirm('确定要删除吗？')){
                $.post('<?php echo e(url('weixin/reply_del')); ?>/'+reply_id, {'_token':'<?php echo e(csrf_token()); ?>'}, function(data){
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
