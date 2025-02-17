@extends('admin.layout')
@section('content')
    <link href="<?php echo e(asset('public/root/lib/icheck/icheck.css')); ?>" rel="stylesheet" type="text/css" />
    <div class="pd-20">
        <form action="<?php echo e(url('/admin/orders/'.$field->id)); ?>" method="post" class="form form-horizontal">
            <input type="hidden" name="_method" value="put" />
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <input type="hidden" name="page" value="<?php echo e(app('request')->input('page')); ?>" />
            <div class="row cl">
                <label class="form-label col-3">Order Number：</label>
                <div class="formControls col-6">
                    <?php echo e($field->order_num); ?>
                    <input type="hidden" value="<?php echo e($field->order_num); ?>" name="order_num" >
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3">Receiver：</label>
                <div class="formControls col-6">
                    <?php echo e($field->name); ?>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3">Phone：</label>
                <div class="formControls col-6">
                    <?php echo e($field->phone); ?>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3">Address：</label>
                <div class="formControls col-6">
                    <?php echo e($field->address); ?>
                    <input type="hidden" value="<?php echo e($field->address); ?>" name="address" >
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3">Payment Price：</label>
                <div class="formControls col-6">
                    <?php echo e($field->all_price + $field->express_price); ?>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3">Payment Status：</label>
                <div class="formControls col-6">
                    <?php echo e($field->is_pay == 1 ? 'Paid' : 'Not Paid'); ?>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3">Order Time：</label>
                <div class="formControls col-6">
                    <?php echo e(date('Y-m-d H:i', $field->addtime)) ?>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3">Shipment：</label>
                <div class="formControls col-6 skin-minimal">
                    <div class="radio-box">
                        <input type="radio" id="view-1" name="is_send" value="0" <?php echo $field->is_send == 0 ? 'checked' : ''; ?>>
                        <label for="view-1">Unshipped</label>
                    </div>
                    <div class="radio-box">
                        <input type="radio" id="view-2" name="is_send" value="1" <?php echo $field->is_send == 1 ? 'checked' : ''; ?>>
                        <label for="view-2">Shipped</label>
                    </div>
                    <div class="radio-box">
                        <input type="radio" id="view-3" name="is_send" value="2" <?php echo $field->is_send == 2 ? 'checked' : ''; ?>>
                        <label for="view-3">Received</label>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3">Select Express：</label>
                <div class="formControls col-6">
                    <span class="select-box">
                    <select class="select" size="1" name="express">
                        <option <?php if($field->express == '') echo 'selected'; ?>>Please Select Express</option>
                        <option value="优速" <?php if($field->express == '优速') echo 'selected'; ?>>you su</option>
                        <option value="德邦" <?php if($field->express == '德邦') echo 'selected'; ?>>de bang</option>
                        <option value="同城跑腿" <?php if($field->express == '同城跑腿') echo 'selected'; ?>>tong cheng</option>
                        <option value="顺丰" <?php if($field->express == '顺丰') echo 'selected'; ?>>shun feng</option>
                        <option value="申通" <?php if($field->express == '申通') echo 'selected'; ?>>shen tong</option>
                        <option value="百世" <?php if($field->express == '百世') echo 'selected'; ?>>bai shi</option>
                        <option value="邮政" <?php if($field->express == '邮政') echo 'selected'; ?>>you zheng</option>
                    </select>
                    </span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3">Waybill：</label>
                <div class="formControls col-6">
                    <textarea name="express_num" cols="" rows="" class="textarea"  placeholder="At least 10 characters" datatype="*10-1000" dragonfly="true" nullmsg="required" onKeyUp="textarealength(this,1000)"><?php echo e($field->express_num); ?></textarea>
                    <p class="textarea-numberbar"><em class="textarea-length">0</em>/1000</p>
                </div>
            </div>
            <div class="row cl">
                <div class="col-8 col-offset-4">
                    <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;Submit&nbsp;&nbsp;">
                </div>
            </div>
        </form>
</div>
    <script type="text/javascript" src="<?php echo e(asset('public/root/lib/icheck/jquery.icheck.min.js')); ?>"></script>
    <script type="text/javascript">
        $(function(){
            $('.skin-minimal input').iCheck({
                checkboxClass: 'icheckbox-blue',
                radioClass: 'iradio-blue',
                increaseArea: '20%'
            });
        });
    </script>
    @parent
@endsection