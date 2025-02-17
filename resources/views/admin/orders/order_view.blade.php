<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"><!--无视ie8-->
    <meta name="format-detection" content="telephone=no"><!--iPhone号码超链接-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"><!--禁止放大-->
    <title>4989 Order Info</title>
    <style>
        h3{font-size: 14px;}
        h4{font-weight: normal; color: #444; line-height: 20px; padding: 0; margin: 0;}
        .line {width: 400px; height: 1px; background: #ddd; margin: 7px 0;}
    </style>
</head>
<body>
    <h2>West Market 4989 Payment Notice</h2>
    <h5>User <?php echo e($member->nickname); ?>，Has Paid<?php echo e($order->order_num); ?>。</h5>
    <h2>Oder Info</h2>
    <h4>Receiver：<?php echo e($order->name); ?></h4>
    <h4>Phone：<?php echo e($order->phone); ?></h4>
    <h4>Address：<?php echo e($order->address); ?></h4>
    <?php foreach($orderGoods as $k=>$v): ?>
    <h3>Order Goods<?php echo count($orderGoods); ?></h3>
    <h4 style="font-weight: bold; color: #222;">Brand：<?php echo e($k); ?></h4>
    <div class="line"></div>
    <?php foreach($v as $v1): ?>
    <h4>Name：<?php echo e($v1->name); ?></h4>
    <h4>Price：<?php echo e($v1->price); ?></h4>
    <h4>Number：<?php echo e($v1->goods_num); ?></h4>
    <div class="line"></div>
    <?php endforeach; ?>
    <?php endforeach; ?>
    <h4>Goods Price：<?php echo e($order->all_price); ?>元</h4>
    <h4>Shipping Price：<?php echo e($order->express_price); ?>元</h4>
    <br />
    <h5>Total Price ￥<?php echo e($order->all_price+$order->express_price); ?>元</h5>
</body>
</html>
