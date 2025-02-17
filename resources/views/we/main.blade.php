@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin/main')); ?>">首页</a>
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 开始-->
    <!--<div class="result_wrap">
        <div class="result_title">
            <h3>快捷操作</h3>
        </div>
        <div class="result_content">
            <div class="short_wrap">
                <a href="#"><i class="fa fa-plus"></i>新增文章</a>
                <a href="#"><i class="fa fa-recycle"></i>批量删除</a>
                <a href="#"><i class="fa fa-refresh"></i>更新排序</a>
            </div>
        </div>
    </div>-->
    <!--结果集标题与导航组件 结束-->


    <div class="result_wrap">
        <div class="result_title">
            <h3>系统基本信息</h3>
        </div>
        <div class="result_content">
            <ul>
                <li>
                    <label>操作系统</label><span><?php echo php_uname(); ?></span>
                </li>
                <li>
                    <label>运行环境</label><span><?php echo php_sapi_name(); ?></span>
                </li>
                <li>
                    <label>MySQL 版本</label><span><?php echo $mysqlVersion[0]->version; ?></span>
                </li>
                <li>
                    <label>PHP 版本</label><span><?php echo PHP_VERSION; ?></span>
                </li>
                <li>
                    <label>上传附件限制</label><span><?php echo get_cfg_var ("upload_max_filesize") ? get_cfg_var ("upload_max_filesize") : "不允许上传附件"; ?></span>
                </li>
                <li>
                    <label>时区设置</label><span><?php echo date_default_timezone_get(); ?></span>
                </li>
                <li>
                    <label>北京时间</label><span><?php echo date("Y-m-d G:i:s"); ?></span>
                </li>
                <li>
                    <label>服务器IP</label><span><?php echo GetHostByName($_SERVER['SERVER_NAME']); ?></span>
                </li>
                <li>
                    <label>服务器域名</label><span><?php echo $_SERVER['HTTP_HOST']; ?></span>
                </li>
            </ul>
        </div>
    </div>


    <div class="result_wrap">
        <div class="result_title">
            <h3>使用帮助</h3>
        </div>
        <div class="result_content">
            <ul>
                <li>
                    <label>官方技术站点：</label><span><a href="http://hiker-vip.com" target="_blank">http://hiker-vip.com</a></span>
                </li>
                <li>
                    <label>开发团队：</label><span><a href="javascript:void(0);">嗨科网络科技有限公司</a></span>
                </li>
            </ul>
        </div>
    </div>
    <!--结果集列表组件 结束-->
@endsection