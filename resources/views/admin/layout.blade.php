<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/favicon.ico" >
<LINK rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/html5.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/respond.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/PIE_IE678.js')); ?>"></script>
<![endif]-->
<link href="<?php echo e(asset('public/root/css/H-ui.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('public/root/css/H-ui.admin.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('public/root/lib/Hui-iconfont/1.0.6/iconfont.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('public/root/css/style.css')); ?>" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/DD_belatedPNG_0.0.8a-min.js')); ?>" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/jquery/1.9.1/jquery.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/layer/2.1/layer.js')); ?>"></script>
<title>4989 West Market</title>
<meta name="keywords" content="HIKER">
<meta name="description" content="HIKER商城后台管理系统">
</head>
<body>

@section('content')
<?php if(session('error')): ?>
<script>
    layer.alert('<?php echo e(session('error')); ?>', {
        icon: 0,
        skin: 'layer-ext-moon'
    });
</script>
<?php endif; ?>
@show

<script type="text/javascript" src="<?php echo e(asset('public/root/js/H-ui.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('public/root/js/H-ui.admin.js')); ?>"></script>

<?php if(session('permission_deny')): ?>
<script>
layer.msg('<?php echo e(session('permission_deny')); ?>', {icon:2, time:2000});
</script>
<?php endif; ?>
</body>
</html>

<script>
    var UA = navigator.userAgent;
    var forIOS = function(){
        if(!UA.match(/iPad/) && !UA.match(/iPhone/) && !UA.match(/iPod/)){return;}
        if($('#wrapper').length){return;}
        $('body').children().not('script').wrapAll('<div id="wrapper"></div>');
    }();
</script>