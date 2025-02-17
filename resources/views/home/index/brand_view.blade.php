@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-brand.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <input type="hidden" value="<?php echo url('/brand_view/'.$brandData->id); ?>" name="share_link" />
    <div class="contaner">
        <div class="brand-view clearfix">
            <div class="brand-img"><img src="<?php echo e(url('public/'.($brandData->image ? $brandData->image : $_SYSTEM['defaultportrait']))); ?>" alt="" /></div>
            <div class="brand-txt">
                <p><?php echo e(str_replace('@', '&nbsp;', $brandData->name)); ?></p>
                <span>Stock <em><?php echo e($goodsCount); ?></em></span>
            </div>
            <div class="brand-btn">
                <a href="javascript:void(0);" class="<?php echo $isFavor; ?>" onclick="ajaxBrandFavor(this);" brand_id="<?php echo e($brandData->id); ?>">
                    <i class="iconfont icon-shoucang"></i>
                    <p>Favorite</p>
                </a>
            </div>
        </div>

        <?php if(!empty($koreabrand)): ?>
        <div class="iscroll" id="i-scroll-1">
            <div class="scroller">
                <ul class="clearfix">
                    <?php foreach($koreabrand as $v): ?>
                    <li><a href="<?php echo e(url('/brand_view/'.$v->id)) ?>"><?php echo e(explode('@', $v->name)[0]); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <?php if(!$brandGoodsData): ?>
        <div class="brand_goods_null">No Data</div>
        <?php else: ?>
        <ul class="lists">
            <?php foreach($brandGoodsData as $v): ?>
            <li>
                <a href="<?php echo e(url('/goods_view/'.$v->id)); ?>" class="goods-info">
                    <p>
                        <img src="<?php echo e(url('public/'.($v->image ? $v->image : $_SYSTEM['defaultportrait']))); ?>" alt="" />
                    </p>
                    <h2>
                        <strong><?php echo e($v->name); ?></strong>
                        <span><?php echo e($v->other_name); ?></span>
                    </h2>
                    <div>
                        <strong>￥ <?php echo e($v->org_price); ?></strong>
                        <br/>
                        <span>￥ <?php echo e($v->price); ?></span>
                        <em>VIP Price</em>
                    </div>
                </a>
                <?php if(!$v->special): ?>
                <a href="javascript:void(0);" class="list-cart" goods_id="<?php echo e($v->id); ?>" limit_num="<?php echo e($v->limit_num); ?>" stock="<?php echo e($v->stock); ?>" onclick="globalAddCart(this);"></a>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>

    <a href="javascript:void(0);" sending="0" class="more-bt">Load More</a>

    <a href="{{ url('nav') }}" class="to-nav">Goods<br/>Search</a>

    <div style="clear:both; height: 4rem;"></div>

    @include('home.index.footer')

@endsection

