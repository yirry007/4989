@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">添加消息模板</a>
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="<?php echo e(url('weixin/template_add')); ?>" method="post">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="160">提示：</th>
                    <td>具体模板编号请查看微信公众号平台的模板库（模板太多了，不列举了）</td>
                </tr>
                <tr>
                    <th><i class="require">*</i>模板前缀：</th>
                    <td>
                        <select name="prefix">
                            <option value="TM">TM</option>
                            <option value="OPENTM">OPENTM</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="160"><i class="require">*</i>模板编号：</th>
                    <td>
                        <input type="text" class="lg" name="short_id" onkeyup="this.value=this.value.replace(/[^\d]/g,'');" />
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
