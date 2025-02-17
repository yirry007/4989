<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<title>微信公众号管理系统</title> 
<link href="<?php echo e(asset('/public/we/login/css/login.css')); ?>" type="text/css" rel="stylesheet">
</head> 
<body> 

<div class="login">
    <div class="message">微信公众号管理系统</div>
    <div id="darkbannerwrap"></div>
    
    <form method="post" action="<?php echo e(url('/public/weixin/login')); ?>">
		<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
		<input name="username" placeholder="用户名" type="text" value="<?php echo e(session('username')); ?>" />
		<hr class="hr15" />
		<input name="password" placeholder="密码" type="password" />
		<hr class="hr15" />
		<input name="code" placeholder="验证码" style="width:60%;" type="text" />
		<img src="<?php echo e(url('/public/weixin/code')); ?>" onclick="this.src='<?php echo e(url('/public/weixin/code')); ?>?'+Math.random();"/>
		<hr class="hr15" />
		<input value="登录" style="width:100%;" type="submit" />
		<hr class="hr20" />
	</form>
	
</div>

<?php if(session('error') || count($errors) > 0): ?>
<script>
    alert('<?php echo count($errors) > 0 ? e($errors->all()[0]): e(session('error')); ?>');
</script>
<?php endif; ?>

</body>
</html>