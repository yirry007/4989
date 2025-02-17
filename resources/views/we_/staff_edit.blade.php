@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">修改客服</a>
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="<?php echo e(url('weixin/staff_edit')); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <input type="hidden" name="kf_wx" value="<?php echo e(app('request')->input('invite_wx')); ?>" />
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="160"><i class="require">*</i>客服账号：</th>
                    <td>
                        <input type="text" class="lg" name="email" value="<?php echo e(explode('@', app('request')->input('email'))[0]); ?>" readonly />
                    </td>
                </tr>
                <tr>
                    <th width="160"><i class="require">*</i>昵称：</th>
                    <td>
                        <input type="text" class="lg" name="nickname" value="<?php echo e(app('request')->input('nickname')); ?>" />
                    </td>
                </tr>
                <tr>
                    <th width="160"><i class="require">*</i>密码：</th>
                    <td>
                        <input type="text" class="lg" name="password" value="123456" />
                    </td>
                </tr>
                <tr>
                    <th width="160"><i class="require">*</i>绑定微信号：</th>
                    <td>
                        <input type="text" class="lg" name="invite_wx" value="<?php echo e(app('request')->input('invite_wx') ?: ''); ?>" />
                    </td>
                </tr>
                <tr>
                    <th width="160"><i class="require">*</i>客服头像：</th>
                    <td>
                        <img src="<?php echo e(urldecode(app('request')->input('headimg'))); ?>" width="120" height="120" /><br/>
                        <input type="file" name="headimg">
                        <p><i class="require">*</i>640x640 的 jpg 文件（本地文件的绝对路径）</p>
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
