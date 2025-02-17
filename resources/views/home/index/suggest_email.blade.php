<style>
    h3{font-size: 14px;}
    h4{font-weight: normal; color: #444; line-height: 20px; padding: 0; margin: 0;}
    .line {width: 400px; height: 1px; background: #ddd; margin: 7px 0;}
</style>
<h2>4989 Market. Suggestion</h2>
<h5>From User<?php echo e($emailData->nickname); ?></h5>
<h2>Info</h2>
<h4>Type：<?php echo $emailData->types == '1' ? 'Complaint' : 'Suggestion'; ?></h4>
<h4>Content：<?php echo e($emailData->content); ?></h4>
<h4>Time：<?php echo e(date('Y-m-d H:i:s', $emailData->addtime)); ?></h4>
<a href="http://4989cn.com/admin" style="display: block; margin-top: 15px; width: 200px; height: 34px; font: bold 14px/34px 'Microsoft YaHei'; color: #fff; text-align: center; background: #404040;">To Admin</a>