@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">用户管理</a>
    </div>
    <!--面包屑导航 结束-->

    <!--用户移动到指定用户组-->
    <div class="search_wrap">
        <table class="search_tab">
            <tr>
                <th width="120">选择用户组:</th>
                <td>
                    <select name="group_id">
                        <?php foreach($groupListAll as $v): ?>
                        <option value="<?php echo e($v['id']); ?>"><?php echo e($v['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="button" value="移动用户组" onclick="moveUsers();"></td>
            </tr>
        </table>
    </div>

    <!--搜索结果页面 列表 开始-->
    <form action="#" method="post">
        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc" width="5%"><input type="checkbox" name=""></th>
                        <th class="tc">openid</th>
                        <th class="tc">昵称</th>
                        <th class="tc">性别</th>
                        <th class="tc">所在地</th>
                        <th class="tc">头像</th>
                        <th class="tc">关注时间</th>
                        <th class="tc">备注</th>
                        <th class="tc" width="8%;">操作</th>
                    </tr>
                    <?php foreach($userData as $v): ?>
                    <tr>
                        <td class="tc"><input type="checkbox" class="_openid" value="<?php echo e($v['openid']); ?>"></td>
                        <td class="tc"><?php echo e($v['openid']); ?></td>
                        <td class="tc"><?php echo e($v['nickname']); ?></td>
                        <td class="tc"><?php echo $v['sex'] == 1 ? '男' : '女'; ?></td>
                        <td class="tc"><?php echo e($v['country'].' '.$v['province'].' '.$v['city']); ?></td>
                        <td class="tc"><img src="<?php echo e($v['headimgurl']); ?>" width="80" height="80" /></td>
                        <td class="tc"><?php echo e(date('Y-m-d H:i', $v['subscribe_time'])); ?></td>
                        <td class="tc"><?php echo $v['remark'] ? e($v['remark']) : '无备注'; ?></td>
                        <td class="tc"><a href="<?php echo e(url('weixin/user_edit_view/'.$v['openid'])); ?>">编辑</a></td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                <div class="page_nav"><?php echo $pageShow; ?></div>

            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->
    <script>
        function moveUsers(){
            var group_id = $('select[name=group_id]').val();
            var input = $('input._openid:checked');
            var openid_arr = [];

            $(input).each(function(k, v){
                openid_arr.push($(v).val());
            });

            $.post('<?php echo e(url('weixin/move_users')); ?>', {'group_id':group_id, 'openid_arr':openid_arr, '_token':'<?php echo e(csrf_token()); ?>'}, function(data){
                if(data.status == 0){
                    window.location.reload();
                }else{
                    alert(data.msg);
                }
            });
        }
    </script>
@endsection
