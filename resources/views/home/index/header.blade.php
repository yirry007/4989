<div style="height:64px;"></div>
<img src="<?php echo e(url('public/home/img/index/weixin-qr.jpg')); ?>" class="weixin-qr" onclick="weixinClose()" alt="" />
<div class="black-qr" onclick="weixinClose()"></div>
<div class="header">
    <a class="to_my" href="<?php echo e(url('my')); ?>">
        <img src="<?php echo e(url('public/home/img/logo.png')); ?>" id="header_img" />
    </a>
    <div class="header_m">
        <a class="to_nav" href="<?php echo e(url('nav')); ?>">Goods Search</a>
        <a class="to_brand" href="<?php echo e(url('/brand')); ?>">
            <img src="<?php echo e(url('public/home/img/brand.jpg')); ?>" />
            <p>Brand</p>
        </a>
    </div>
    <a class="head_cart" href="<?php echo e(url('cart')); ?>">
        <img src="<?php echo e(url('public/home/img/edit/cart.png')); ?>" />
        <p>Cart</p>
        <em></em>
    </a>
    <a class="show_qr" href="javascript:void(0);" onclick="weixin();">
        <img src="<?php echo e(url('public/home/img/index/foot-icon0.png')); ?>" />
        <p>Follow</p>
    </a>
</div>

<script>
    //微信二维码
    $(function(){
        $.get('<?php echo e(url('get_header_info')); ?>', function(data){
            $('#header_img').attr('src', data.portrait);
        });
    });
    function weixin() {
        $('.weixin-qr').fadeIn(300);
        $('.black-qr').fadeIn(300);
    }
    function weixinClose() {
        $('.weixin-qr').fadeOut(300);
        $('.black-qr').fadeOut(300);
    }
</script>