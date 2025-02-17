@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-buy.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="contaner">
        <a href="<?php echo e(url('/address?from=buy')); ?>" class="address-check clearfix" province="<?php echo $addressData ? e($addressData->province) : ''; ?>" address_id="<?php echo $addressData ? e($addressData->id) : ''; ?>">
            <?php if($addressData): ?>
            <div class="cont">
                <div class="middle">
                <p><em class="name"><?php echo e($addressData->name); ?></em> <em class="phone"><?php echo e($addressData->phone); ?></em></p>
                <p><em class="address"><?php echo e($addressData->province_name); ?><?php echo e($addressData->province_name == '延吉' ? '' : '省'); ?> <?php echo e($addressData->address); ?></em></p>
                </div>
            </div>
            <?php else: ?>
            <div class="area-null">Please Select Address</div>
            <?php endif; ?>
            <div class="right"><i class="iconfont icon-down"></i></div>
        </a>

        <ul class="buy-list">
            <?php foreach($buyData as $k=>$v): ?>
            <li>
                <div class="list-seller clearfix">
                    <a href="brand_view/<?php echo e($v[0]->brand_id); ?>" class="seller">Brand: <?php echo e(str_replace('@', '&nbsp;', $k)); ?></a>
                </div>
                <ul class="list-goods">
                    <?php foreach($v as $v1): ?>
                    <li class="clearfix">
                        <input type="hidden" name="express" goods_name="<?php echo e($v1->goods_name); ?>" province_name="<?php echo e($v1->province_name); ?>" value="<?php echo e($v1->province); ?>" />
                        <a href="<?php echo e(url('/goods_view/'.$v1->goods_id)); ?>" class="goods clearfix">
                            <div class="img"><img src="<?php echo e(url('public/'.$v1->goods_image)); ?>" alt="" /></div>
                            <div class="txt">
                                <p><?php echo e($v1->goods_name); ?></p>
                                <u><?php echo e($v1->goods_weight) ?>kg</u>
                                <span>￥<em class="org_price"><?php echo e(number_format($v1->org_price*$v1->goods_num, 2, '.', '')); ?></em></span>
                                <span class="vip">￥<em class="price"><?php echo e(number_format($v1->goods_price*$v1->goods_num, 2, '.', '')); ?></em><s>VIP Price</s></span>
                            </div>
                            <?php if($v1->express_id): ?>
                            <p class="express">Shipping Only<strong><?php echo e($v1->province_name); ?></strong></p>
                            <?php endif; ?>
                        </a>
                        <div class="goods-num clearfix">Count <?php echo e($v1->goods_num); ?></div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>
		
		<?php if($express && $express->days): ?>
		<div class="buy-receive">
			<?php if($express->is_local): ?>
                Order before <?php echo e($_SYSTEM['ordertime']); ?>，Same day ship / Order after <?php echo e($_SYSTEM['ordertime']); ?>，Next day ship<br/>【only 延吉市】
			<?php else: ?>
                Expected to arrive in <?php echo e(receive_time($express->days)); ?>
			<?php endif; ?>
		</div>
		<?php endif; ?>

        <div class="buy_pay">
            <p class="express-price">
                <span>Shipping fee</span>
                <strong>￥ <?php echo e(number_format($express_price, 2, '.', '')); ?></strong>
            </p>
            <p class="sel_method">Please Select Payment Method</p>
            <a href="javascript:void(0);" class="we_pay" onclick="ajaxBuyPay(this);" price="<?php echo e(number_format($org_allPrice+$express_price, 2, '.', '')); ?>" sending="0">Wechat Pay ￥ <?php echo e(number_format($org_allPrice+$express_price, 2, '.', '')); ?></a>
            <a href="javascript:void(0);" class="vip_pay" onclick="ajaxBuyVipPay(this);" price="<?php echo e(number_format($allPrice+$express_price, 2, '.', '')); ?>" sending="0">Balance Pay ￥ <?php echo e(number_format($allPrice+$express_price, 2, '.', '')); ?></a>
            <p class="pay-bottom">
                <span>Balance: ￥<?php echo e($memberData->money); ?></span>
                <a href="<?php echo e(url('topup')); ?>">Top up &gt;&gt;</a>
            </p>
        </div>

    </div>
@endsection

