@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-cart.css')); ?>" rel="stylesheet" type="text/css" />
    <script src="<?php echo e(asset('public/home/js/checkbox.js')); ?>"></script>
@endsection

@section('content')
    <div class="contaner">

        <div class="cart-head">
            <div class="all-check">
                <div class="checkbox <?php if($cartData): ; ?>check<?php endif; ?>" onclick="checkAll(this); calcAcount();"><u></u><div>All</div></div>
            </div>
            <a href="javascript:void(0);" class="delete-btn" onclick="ajaxCheckDelete(this);" sending="0">Del</a>
        </div>

        <div class="cart-none"><?php if(!$cartData): ; ?><a href="<?php echo e(url('/')); ?>" class="cart-null">No Data</a><?php endif; ?></div>

        <ul class="cart-list">
            <?php foreach($cartData as $k=>$v): ?>
            <li class="brand-li">
                <div class="list-seller clearfix">
                    <div class="checkbox check" onclick="checkSeller(this); calcAcount();"><u></u></div>
                    <a href="brand_view/<?php echo e($v[0]->brand_id); ?>" class="seller">Brand: <?php echo e(str_replace('@', '&nbsp;', $k)); ?></a>
                </div>
                <ul class="list-goods">
                    <?php foreach($v as $v1): ?>
                    <li class="cart-li clearfix" cart_id="<?php echo e($v1->id) ?>" goods_id="<?php echo e($v1->goods_id) ?>">
                        <div class="checkbox cart-check check" onclick="checkGoods(this); calcAcount();"><u></u></div>
                        <a href="<?php echo e(url('/goods_view/'.$v1->goods_id)); ?>" class="goods clearfix">
                            <div class="img"><img src="<?php echo e(url('public/'.$v1->goods_image)); ?>" alt="" /></div>
                            <div class="txt">
                                <p><?php echo e($v1->goods_name); ?></p>
                                <u><?php echo e($v1->weight) ?>kg</u>
                                <span>￥<em class="org_price" org_price="<?php echo e($v1->org_price) ?>"><?php echo e(number_format($v1->org_price*$v1->goods_num, 2, '.', '')); ?></em></span>
                                <span class="vip">￥<em class="price" goods_price="<?php echo e($v1->goods_price) ?>"><?php echo e(number_format($v1->goods_price*$v1->goods_num, 2, '.', '')); ?></em><s>VIP Price</s></span>
                            </div>
                            <?php if($v1->express_id): ?>
                            <p class="express">Only ship to <strong><?php echo e($v1->province_name); ?></strong></p>
                            <?php endif; ?>
                        </a>
                        <div class="goods-num clearfix" limit_num="<?php echo e($v1->limit_num); ?>">
                            <div class="less" onclick="ajaxLessCart(this)"><img src="<?php echo e(url('public/home/img/index/less-icon2.jpg')); ?>" alt="" /></div>
                            <input type="text" class="num-input" name="num" value="<?php echo e($v1->goods_num); ?>" onblur="ajaxEnterCart(this)" onkeyup=";if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" />
                            <div class="plus" onclick="ajaxPlusCart(this)"><img src="<?php echo e(url('public/home/img/index/plus-icon2.jpg')); ?>" alt="" /></div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="cart-foot">
            <div class="foot-cont">
                <div class="all-check">
                    <div class="checkbox <?php if($cartData): ; ?>check<?php endif; ?>" onclick="checkAll(this); calcAcount();"><u></u><div>All</div></div>
                </div>
                <a href="javascript:void(0);" class="delete-btn" onclick="ajaxCheckDelete(this);" sending="0">Total</a>
                <div class="all_price">
                    <span>￥ <em class="org"><?php echo e(number_format($org_allPrice, 2, '.', '')); ?></em></span>
                    <span class="vip">￥ <em class="price"><?php echo e(number_format($allPrice, 2, '.', '')); ?></em><strong>VIP</strong></span>
                </div>
                <a href="javascript:void(0);" class="foot-btn" onclick="ajaxCartAddBuy(this);" sending="0">Payment</a>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        //商品数量//选择商品//删除商品
        function ajaxEnterCart(obj) {
            var limit_num = $(obj).parents('.goods-num').attr('limit_num');
            if(obj.value < limit_num)obj.value=limit_num;

            var cart_id = $(obj).parents('.list-goods li').attr('cart_id');
            var goods_id = $(obj).parents('.list-goods li').attr('goods_id');
            var price = $(obj).parents('.list-goods li').find('.price').attr('goods_price');
            var org_price = $(obj).parents('.list-goods li').find('.org_price').attr('org_price');

            $(obj).parents('.list-goods li').find('.price').text((price*$(obj).val()).toFixed(2));
            $(obj).parents('.list-goods li').find('.org_price').text((org_price*$(obj).val()).toFixed(2));

            ajaxUpdateCart(cart_id, goods_id, $(obj).val());

            calcAcount();
        }
        function ajaxLessCart(obj) {
            var limit_num = $(obj).parents('.goods-num').attr('limit_num');
            var goods_num = $(obj).next('.num-input');
            var value = goods_num.val();
            if(value > limit_num){
                value -= 1;
                goods_num.val(value);

                var cart_id = $(obj).parents('.list-goods li').attr('cart_id');
                var goods_id = $(obj).parents('.list-goods li').attr('goods_id');
                var price = $(obj).parents('.list-goods li').find('.price').attr('goods_price');
                var org_price = $(obj).parents('.list-goods li').find('.org_price').attr('org_price');

                $(obj).parents('.list-goods li').find('.price').text((price*value).toFixed(2));
                $(obj).parents('.list-goods li').find('.org_price').text((org_price*value).toFixed(2));
                ajaxUpdateCart(cart_id, goods_id, value);

                calcAcount();
            }
        }
        function ajaxPlusCart(obj) {
            var goods_num = $(obj).prev('.num-input');
            var value = goods_num.val();

            value = parseInt(value);
            value += 1;
            goods_num.val(value);

            var cart_id = $(obj).parents('.list-goods li').attr('cart_id');
            var goods_id = $(obj).parents('.list-goods li').attr('goods_id');
            var price = $(obj).parents('.list-goods li').find('.price').attr('goods_price');
            var org_price = $(obj).parents('.list-goods li').find('.org_price').attr('org_price');

            $(obj).parents('.list-goods li').find('.price').text((price*value).toFixed(2));
            $(obj).parents('.list-goods li').find('.org_price').text((org_price*value).toFixed(2));

            ajaxUpdateCart(cart_id, goods_id, value);

            calcAcount();
        }
        function ajaxUpdateCart(cart_id, goods_id, goods_num){
            $.post('<?php echo e(url('ajax_update_cart')); ?>', {'cart_id': cart_id, 'goods_id':goods_id, 'goods_num':goods_num, '_token':'<?php echo e(csrf_token()); ?>'}, function () {});
        }
        function ajaxCheckDelete(obj){
            var sending = $(obj).attr('sending');

            if(sending === '0') {
                dialogMsgOkno('Are you sure to delete it?', function () {
                    $(obj).attr('sending', '1');
                    var sel_cart_li = $('.cart-check.check').parents('li.cart-li');

                    $(sel_cart_li).each(function (k, v) {
                        var cart_id = $(v).attr('cart_id');
                        var goods_id = $(v).attr('goods_id');

                        $.post('<?php echo e(url('ajax_delete_cart')); ?>', {'cart_id': cart_id, 'goods_id': goods_id,'_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                            if (data.status == 0) {
                                var goodsData = $(v).parents('.list-goods');
                                $(v).remove();

                                if ($(goodsData).find('li.cart-li').size() === 0) {
                                    $(goodsData).parents('li.brand-li').remove();
                                }

                                var cartData = $('.cart-list').find('li');
                                var html = '<a href="<?php echo e(url('/goods')); ?>" class="cart-null">No Data</a>';
                                if (cartData.length == 0) {
                                    $('.cart-none').html(html);
                                    $('.all-check').find('.checkbox').removeClass('check');
                                }
                            } else if(data.status == 691){
                                window.location.href = '<?php echo e(url('login')); ?>';
                            } else {
                                dialogMsg(data.msg);
                            }
                            calcAcount();
                        });
                    });

                    $(obj).attr('sending', '0');
                });
            }
        }
        function calcAcount(){
            var sel_cart_li = $('.check').parent('li');
            var all_price = 0;
            var org_all_price = 0;

            $(sel_cart_li).each(function(k, v){
                all_price += parseFloat($(v).find('.price').text());
                org_all_price += parseFloat($(v).find('.org_price').text());
            });

            $('.all_price em.price').html(all_price.toFixed(2));
            $('.all_price em.org').html(org_all_price.toFixed(2));
            console.log(all_price);
        }
        //提交订单
        function ajaxCartAddBuy(obj) {
            var sending = $(obj).attr('sending');

            if(sending === '0') {
                $(obj).attr('sending', '1');
                var cart_li = $('.check').parents('.list-goods li');

                var cart_id = [];

                /*
                 * push : 从数组的末尾添加数据
                 * pop : 从数组的末尾删除数据
                 * unshift : 从数组的开头添加数据
                 * shift : 从数组的开头删除数据
                 */

                $(cart_li).each(function (k, v) {
                    cart_id.push($(v).attr('cart_id'));
                });

                if (cart_id.length == 0) {
                    dialogMsg('Please Select Goods');
                    $(obj).attr('sending', '0');
                    return false;
                }

                $.post('<?php echo e(url('ajax_cart_add_buy')); ?>', {'cart_id': cart_id,'_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                    if (data.status == 0) {
                        window.location.href = '<?php echo e(url('buy')) ?>';
                    } else {
                        dialogMsg(data.msg);
                    }
                    $(obj).attr('sending', '0');
                });
            }
        }

    </script>
@endsection