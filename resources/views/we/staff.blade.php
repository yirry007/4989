@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">客服管理</a>
    </div>
    <!--面包屑导航 结束-->

    <!--搜索结果页面 列表 开始-->
    <form action="#" method="post">
        <div class="result_wrap">
            <!--快捷导航 开始-->
            <div class="result_content">
                <div class="short_wrap">
                    <a href="<?php echo e(url('weixin/staff_add')); ?>"><i class="fa fa-plus"></i>新增客服</a>
                    <input type="button" value="全部客服" onclick="window.location.href='<?php echo e(url('weixin/staff')); ?>'" />
                    <input type="button" value="在线客服" onclick="window.location.href='<?php echo e(url('weixin/staff?status=online')); ?>'" />
                </div>
            </div>
            <!--快捷导航 结束-->
        </div>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <?php if($staffData !== null): ?>
                    <tr>
                        <th class="tc">客服账号</th>
                        <th class="tc">头像</th>
                        <th class="tc">客服ID</th>
                        <th class="tc">昵称</th>
                        <th class="tc">账号</th>
                        <th class="tc" width="8%;">操作</th>
                    </tr>
                    <?php foreach($staffData as $v): ?>
                    <tr>
                        <td class="tc"><?php echo e(explode('@', $v['kf_account'])[0]); ?></td>
                        <td class="tc"><img src="<?php echo e($v['kf_headimgurl']); ?>" width="80" height="80" /></td>
                        <td class="tc"><?php echo e($v['kf_id']); ?></td>
                        <td class="tc"><?php echo e($v['kf_nick']); ?></td>
                        <td class="tc"><?php echo array_key_exists('kf_wx', $v) ? e($v['kf_wx']) : '未绑定账号'; ?></td>
                        <td class="tc">
                            <a href="<?php echo e(url('weixin/staff_edit?email='.$v['kf_account'].'&nickname='.$v['kf_nick'].'&invite_wx='.(array_key_exists('kf_wx', $v) ? e($v['kf_wx']) : '0').'&headimg='.urlencode($v['kf_headimgurl']))); ?>">编辑</a>
                            <a href="javascript:void(0);" email="<?php echo e($v['kf_account']); ?>" nickname="<?php echo e($v['kf_nick']); ?>" password="123456" onclick="del(this);">删除</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if($onlineStaffData !== null): ?>
                    <tr>
                        <th class="tc">客服账号</th>
                        <th class="tc">客服ID</th>
                    </tr>
                    <?php foreach($onlineStaffData as $v): ?>
                    <tr>
                        <td class="tc"><?php echo e(explode('@', $v['kf_account'])[0]); ?></td>
                        <td class="tc"><?php echo e($v['kf_id']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->
    <script>
        function del(obj){
            if(confirm('确定要删除吗？')){
                var email = $(obj).attr('email');
                var nickname = $(obj).attr('nickname');
                var password = $(obj).attr('password');

                $.post('<?php echo e(url('weixin/staff_delete')); ?>', {'email':email, 'nickname':nickname, 'password':password, '_token':'<?php echo e(csrf_token()); ?>'}, function(data){
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
