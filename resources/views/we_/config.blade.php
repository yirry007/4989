@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin/main')); ?>">首页</a> &raquo; 修改配置
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 开始-->
    <div class="result_wrap">
        <div class="result_title">
            <h3>修改配置</h3>
        </div>
    </div>
    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form method="post" action="<?php echo e(url('weixin/config')); ?>" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="160"><i class="require">*</i>公众号APPID：</th>
                    <td>
                        <input type="text" name="appid" class="lg" value="<?php echo e($config->appid); ?>" /> </i>请输入公众号APPID</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>公众号APPSECRET：</th>
                    <td>
                        <input type="text" name="appsecret" class="lg" value="<?php echo e($config->appsecret); ?>" /> </i>请输入公众号APPSECRET</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>Token：</th>
                    <td>
                        <input type="text" name="token" class="lg" value="<?php echo e($config->token); ?>" /> </i>请输入Token</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>AESKey：</th>
                    <td>
                        <input type="text" name="aeskey" class="lg" value="<?php echo e($config->aeskey); ?>" /> </i>请输入AESKey</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>原始ID：</th>
                    <td>
                        <input type="text" name="origin_id" class="lg" value="<?php echo e($config->origin_id); ?>" /> </i>原始ID</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>验证文件：</th>
                    <td><input type="file" name="valid_file"></td>
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