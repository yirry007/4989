<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo e(asset('/public/we/css/ch-ui.admin.css')); ?>" />
	<link rel="stylesheet" href="<?php echo e(asset('/public/we/font/css/font-awesome.min.css')); ?>" />
	<script type="text/javascript" src="<?php echo e(asset('/public/we/js/jquery.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('/public/we/js/ch-ui.admin.js')); ?>"></script>
</head>
<body>


@yield('content')


<?php if(session('error') || count($errors) > 0): ?>
<script>
    alert('<?php echo count($errors) > 0 ? e($errors->all()[0]): e(session('error')); ?>');
</script>
<?php endif; ?>
</body>
</html>