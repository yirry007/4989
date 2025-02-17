<style>
    h3{font-size: 14px;}
    h4{font-weight: normal; color: #444; line-height: 20px; padding: 0; margin: 0;}
    .line {width: 400px; height: 1px; background: #ddd; margin: 7px 0;}
</style>
<h2>4989 Market Notice</h2>
<h5>User<?php echo e($emailData['name']); ?>，Has Paid<?php echo e($emailData['code']); ?>。</h5>
<h2>Order Info</h2>
<h4>Receiver：<?php echo e($emailData['orderData']->name); ?></h4>
<h4>Phone：<?php echo e($emailData['orderData']->phone); ?></h4>
<h4>Address：<?php echo e($emailData['orderData']->address); ?></h4>
<?php $num = 0; foreach($emailData['orderData']->order_goods as $k=>$v): $num++; ?>
<h3>Goods Info<?php echo $num; ?></h3>
<h4 style="font-weight: bold; color: #222;">Brand：<?php echo e($k); ?></h4>
<div class="line"></div>
<?php foreach($v as $v1): ?>
<h4>Name：<?php echo e($v1->name); ?></h4>
<h4>Price：<?php echo e($v1->price); ?></h4>
<h4>Number：<?php echo e($v1->goods_num); ?></h4>
<div class="line"></div>
<?php endforeach; ?>
<?php endforeach; ?>
<h4>Shipping Pay：<?php echo e($emailData['orderData']->express_price); ?></h4>
<br />
<h5>Total Pay ￥<?php echo e($emailData['price']); ?>元</h5>
<a href="http://4989cn.com/admin" style="display: block; margin-top: 15px; width: 200px; height: 34px; font: bold 14px/34px 'Microsoft YaHei'; color: #fff; text-align: center; background: #404040;">To Admin</a>