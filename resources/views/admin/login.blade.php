<!DOCTYPE html>
<html class="no-js linen" lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Developr</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="user-scalable=0, initial-scale=1.0, target-densitydpi=115">
	<link rel="stylesheet" href="<?php echo e(asset('public/root/login/css/style.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('public/root/login/css/colors.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('public/root/login/css/styles/form.css')); ?>">
	<link rel="stylesheet" media="screen" href="<?php echo e(asset('public/root/login/css/login.css')); ?>">
</head>

<body>

	<div id="container">

		<hgroup id="login-title" class="large-margin-bottom">
			<h1 class="login-title-image">4989 West Market</h1>
			<h5>&copy; 嗨科网络科技有限公司</h5>
		</hgroup>

		<form method="post" action="{{url('/admin/login')}}" id="form-login">
			<input type="hidden" name="_token" value="{{csrf_token()}}" />
			<ul class="inputs black-input large">
				<li><input type="text" name="username" id="username" value="{{session('username')}}" class="input-unstyled" placeholder="Username" autocomplete="off"></li>
				<li><input type="password" name="password" id="password" value="" class="input-unstyled" placeholder="Password" autocomplete="off"></li>
				<li><input type="text" name="code" id="verify" value="" class="input-unstyled" placeholder="Code" autocomplete="off"><img src="<?php echo e(url('/admin/code')); ?>" onclick="this.src='{{url('/admin/code')}}?'+Math.random();"/></li>
			</ul>
			<button type="submit" class="button glossy full-width huge">Login</button>
		</form>

	</div>
	<script type="text/javascript" src="<?php echo e(asset('public/root/lib/jquery/1.9.1/jquery.min.js')); ?>"></script>
	<script type="text/javascript" src="<?php echo e(asset('public/root/lib/layer/2.1/layer.js')); ?>"></script>
	<?php if(session('error') || count($errors) > 0): ?>
	<script>
		layer.alert('<?php echo count($errors) > 0 ? e($errors->all()[0]): e(session('error')); ?>', {
			icon: 0,
			skin: 'layer-ext-moon'
		});
	</script>
	<?php endif; ?>
</body>
</html>