@section('script')
    <script>
        function ajaxBuyPay(obj){
            var sending = $(obj).attr('sending');

            if(sending === '0'){
                var price = $(obj).attr('price');
                dialogMsgOkno('Total pay ￥'+price, function(){
                    $(obj).attr('sending', '1');

                    //地址id获取
                    var address_id = $('.address-check').attr('address_id');

                    //验证（地址）
                    if(address_id == ''){
                        dialogMsg('Please Select Address');
                        $(obj).attr('sending', '0');
                        return false;
                    }

                    var province = $('.address-check').attr('province');
                    var express_ok = true;
                    var express_msg = '';
                    var express_title = 'The shipping area for the following products is limited';
                    $('input[name=express]').each(function(k, v){
                        if($(v).val() != '' && $(v).val() != province){
                            express_msg += '产品：<b style="color:#ff0000;">'+$(v).attr('goods_name')+'</b>('+$(v).attr('province_name')+')<br/>';
                            express_ok = false;
                        }
                    });

                    if(!express_ok){
                        dialogMsg(express_msg, function(){window.history.go(-1);}, express_title, {'confirm':'Re-Order'});
                        return false;
                    }

                    //ajax提交数据（携带地址id）
                    $.post('<?php echo e(url('ajax_buy_pay')); ?>', {'address_id':address_id, '_token':'<?php echo e(csrf_token()); ?>'}, function (data) {
                        if(data.status == 0){
                            window.location.href = '<?php echo e(url('wxpay')) ?>?order_num='+data.order_num;
                        }else if(data.status == 691){
                            window.location.href = '<?php echo e(url('login')); ?>';
                        }else{
                            dialogMsg(data.msg);
                        }
                         $(obj).attr('sending', '0');
                    });
                }, 'Paying by Wechat Pay', {'confirm':'Confirm to pay', 'cancel':'cancel'});
            }
        }

        function ajaxBuyVipPay(obj){
            var sending = $(obj).attr('sending');

            if(sending === '0'){
                var price = $(obj).attr('price');
                dialogMsgOkno('Total pay ￥'+price, function(){
                    $(obj).attr('sending', '1');

                    //地址id获取
                    var address_id = $('.address-check').attr('address_id');

                    //验证（地址）
                    if(address_id == ''){
                        dialogMsg('Please Select Address');
                        $(obj).attr('sending', '0');
                        return false;
                    }

                    var province = $('.address-check').attr('province');
                    var express_ok = true;
                    var express_msg = '';
                    var express_title = 'The shipping area for the following products is limited';
                    $('input[name=express]').each(function(k, v){
                        if($(v).val() != '' && $(v).val() != province){
                            express_msg += 'Goods：<b style="color:#ff0000;">'+$(v).attr('goods_name')+'</b>('+$(v).attr('province_name')+')<br/>';
                            express_ok = false;
                        }
                    });

                    if(!express_ok){
                        dialogMsg(express_msg, function(){window.history.go(-1);}, express_title, {'confirm':'Re-Order'});
                        return false;
                    }

                    //验证是否有足够的余额
                    var money = parseFloat('<?php echo $memberData->money ?>');
                    var price = parseFloat($(obj).find('em').html());
                    
                    if(money < price){
                        if(confirm('Balance is not enough, would you like to top up?')){
                            window.location.href = '<?php echo e(url('/my')); ?>';
                        }
                        $(obj).attr('sending', '0');
                        return false;
                    }

                    //ajax提交数据（携带地址id）
                    $.post('<?php echo e(url('ajax_buy_vip_pay')); ?>', {'address_id':address_id, '_token':'<?php echo e(csrf_token()); ?>'}, function (data) {
                        if(data.status == 0){
                            window.location.href = '<?php echo e(url('payok')) ?>';
                        }else if(data.status == 691){
                            window.location.href = '<?php echo e(url('login')); ?>';
                        }else if(data.status == 7){
                            if(confirm(data.msg)){
                                window.location.href = '<?php echo e(url('topup')); ?>';
                            }
                        }else{
                            dialogMsg(data.msg);
                        }
                        $(obj).attr('sending', '0');
                    });
                }, 'Paying by Wechat Pay', {'confirm':'Confirm to pay', 'cancel':'cancel'});
            }
        }
    </script>
@endsection