@section('script')
    <script src="<?php echo e(asset('public/home/js/iscroll.min.js')); ?>"></script>
    <script src="<?php echo e(asset('public/home/js/navbarscroll.js')); ?>"></script>
    <script>
        //滚动分类
        $(function(){
            $('.iscroll').navbarscroll();
        });

        //收藏品牌
        function ajaxBrandFavor(obj) {
            var brand_id = $(obj).attr('brand_id');

            if (!brand_id || brand_id == '0') {
                dialogMsg('error: Get ID Failed');
                return false;
            }

            $.post('<?php echo e(url('ajax_brand_favor')); ?>', {'brand_id': brand_id,'_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                if (data.status == 0) {
                    dialogMsg(data.msg);
                    $('.brand-btn').find('a').addClass('v');
                } else if(data.status == 691){
                    window.location.href = '<?php echo e(url('login')); ?>';
                } else {
                    dialogMsg(data.msg);
                }
            });
        }

        //微信二维码
        function weixin() {
            $('.weixin-qr').fadeIn(300);
            $('.black-qr').fadeIn(300);
        }
        function weixinClose() {
            $('.weixin-qr').fadeOut(300);
            $('.black-qr').fadeOut(300);
        }

        //加载更多
        var oWin = $(window);
        var page = 1;
        var url = '<?php echo e(url()); ?>';
        var headOffset = 64;
        $(window).scroll(function(){
            //屏幕滑动时超出屏幕的视频暂停
            $('video').each(function(k, v){
                if($(v).offset().top < oWin.scrollTop() + headOffset || $(v).offset().top + $(v).outerHeight() > oWin.scrollTop() + oWin.height()){
                    $(v).trigger('pause');
                }
            });

            var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
            if($(document).height() <= totalheight){
                var sending = $('.more-bt').attr('sending');
                if (sending === '0') {
                    $('.more-bt').attr('sending', 1);
                    $('.more-bt').html('Loading...');

                    var id = '<?php echo e($brandData->id); ?>';

                    $.get('<?php echo e(url('ajax_get_brand_goods')) ?>?id=' + id + '&page=' + page, function (data) {
                        if (data.length) {
                            var html = '';

                            $(data).each(function (k, v) {
                                var headimg = '';

                                if(v.image && v.image !== ''){
                                    headimg = url+'/public/'+v.image;
                                }else{
                                    headimg = url+'/public/<?php echo e($_SYSTEM['defaultportrait']); ?>';
                                }

                                var brandimg = '';

                                if(v.brand_image && v.brand_image !== ''){
                                    brandimg = url+'/public/'+v.brand_image;
                                }else{
                                    brandimg = url+'/public/<?php echo e($_SYSTEM['defaultportrait']); ?>';
                                }

                                var names = v.brand_name ? v.brand_name.split('@') : ['4989 Market', '4989 Market'];

                                var expressimg = '<?php echo e(url('public/home/img/edit/express.png')); ?>';
                                var cartimg = '<?php echo e(url('public/home/img/edit/cart.png')); ?>';

                                var weight = v.weight === '0.00' ? 'Free Shipping' : v.weight + 'kg';

                                var add_to_cart = '';
                                if (!v.special) {
                                    add_to_cart = '<a href="javascript:void(0);" class="list-cart" goods_id="'+v.id+'" limit_num="'+v.limit_num+'" stock="'+v.stock+'" onclick="globalAddCart(this);"></a>';
                                }

                                html += '\
                                <li>\
                                    <a href="'+url+'/goods_view/'+v.id+'" class="goods-info">\
                                        <p>\
                                            <img src="'+headimg+'" alt="" />\
                                        </p>\
                                        <h2>\
                                            <strong>'+v.name+'</strong>\
                                            <span>'+v.other_name+'</span>\
                                        </h2>\
                                        <div>\
                                            <strong>￥ '+v.org_price+'</strong>\
                                            <br/>\
                                            <span>￥ '+v.price+'</span>\
                                            <em>VIP Price</em>\
                                        </div>\
                                    </a>\
                                    '+add_to_cart+'\
                                </li>';
                            });

                            $('.lists').append(html);

                            page++;

                            $('.more-bt').html('Load More');
                            $('.more-bt').attr('sending', 0);
                        } else {
                            $('.more-bt').html('No Data');
                            $('.more-bt').removeAttr('sending');
                        }
                    });
                }
            }
        });

        wx.ready(function () {
            $(function(){
                var share_link = $('input[name=share_link]').val();
                if(!share_link)share_link = '<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>';

                wx.onMenuShareAppMessage({
                    title: '<?php echo e($brandData->name); ?>',
                    desc: '<?php echo e($_SYSTEM["siteinfo"]); ?>',
                    link: share_link,
                    imgUrl: '<?php echo $brandData->image ? e(url('public/'.$brandData->image)) : 'http://'.$_SERVER['HTTP_HOST'].'/public/'.$_SYSTEM["sharelogo"]; ?>'
                });

                wx.onMenuShareTimeline({
                    title: '<?php echo e($brandData->name); ?>',
                    link: share_link,
                    imgUrl: '<?php echo $brandData->image ? e(url('public/'.$brandData->image)) : 'http://'.$_SERVER['HTTP_HOST'].'/public/'.$_SYSTEM["sharelogo"]; ?>'
                });
            });
        });
    </script>
@endsection