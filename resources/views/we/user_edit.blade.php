@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">用户编辑</a>
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="<?php echo e(url('weixin/user_edit')); ?>" method="post">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <input type="hidden" name="openid" value="<?php echo e($userData['openid']); ?>" />
            <input type="hidden" name="tag_id" value="<?php echo e(json_encode($tagList)); ?>" />
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="160">openid：</th>
                    <td><?php echo e($userData['openid']); ?></td>
                </tr>
                <tr>
                    <th>昵称：</th>
                    <td><?php echo e($userData['nickname']); ?></td>
                </tr>
                <tr>
                    <th>性别：</th>
                    <td><?php echo $userData['sex'] == 1 ? '男' : '女'; ?></td>
                </tr>
                <tr>
                    <th>语言：</th>
                    <td><?php echo e($userData['language']); ?></td>
                </tr>
                <tr>
                    <th>所在地：</th>
                    <td><?php echo e($userData['country'].' '.$userData['province'].' '.$userData['city']); ?></td>
                </tr>
                <tr>
                    <th>头像：</th>
                    <td><img src="<?php echo e($userData['headimgurl']); ?>" /></td>
                </tr>
                <tr>
                    <th>关注时间：</th>
                    <td><?php echo e(date('Y-m-d H:i', $userData['subscribe_time'])); ?></td>
                </tr>
                <tr>
                    <th>备注：</th>
                    <td>
                        <input type="text" name="remark" value="<?php echo e($userData['remark']); ?>" />
                    </td>
                </tr>
                <tr>
                    <th>用户标签：</th>
                    <td>
                        <?php
                            foreach($tagList as $v):
                            if(in_array($v['id'], $userTag)){
                                $check = 'checked';
                            }else{
                                $check = '';
                            }
                        ?>
                        <label><input type="checkbox" name="has_tag_id[]" value="<?php echo e($v['id']); ?>" <?php echo e($check); ?> /><?php echo e($v['name']); ?></label>
                        <?php endforeach; ?>
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
@endsection
