@extends('admin.layout')
@section('content')
	<script>
		if(window.top != window){
			window.top.location.href = document.location.href;
		}
	</script>
	<link href="<?php echo e(asset('public/root/skin/default/skin.css')); ?>" rel="stylesheet" type="text/css"/>

	<header class="Hui-header cl"> <a class="Hui-logo l" title="HIKERSHOP" href="<?php echo e(url('/admin')); ?>">HIKER</a> <a class="Hui-logo-m l" href="<?php echo e(url('/admin')); ?>" title="HIKERSHOP">HKSP</a>
		<ul class="Hui-userbar">
			<li>
				<?php if(session('admin')->id == 1): ?>
				SuperAdmin
				<?php endif; ?>
			</li>
			<li class="dropDown dropDown_hover"><a href="#" class="dropDown_A">{{session('admin')->username}} <i class="Hui-iconfont">&#xe6d5;</i></a>
				<ul class="dropDown-menu radius box-shadow">
					<li><a href="<?php echo e(url('/admin/admin/'.session('admin')->id.'/edit?page=')) ?>">Info</a></li>
					<li><a href="javascript:void(0);" onclick="ajaxDeleteCache();">Clear Cache</a></li>
					<li><a href="{{url('/admin/logout')}}">Logout</a></li>
				</ul>
			</li>
			<!-- <li id="Hui-msg"> <a href="#" title="消息"><span class="badge badge-danger">1</span><i class="Hui-iconfont" style="font-size:18px">&#xe68a;</i></a> </li> -->
		</ul>
		<a href="javascript:;" class="Hui-nav-toggle Hui-iconfont" aria-hidden="false">&#xe667;</a> </header>
	<aside class="Hui-aside">
		<input runat="server" id="divScrollValue" type="hidden" value="" />
		<div class="menu_dropdown bk_2">
			<dl>
				<dt><a class="admin_menu" data-title="Admin" href="javascript:void(0)" _href="<?php echo e(url('/admin/admin')); ?>"><i class="Hui-iconfont">&#xe608;</i> Admin</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Member Group" href="javascript:void(0)" _href="<?php echo e(url('/admin/member_groups')); ?>"><i class="Hui-iconfont">&#xe608;</i> Member Group</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Members" href="javascript:void(0)" _href="<?php echo e(url('/admin/members')); ?>"><i class="Hui-iconfont">&#xe608;</i> Members</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Check In" href="javascript:void(0)" _href="<?php echo e(url('/admin/sign')); ?>"><i class="Hui-iconfont">&#xe608;</i> Check In</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Red Pocket" href="javascript:void(0)" _href="<?php echo e(url('/admin/bonus_list')); ?>"><i class="Hui-iconfont">&#xe608;</i> Red Pocket</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Top Up Money" href="javascript:void(0)" _href="<?php echo e(url('/admin/topup_moneys')); ?>"><i class="Hui-iconfont">&#xe608;</i> Top Up Money</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Top Up Orders" href="javascript:void(0)" _href="<?php echo e(url('/admin/topups')); ?>"><i class="Hui-iconfont">&#xe608;</i> Top Up Orders</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Withdraw" href="javascript:void(0)" _href="<?php echo e(url('/admin/withdraw')); ?>"><i class="Hui-iconfont">&#xe608;</i> Withdraw</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Articles" href="javascript:void(0)" _href="<?php echo e(url('/admin/articles')); ?>"><i class="Hui-iconfont">&#xe608;</i> Articles</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Ads" href="javascript:void(0)" _href="<?php echo e(url('/admin/ads')); ?>"><i class="Hui-iconfont">&#xe608;</i> Ads</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Banners" href="javascript:void(0)" _href="<?php echo e(url('/admin/banners')); ?>"><i class="Hui-iconfont">&#xe608;</i> Banners</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Categories" href="javascript:void(0)" _href="<?php echo e(url('/admin/categories')); ?>"><i class="Hui-iconfont">&#xe608;</i> Categories</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Brands" href="javascript:void(0)" _href="<?php echo e(url('/admin/brands')); ?>"><i class="Hui-iconfont">&#xe608;</i> Brands</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Goods" href="javascript:void(0)" _href="<?php echo e(url('/admin/goods')); ?>"><i class="Hui-iconfont">&#xe608;</i> Goods</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Orders" href="javascript:void(0)" _href="<?php echo e(url('/admin/orders')); ?>"><i class="Hui-iconfont">&#xe608;</i> Orders</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Partners" href="javascript:void(0)" _href="<?php echo e(url('/admin/partners')); ?>"><i class="Hui-iconfont">&#xe608;</i> Partners</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Benefit" href="javascript:void(0)" _href="<?php echo e(url('/admin/benefit')); ?>"><i class="Hui-iconfont">&#xe608;</i> Benefit</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Partner Ads" href="javascript:void(0)" _href="<?php echo e(url('/admin/bus_ads')); ?>"><i class="Hui-iconfont">&#xe608;</i> Partner Ads</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Sales Volume" href="javascript:void(0)" _href="<?php echo e(url('/admin/sale_goods')); ?>"><i class="Hui-iconfont">&#xe608;</i> Sales Volume</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Area Statistics" href="javascript:void(0)" _href="<?php echo e(url('/admin/areas')); ?>"><i class="Hui-iconfont">&#xe608;</i> Area Statistics</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Express" href="javascript:void(0)" _href="<?php echo e(url('/admin/express')); ?>"><i class="Hui-iconfont">&#xe608;</i> Express</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Email" href="javascript:void(0)" _href="<?php echo e(url('/admin/email')); ?>"><i class="Hui-iconfont">&#xe608;</i> Email</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="System Config" href="javascript:void(0)" _href="<?php echo e(url('/admin/system')); ?>"><i class="Hui-iconfont">&#xe608;</i> System Config</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Env Config" href="javascript:void(0)" _href="<?php echo e(url('/admin/env')); ?>"><i class="Hui-iconfont">&#xe608;</i> Env Config</a></dt>
			</dl>
			<dl>
				<dt><a class="admin_menu" data-title="Suggest" href="javascript:void(0)" _href="<?php echo e(url('/admin/suggest')); ?>"><i class="Hui-iconfont">&#xe608;</i> Suggest</a></dt>
			</dl>
			<!--
			<dl>
				<dt><a class="admin_menu" href="<?php echo e(url('/weixin')); ?>"><i class="Hui-iconfont">&#xe608;</i> Official Account</a></dt>
			</dl>
			-->
		</div>
	</aside>
	<div class="dislpayArrow"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
	<section class="Hui-article-box">
		<div id="Hui-tabNav" class="Hui-tabNav">
			<div class="Hui-tabNav-wp">
				<ul id="min_title_list" class="acrossTab cl">
					<li class="active"><span title="Main" data-href="main.html">Main</span><em></em></li>
				</ul>
			</div>
			<div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
		</div>
		<div id="iframe_box" class="Hui-article">
			<div class="show_iframe">
				<div style="display:none" class="loading"></div>
				<iframe scrolling="yes" frameborder="0" src="{{url('/admin/main')}}"></iframe>
			</div>
		</div>
	</section>
	<script>
		function ajaxDeleteCache(){
			layer.confirm('Are you sure to clear the cache?', {
				btn : ['Yes', 'No']
			}, function(){
				$.post('<?php echo e(url('/admin/delete_cache')) ?>', {'_token':'<?php echo e(csrf_token()); ?>'}, function(data){
					if(data.status == 0){
						layer.msg(data.msg, {icon:1, time:2000});
					}
				})
			}, function(){
				layer.closeAll();
			});
		}
	</script>
@endsection