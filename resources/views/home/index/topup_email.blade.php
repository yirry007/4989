<h2>4989 Market Top up Amount</h2>
<h5>From User <?php echo e($emailData->nickname); ?></h5>
<h2>Info</h2>
<h4>Serial No.：<?php echo e($emailData->topup_sn); ?></h4>
<h4>Amount：<?php echo e($emailData->money); ?></h4>
<h4>Time：<?php echo e(date('Y-m-d H:i:s', $emailData->addtime)); ?></h4>
<a href="http://xsc4989.hiker-shop.com/admin" style="display: block; margin-top: 15px; width: 200px; height: 34px; font: bold 14px/34px 'Microsoft YaHei'; color: #fff; text-align: center; background: #404040;">To Admin</a>