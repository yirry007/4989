@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">消息群发</a>
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="<?php echo e(url('weixin/msg')); ?>" method="post">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="160"><i class="require">*</i>首行：</th>
                    <td>
                        <input type="text" class="lg" name="first">
                    </td>
                </tr>
                <tr>
                    <th width="160"><i class="require">*</i>店铺名称：</th>
                    <td>
                        <input type="text" class="lg" name="keyword1">
                    </td>
                </tr>
                <tr>
                    <th width="160"><i class="require">*</i>店铺等级：</th>
                    <td>
                        <input type="text" class="lg" name="keyword2">
                    </td>
                </tr>
                <tr>
                    <th width="160"><i class="require">*</i>销售类别：</th>
                    <td>
                        <input type="text" class="lg" name="keyword3">
                    </td>
                </tr>
                <tr>
                    <th width="160"><i class="require">*</i>结束语：</th>
                    <td>
                        <input type="text" class="lg" name="remark">
                    </td>
                </tr>
                <tr>
                    <th width="160"><i class="require">*</i>链接：</th>
                    <td>
                        <input type="text" class="lg" name="url">
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
