@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">新增标签</a>
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="<?php echo e(url('weixin/tag_edit')); ?>" method="post">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <input type="hidden" name="tag_id" value="<?php echo e($id); ?>" />
            <table class="add_tab">
                <tbody>
                <tr>
                    <th>ID：</th>
                    <td>
                        <?php echo e($id); ?>
                    </td>
                </tr>
                <tr>
                    <th>用户数：</th>
                    <td>
                        <?php echo e(app('request')->input('count')); ?>
                    </td>
                </tr>
                <tr>
                    <th>标签名称：</th>
                    <td>
                        <input type="text" class="lg" name="name" value="<?php echo e(app('request')->input('name')); ?>" />
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
