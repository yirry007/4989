@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-mypage.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="contaner">

        <ul class="order-select clearfix">
            <li><a href="<?php echo e(url('/order')); ?>" <?php if(app('request')->input('select') == '') echo 'class="v"'; ?>>All</a></li>
            <li><a href="<?php echo e(url('/order?select=1')); ?>" <?php if(app('request')->input('select') == '1') echo 'class="v"'; ?>>Unpaid</a></li>
            <li><a href="<?php echo e(url('/order?select=2')); ?>" <?php if(app('request')->input('select') == '2') echo 'class="v"'; ?>>Paid</a></li>
            <li><a href="<?php echo e(url('/order?select=3')); ?>" <?php if(app('request')->input('select') == '3') echo 'class="v"'; ?>>Shipped</a></li>
            <li><a href="<?php echo e(url('/order?select=4')); ?>" <?php if(app('request')->input('select') == '4') echo 'class="v"'; ?>>Finish</a></li>
        </ul>

        <ul class="order-list">
            <?php foreach($orderData as $v): ?>
            <li>
                <div class="order-num clearfix">
                    <div class="lef">
                        Order No. <em id="order_num"><?php echo e($v->order_num); ?></em>
                    </div>
                    <div class="righ"><?php echo e(date('Y-m-d', $v->addtime)) ?></div>
                </div>
                <div class="order-status clearfix">
                    <div class="left">
                        <p>Status : <span><?php echo e($v->status); ?></span></p>
                        <p>Price : <span>￥<em><?php echo e(number_format($v->all_price + $v->express_price, 2, '.', '')); ?></em></span><b class="<?php echo $v->pay_method == '1' ? 'g' : 'r'; ?>">（<?php echo $v->pay_method == '1' ? 'Wechat' : 'Balance'; ?>）</b></p>
                        <strong>( With shipping fee <?php echo e($v->express_price); ?>元 )</strong>
                    </div>
                    <div class="right clearfix <?php echo $v->button; ?>" order_id="<?php echo e($v->id); ?>">
                        <span class="btn1" onclick="ajaxOrderDelete(this);" sending="0">Cancel</span> <a href="javascript:void(0);" class="order-btn0 btn1" onclick="ajaxOrderPay(this);" sending="0">Payment</a>
                        <span class="btn2">Wait to ship</span>
                        <a href="javascript:void(0);" class="order-btn1 btn3" onclick="ajaxOrderCheck(this);" sending="0">Received</a><!--<a href="<?php echo e(url('/order_express/'.$v->express_num.'/'.$v->express)); ?>" class="order-btn2 btn3">物流信息</a>-->
                        <span class="btn4">Finish</span>
                    </div>
                </div>
                <div class="sub_info">
                    <p>Receiver : <?php echo $v->is_charge ? e($v->nickname) : e($v->name); ?></p>
                    <p>Phone : <?php echo e($v->phone); ?></p>
                    <p>Address : <?php echo $v->is_charge ? e($v->name) : e($v->address); ?></p>
                </div>
                <?php if($v->is_pay == '1' && ($v->is_send == '1' || $v->is_send == '2')): ?>
                <?php $express = explode(';', $v->express_num); ?>
                <div class="sub_info">
                    <h4>Waybill</h4>
                    <?php
                        foreach($express as $v1):
                        $exp = explode(':', $v1);
                    ?>
                    <p class="order_exp">
                        <?php echo array_key_exists(1, $exp) ? e($exp[1]) : 'No Waybill'; ?>
                        <a href="<?php echo array_key_exists(0, $exp) && $exp[0] && array_key_exists(1, $exp) && $exp[1] ? e(url('order_express/'.trim($exp[1]).'/'.trim($exp[0]))) : 'javascript:void(0);'; ?>">Detail</a>
                    </p>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <!--<div style="font: .8rem/1.6rem ''; color: #aaa; text-align: center;">상품상세</div>-->
                <ul class="buy-list">
                    <?php foreach($v->order_goods as $k1=>$v1): ?>
                    <li>
                        <?php if($v1[0]->brand_id): ?>
                        <div class="list-seller clearfix"><a href="<?php echo e(url('/brand_view/'.$v1[0]->brand_id)); ?>" class="seller"><?php echo e(str_replace('@', '&nbsp;', $k1)); ?></a></div>
                        <?php endif; ?>
                        <ul class="list-goods">
                            <?php foreach($v1 as $v2): ?>
                            <li class="clearfix">
                                <a href="<?php if($v->is_charge){echo 'javascript:void(0);';}else{echo e(url('/goods_view/'.$v2->goods_id));} ?>" class="goods clearfix">
                                    <div class="img"><img src="<?php echo e(url('public/'.$v2->image)); ?>" alt="image not found" /></div>
                                    <div class="txt">
                                        <p><?php echo e($v2->name); ?><u><?php echo e($v2->weight) ?>kg</u></p>
                                        <span>￥<em><?php echo e($v2->price); ?></em></span>
                                    </div>
                                </a>
                                <div class="goods-num clearfix">x <?php echo e($v2->goods_num); ?></div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="page_list"><?php echo $pageShow; ?></div>

    </div>
@endsection

@section('script')
    <script>
        //立即支付
        function ajaxOrderPay(obj) {
            var sending = $(obj).attr('sending');

            if(sending === '0') {
                dialogMsgOkno('Confirm to buy?', function () {
                    $(obj).attr('sending', '1');

                    var order_num = $('#order_num').text();

                    $.post('<?php echo e(url('ajax_order_pay')); ?>', {'order_num': order_num, '_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                        if (data.status == 0) {
                            window.location.href = '<?php echo e(url('wxpay')) ?>?order_num='+data.order_num;
                        } else {
                            dialogMsg(data.msg);
                        }
                        $(obj).attr('sending', '0');
                    });
                });
            }
        }
        //确认订单
        function ajaxOrderCheck(obj) {
            var sending = $(obj).attr('sending');

            if(sending === '0') {
                dialogMsgOkno('Please confirm receipt after receiving the goods!', function () {
                    $(obj).attr('sending', '1');

                    var order_id = $(obj).parent().attr('order_id');

                    $.post('<?php echo e(url('ajax_order_check')); ?>', {'order_id': order_id, '_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                        if (data.status == 0) {
                            window.location.reload();
                        } else if(data.status == 691){
                            window.location.href = '<?php echo e(url('login')); ?>';
                        } else {
                            dialogMsg(data.msg);
                        }
                        $(obj).attr('sending', '0');
                    });
                });
            }
        }
        //取消订单
        function ajaxOrderDelete(obj) {
            var sending = $(obj).attr('sending');

            if(sending === '0') {
                dialogMsgOkno('Are you sure to cancel the order?', function () {
                    $(obj).attr('sending', '1');

                    var order_id = $(obj).parent().attr('order_id');

                    $.post('<?php echo e(url('ajax_order_delete')); ?>', {'order_id': order_id, '_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                        if (data.status == 0) {
                            window.location.reload();
                        } else if(data.status == 691){
                            window.location.href = '<?php echo e(url('login')); ?>';
                        } else {
                            dialogMsg(data.msg);
                        }
                        $(obj).attr('sending', '0');
                    });
                });
            }
        }
    </script>
@endsection

