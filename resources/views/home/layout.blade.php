<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"><!--无视ie8-->
    <meta name="format-detection" content="telephone=no"><!--iPhone号码超链接-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"><!--禁止放大-->
    <title><?php echo e($_SYSTEM['sitename']); ?>@yield('title')</title>
    <script src="http://libs.baidu.com/jquery/1.8.3/jquery.min.js"></script>
    <script>
        var htmlWidth = window.screen.width;
        $('html').css('font-size', htmlWidth /24 + 'px');
    </script>
    <link href="<?php echo e(asset('public/home/css/iconfont.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset('public/home/css/style.css')); ?>" rel="stylesheet" type="text/css" />
    @yield('head')
    <link rel="stylesheet" href="<?php echo e(asset('public/home/msg/alert.css')); ?>">
    <script src="<?php echo e(asset('public/home/msg/alert.js')); ?>"></script>
    <script src="<?php echo e(asset('public/home/msg/dialog.js')); ?>"></script>
</head>
<body>
<!--微信分享111-->
<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
    wx.config({
        debug: false,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: '<?php echo $signPackage["timestamp"];?>',
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage'
        ]
    });

    wx.ready(function () {
        $(function(){

            var share_link = $('input[name=share_link]').val();
            if(!share_link)share_link = '<?php echo url('index'); ?>';

            wx.onMenuShareAppMessage({
                title: '<?php echo e($_SYSTEM["sitename"]); ?>',
                desc: '<?php echo e($_SYSTEM["siteinfo"]); ?>',
                link: share_link,
                imgUrl: '<?php echo url('/public/'.$_SYSTEM["sharelogo"]); ?>'
            });

            wx.onMenuShareTimeline({
                title: '<?php echo e($_SYSTEM["sitename"]); ?>',
                link: share_link,
                imgUrl: '<?php echo url('/public/'.$_SYSTEM["sharelogo"]); ?>'
            });
        });
    });

	var global_cart_sending = '0';
    function globalAddCart(obj){
		if (global_cart_sending === '1') {
			return false;
		}
		
		global_cart_sending = '1';
		
        var goods_id = $(obj).attr('goods_id');
        var goods_num = $(obj).attr('limit_num');
        var goods_stock = $(obj).attr('stock');

        $.post('<?php echo e(url('ajax_add_cart')); ?>', {'goods_id': goods_id,'goods_num': goods_num,'goods_stock': goods_stock,'_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
			global_cart_sending = '0';
			
            if(data.status == 691){
                window.location.href = '<?php echo e(url('login')); ?>';
            } else {
                dialogMsgOkno(data.msg, function(){
                    window.location.href = '<?php echo e(url('cart')); ?>';
                }, false, {'confirm':'前往购物车', 'cancel':'继续购物'});
            }
        });
    }
</script>
@section('content')

@show

@yield('script')

</body>
</html>
