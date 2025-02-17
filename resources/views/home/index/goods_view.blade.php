@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-goods.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <input type="hidden" value="<?php echo url('goods_view/'.$goodsData->id); ?>" name="share_link" />
    <input type="hidden" name="stock" value="<?php echo e($goodsData->stock); ?>" />
    <div class="contaner">
        <div class="black" onclick="popClose()" ></div>
        <div class="view-pop">
            <div class="pop-cont">
                <div class="img"><img src="<?php echo e(url('public/'.$goodsData->image)); ?>" alt="" /></div>
                <div class="txt">
                    <div class="txt1">
                        <strong>￥ <?php echo e($goodsData->org_price); ?></strong>
                        <br/>
                        <span>￥ <?php echo e($goodsData->price); ?></span>
                        <em>VIP Price</em>
                    </div>
                    <div class="goods-num clearfix" limit_num="<?php echo e($goodsData->limit_num); ?>">
                        <div class="less" onclick="ajaxLessCart(this)"><img src="<?php echo e(url('public/home/img/index/less-icon2.jpg')); ?>" alt="" /></div>
                        <input type="text" class="num-input" name="num" value="<?php echo e($goodsData->limit_num); ?>" onblur="ajaxEnterCart(this)" onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" />
                        <div class="plus" onclick="ajaxPlusCart(this)"><img src="<?php echo e(url('public/home/img/index/plus-icon2.jpg')); ?>" alt="" /></div>
                        <div class="hint"><?php echo e($goodsData->limit_num); ?> at least</div>
                    </div>
                </div>
            </div>
            <div class="view-btn" goods_id="<?php echo e($goodsData->id); ?>">
                <a href="javascript:void(0);" onclick="ajaxAddCart();" class="add_cart_btn" sending="0">Add To Cart</a>
                <a href="javascript:void(0);" onclick="ajaxAddBuy(this);" sending="0">Buy</a>
            </div>
        </div>


        <img src="<?php echo e(url('/public/'.$_SYSTEM['service'])); ?>" class="weixin-qr" onclick="weixinClose()" alt="" />
        <div class="black-qr" onclick="weixinClose()"></div>
        <div class="icon-pop">
            <a href="<?php echo url('/index'); ?>"><img src="<?php echo e(url('public/home/img/index/goods_icon1.png')); ?>" alt="" /><br />Main</a>
        <!--<a href="javascript:void(0);" onclick="weixin()"><img src="<?php echo e(url('public/home/img/index/goods_icon2.png')); ?>" alt="" /><br />客服</a>-->
        </div>

        <!--<div class="txt-pop">图片与实物可能存在差异，以实物为准！</div>-->

        <?php if($goodsData->is_sale == 1): ?>
        <div class="is_sale"><img src="<?php echo e(url('public/home/img/icon2.png')); ?>" alt="" /></div>
        <?php endif; ?>

        <div class="goods-img">
            <div class="flexslider">
                <ul class="slides">
                    <?php if($goodsData->video): ?>
                    <li><a href="javascript:void(0);"><video src="<?php echo e(url('public/'.$goodsData->video)); ?>" poster="<?php echo $goodsData->poster ? e(url('public/'.$goodsData->poster)) : e(url('public/home/img/poster.png')); ?>" controls muted x-webkit-airplay="true" webkit-playsinline="true" playsinline="true" x5-playsinline="true" x5-video-player-fullscreen="false" preload="auto" controlslist="nodownload"></video></a></li>
                    <?php endif; ?>
                    <li><a href="javascript:void(0);"><img src="<?php echo e(url('public/'.$goodsData->image)); ?>" alt="" /></a></li>
                    <?php foreach($goodsImage as $v): ?>
                    <li><a href="javascript:void(0);"><img src="<?php echo e(url('public/'.$v->img)); ?>" alt="" /></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <?php if($fiveday && $goodsData->fiveday): ?>
        <div class="fiveday_mark">
            <p>
                <i>
                    <img src="<?php echo e(url('public/home/img/fiveday.png')); ?>" alt="" />
                </i>
            </p>
            <div>
                <span>Origin Price</span>
                <strong><?php echo e($goodsData->market_price); ?></strong>
            </div>
            <div>
                <em>Stock <?php echo e($goodsData->stock); ?></em>
            </div>
        </div>
        <?php endif; ?>

        <div class="goods-txt">
            <div class="txt">
                <div>￥ <?php echo e($goodsData->org_price); ?></div>
                <div class="vip"><strong>￥ <?php echo e($goodsData->price); ?></strong><em>VIP Price</em></div>
            </div>
            <a href="javascript:void(0);" class="<?php echo $isFavor; ?>" onclick="ajaxGoodsFavor(this);" goods_id="<?php echo e($goodsData->id); ?>"><i class="iconfont icon-shoucang"></i><p>Favorite</p></a>
        </div>

        <div class="goods_info">
            <div class="goods_names">
                <h4><?php echo e($goodsData->name); ?></h4>
                <p><?php echo e($goodsData->other_name); ?></p>
            </div>
            <?php if($goodsData->express_id): ?>
            <div class="sub_info">
                <?php if($goodsData->express_id || $goodsData->is_one): ?>
                <p>Only Ship To<strong><?php echo e($goodsData->province_name); ?></strong></p>
                <?php endif; ?>
                <?php if($goodsData->is_one == 1): ?>
                <p>Add Wechat to single buy</p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <div class="goods_opt">
                <h5>Weight</h5>
                <p><?php echo $goodsData->weight === '0.00' ? 'Free Shipping' : e($goodsData->weight.' kg'); ?></p>
            </div>
            <div class="goods_opt">
                <h5>At Least</h5>
                <p><?php echo e($goodsData->limit_num) ?>个</p>
            </div>
            <?php if($goodsData->brands_id): ?>
            <div class="goods_opt">
                <h5>Brand</h5>
                <a href="<?php echo e(url('/brand_view/'.$goodsData->brands_id)); ?>">
                    <span><?php echo e(str_replace('@', '&nbsp;', $goodsData->brand_name)); ?></span>
                    <img src="<?php echo e(url('public/'.($goodsData->brand_image ? $goodsData->brand_image : $_SYSTEM['defaultportrait']))); ?>" />
                    <em></em>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <div class="goods-detail">
            <p>Description</p>
            <div class="detail-cont"><?php echo $goodsData->detail; ?></div>
        </div>

        <div class="rel_goods">
            <h2>You will like</h2>
        </div>

        <ul class="lists">
            <?php foreach($relGoods as $v): ?>
            <li>
                <a href="<?php echo e(url('/goods_view/'.$v->id)); ?>" class="goods-info">
                    <p>
                        <img src="<?php echo e(url('public/'.($v->image ? $v->image : $_SYSTEM['defaultportrait']))); ?>" alt="" />
                    </p>
                    <h2>
                        <strong><?php echo e($v->name); ?></strong>
                        <span><?php echo e($v->other_name); ?></span>
                        <em>
                            <i>Brand</i>
                            <?php echo e(str_replace('@', ' ', $v->brand_name)); ?>
                        </em>
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

        <!--
        <div class="foot-bt">
            <div class="clearfix">
                <a href="javascript:void(0);" onclick="weixin()" class="first"><img src="<?php echo e(url('public/home/img/index/goods_icon2.png')); ?>" alt="" /></a>
                <a href="javascript:void(0);" onclick="popView()"><i class="iconfont icon-gouwuche"></i>加入购物车</a>
                <a href="javascript:void(0);" onclick="popView()" class="last"><i class="iconfont icon-gouwu"></i>立即购买</a>
            </div>
        </div>
        -->
        <div style="height:50px;"></div>
        <div class="foot-bt clearfix">
            <div class="foot_btn clearfix">
                <a href="javascript:void(0);" onclick="weixin();">
                    <img src="<?php echo e(url('public/home/img/edit/wechat.png')); ?>" />
                    <p>Customer Service</p>
                </a>
                <a href="<?php echo e(url('/')); ?>">
                    <img src="<?php echo e(url('public/'.$_SYSTEM['sharelogo'])); ?>" />
                    <p>Main</p>
                </a>
                <a href="<?php echo e(url('cart')); ?>">
                    <img src="<?php echo e(url('public/home/img/edit/cart.png')); ?>" />
                    <p>Cart</p>
                    <em></em>
                </a>
            </div>
            <?php if($goodsData->special): ?>
            <a href="javascript:void(0);" onclick="qrView();" class="to_cart">Add To Cart</a>
            <?php else: ?>
            <a href="javascript:void(0);" onclick="popView();" class="to_cart">Add To Cart</a>
            <?php endif; ?>
        </div>

    </div>

    <a href="{{ url('nav') }}" class="to-nav">Goods<br/>Search</a>
