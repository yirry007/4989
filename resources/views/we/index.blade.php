@extends('we.layout')


@section('content')
	<script>
        if(window.top != window){
            window.top.location.href = document.location.href;
        }
	</script>
	<!--头部 开始-->
	<div class="top_box">
		<div class="top_left">
			<div class="logo">微信公众号管理</div>
			<ul>
				<li><a href="<?php echo e(url('weixin')); ?>" class="active">首页</a></li>
				<li><a href="<?php echo e(url('weixin/config')); ?>" target="main">配置</a></li>
				<li><a href="<?php echo e(url('weixin/user_subscribe')); ?>" >刷新关注</a></li>
				<li><a href="<?php echo e(url('weixin/msg')); ?>" target="main">消息群发</a></li>
				<!--<li><a href="<?php echo e(url('weixin/lst')); ?>" target="main">列表模板</a></li>-->
				<!--<li><a href="<?php echo e(url('weixin/add')); ?>" target="main">添加模板</a></li>-->
			</ul>
		</div>
		<div class="top_right">
			<ul>
				<li>管理员：<?php echo e(session('weixin')->username); ?></li>
				<!--<li><a href="<?php echo e(url('weixin/password')); ?>" target="main">修改密码</a></li>-->
				<li><a href="<?php echo e(url('admin/logout')); ?>">退出</a></li>
			</ul>
		</div>
	</div>
	<!--头部 结束-->

	<!--左侧导航 开始-->
	<div class="menu_box">
		<ul>
			<li>
				<h3><i class="fa fa-fw fa-star"></i>常用功能</h3>
				<ul class="sub_menu">
					<li><a href="<?php echo e(url('weixin/subscribe_event')); ?>" target="main"><i class="fa fa-fw fa-eye"></i>关注事件</a></li>
					<li><a href="<?php echo e(url('weixin/reply')); ?>" target="main"><i class="fa fa-fw fa-comment-o"></i>自动回复</a></li>
				</ul>
			</li>
			<li>
				<h3><i class="fa fa-fw fa-th-large"></i>菜单管理</h3>
				<ul class="sub_menu">
					<li><a href="<?php echo e(url('weixin/menu')); ?>" target="main"><i class="fa fa-fw fa-list"></i>自定义菜单</a></li>
					<li><a href="<?php echo e(url('weixin/menu_event')); ?>" target="main"><i class="fa fa-fw fa-paperclip"></i>菜单事件</a></li>
				</ul>
			</li>
			<li>
				<h3><i class="fa fa-fw fa-envelope"></i>消息模块</h3>
				<ul class="sub_menu">
					<li><a href="javascript:void(0);" _href="<?php echo e(url('weixin/set_industry')); ?>" target="main"><i class="fa fa-fw fa-database"></i>设置行业</a></li>
					<li><a href="<?php echo e(url('weixin/template')); ?>" target="main"><i class="fa fa-fw fa-envelope-o"></i>模板消息</a></li>
					<li><a href="<?php echo e(url('weixin/broadcast')); ?>" target="main"><i class="fa fa-fw fa-envelope-square"></i>群发消息</a></li>
				</ul>
			</li>
			<li>
				<h3><i class="fa fa-fw fa-user"></i>用户模块</h3>
				<ul class="sub_menu">
					<li><a href="<?php echo e(url('weixin/user')); ?>" target="main"><i class="fa fa-fw fa-github-alt"></i>用户管理</a></li>
					<li><a href="<?php echo e(url('weixin/group')); ?>" target="main"><i class="fa fa-fw fa-users"></i>用户组管理</a></li>
					<li><a href="<?php echo e(url('weixin/tag')); ?>" target="main"><i class="fa fa-fw fa-tags"></i>标签管理</a></li>
				</ul>
			</li>
			<li>
				<h3><i class="fa fa-fw fa-cubes"></i>素材模块</h3>
				<ul class="sub_menu">
					<li><a href="<?php echo e(url('weixin/image')); ?>" target="main"><i class="fa fa-fw fa-photo"></i>图片素材</a></li>
					<li><a href="<?php echo e(url('weixin/voice')); ?>" target="main"><i class="fa fa-fw fa-volume-up"></i>声音素材</a></li>
					<li><a href="<?php echo e(url('weixin/video')); ?>" target="main"><i class="fa fa-fw fa-video-camera"></i>视频素材</a></li>
					<li><a href="<?php echo e(url('weixin/news')); ?>" target="main"><i class="fa fa-fw fa-delicious"></i>图文素材</a></li>
				</ul>
			</li>
			<li>
				<h3><i class="fa fa-fw fa-headphones"></i>客服模块</h3>
				<ul class="sub_menu">
					<li><a href="<?php echo e(url('weixin/staff')); ?>" target="main"><i class="fa fa-fw fa-comments-o"></i>客服管理</a></li>
					<li><a href="<?php echo e(url('weixin/staff_send')); ?>" target="main"><i class="fa fa-fw fa-reply-all"></i>消息发送</a></li>
				</ul>
			</li>
			<li>
				<h3><a href="<?php echo e(url('/admin')); ?>" style="display: block; color: #333; text-decoration: none;"><i class="fa fa-fw fa-desktop"></i>网站后台</a></h3>
			</li>
		</ul>
	</div>
	<!--左侧导航 结束-->

	<!--主体部分 开始-->
	<div class="main_box">
		<iframe src="<?php echo e(url('/weixin/main')); ?>" frameborder="0" width="100%" height="100%" name="main"></iframe>
	</div>
	<!--主体部分 结束-->

	<!--底部 开始-->
	<div class="bottom_box">
		CopyRight © 2018. Powered By 嗨科网络科技有限公司.
	</div>
	<!--底部 结束-->
@endsection