@endsection

@section('script')
    <script defer src="<?php echo e(asset('public/home/js/jquery.flexslider.min.js')); ?>"></script>
    <script src="<?php echo e(asset('/public/home/js/layer/layer.js')); ?>"></script>
    <script type="text/javascript">
        <?php if($addCartBehavior): ?>
        ajaxAddCart();
        <?php endif; ?>
        $(window).load(function(){
            $('.flexslider').flexslider({
                animation: "slide",
                slideshow: false,
                start: function(slider){
                    $('body').removeClass('loading');
                    $('.slides li').height($('.slides li').width());
                    var imgs = $('.slides img');

                    $(imgs).each(function(k, v){
                        if($(v).width() > $(v).height()){
                            $(imgs).eq(k).css('width', '100%');
                        }else{
                            $(imgs).eq(k).css('height', '100%');
                        }
                    });
                }
            });
        });

        wx.ready(function () {
            $(function(){
                var share_link = $('input[name=share_link]').val();
                if(!share_link)share_link = '<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>';

                wx.onMenuShareAppMessage({
                    title: '<?php echo e($_SYSTEM["sitename"]); ?>',
                    desc: '<?php echo e($goodsData->name.'\n'.$goodsData->other_name); ?>',
                    link: share_link,
                    imgUrl: '<?php echo $goodsData->image ? e(url('public/'.$goodsData->image)) : 'http://'.$_SERVER['HTTP_HOST'].'/public/'.$_SYSTEM["sharelogo"]; ?>'
                });

                wx.onMenuShareTimeline({
                    title: '<?php echo e($_SYSTEM["sitename"]); ?>',
                    link: share_link,
                    imgUrl: '<?php echo $goodsData->image ? e(url('public/'.$goodsData->image)) : 'http://'.$_SERVER['HTTP_HOST'].'/public/'.$_SYSTEM["sharelogo"]; ?>'
                });
            });
        });

        //微信二维码
        function weixin() {
            $('.weixin-qr').fadeIn(300);
            $('.black-qr').fadeIn(300);
        }
        function weixinClose() {
            $('.weixin-qr').fadeOut(300);
            $('.black-qr').fadeOut(300);
        }

        //购物弹窗
        function popView() {
            $('.black').fadeIn(200);
            $('.view-pop').fadeIn(300);
        }
        function popClose() {
            $('.black').fadeOut(200);
            $('.view-pop').fadeOut(300);
        }

        function qrView(){
            var html = '<img src="<?php echo e(url('/public/'.$_SYSTEM['buy_msg'])); ?>" style="width:100%;" />';

            layer.open({
                type: 1,
                title: false,
                closeBtn: false,
                shadeClose: true,
                content: html,
                style: 'width:300px;background:rgba(0,0,0,0);'
            });
        }

        //商品数量选择
        function ajaxEnterCart(obj) {
            var limit_num = $(obj).parents('.goods-num').attr('limit_num');
            if(obj.value < limit_num)obj.value=limit_num;
        }
        function ajaxLessCart(obj) {
            var limit_num = $(obj).parents('.goods-num').attr('limit_num');
            var goods_num = $(obj).parents('.goods-num').find('.num-input');
            var value = goods_num.val();
            if(value > limit_num){
                value -= 1;
                goods_num.val(value);
            }
        }
        function ajaxPlusCart(obj) {
            var goods_num = $(obj).parents('.goods-num').find('.num-input');
            var value = goods_num.val();
            value = parseInt(value);
            value += 1;
            goods_num.val(value);
        }
        //收藏商品
        function ajaxGoodsFavor(obj) {
            var goods_id = $(obj).attr('goods_id');

            if (!goods_id || goods_id == '0') {
                dialogMsg('error: Get Id Failed');
                return false;
            }

            $.post('<?php echo e(url('ajax_goods_favor')); ?>', {'goods_id': goods_id,'_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                if (data.status == 0) {
                    dialogMsg(data.msg);
                    $('.goods-txt').find('a').addClass('v');
                } else if(data.status == 691){
                    window.location.href = '<?php echo e(url('login')); ?>';
                } else {
                    dialogMsg(data.msg);
                }
            });
        }
        //加入购物车
        function ajaxAddCart(){
            var sending = $('.add_cart_btn').attr('sending');

            if(sending === '0') {
                $('.add_cart_btn').attr('sending', '1');

                var goods_id = $('.add_cart_btn').parent().attr('goods_id');

                var goods_num = $('.num-input').val();

                var goods_stock = $('input[name=stock]').val();

                if (!goods_id || goods_id == '0') {
                    dialogMsg('error: Get Id Failed');
                    $('.add_cart_btn').attr('sending', '0');
                    return false;
                }

                if (!goods_num || goods_num <= '0') {
                    dialogMsg('error: Goods Number Error');
                    $('.add_cart_btn').attr('sending', '0');
                    return false;
                }

                if (!goods_stock || goods_stock == '0') {
                    dialogMsg('error: Stock not enough');
                    $('.add_cart_btn').attr('sending', '0');
                    return false;
                }

                $.post('<?php echo e(url('ajax_add_cart')); ?>', {'goods_id': goods_id,'goods_num': goods_num,'goods_stock': goods_stock,'from_page':'goods_view','_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                    if(data.status == 691){
                        window.location.href = '<?php echo e(url('login')); ?>';
                    } else {
                        dialogMsgOkno(data.msg, function(){
                            window.location.href = '<?php echo e(url('cart')); ?>';
                        }, false, {'confirm':'To Cart', 'cancel':'Continue Shopping'});
                    }

                    $('.add_cart_btn').attr('sending', '0');
                });
            }
        }
        //立即购买
        function ajaxAddBuy(obj) {
            var sending = $(obj).attr('sending');

            if(sending === '0') {
                $(obj).attr('sending', '1');

                var goods_id = $(obj).parent().attr('goods_id');

                var goods_num = $('.num-input').val();

                var goods_stock = $('input[name=stock]').val();

                if (!goods_id || goods_id == '0') {
                    dialogMsg('error: Get Id Failed');
                    $(obj).attr('sending', '0');
                    return false;
                }

                if (!goods_num || goods_num <= '0') {
                    dialogMsg('error: Goods Number Error');
                    $(obj).attr('sending', '0');
                    return false;
                }

                if (!goods_stock || goods_stock == '0') {
                    dialogMsg('error: Stock not enough');
                    $(obj).attr('sending', '0');
                    return false;
                }

                $.post('<?php echo e(url('ajax_add_buy')); ?>', {'goods_id': goods_id,'goods_num': goods_num,'goods_stock': goods_stock,'_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                    if (data.status == 0) {
                        window.location.href = '<?php echo e(url('buy')) ?>';
                    } else {
                        dialogMsg(data.msg);
                    }
                });
                $(obj).attr('sending', '0');
            }
        }
    </script>
@endsection