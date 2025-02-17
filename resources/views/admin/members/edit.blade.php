@extends('admin.layout')
@section('content')
    <link href="<?php echo e(asset('public/root/lib/icheck/icheck.css')); ?>" rel="stylesheet" type="text/css" />
    <div class="pd-20">
        <form action="<?php echo e(url('/admin/members/'.$field->id)); ?>" method="post" class="form form-horizontal">
            <input type="hidden" name="_method" value="put" />
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <input type="hidden" name="page" value="<?php echo e(app('request')->input('page')); ?>" />
            <div class="row cl">
                <label class="form-label col-3">Wechat Openid：</label>
                <div class="formControls col-6">
                    <?php echo e($field->openid); ?>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3">Portrait：</label>
                <div class="formControls col-6">
                    <img src="<?php echo e($field->portrait); ?>" width="160" />
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3">Nickname：</label>
                <div class="formControls col-6">
                    <?php echo e($field->nickname); ?>
                </div>
            </div>
			<div class="row cl">
                <label class="form-label col-3">Withdrawable：</label>
                <div class="formControls col-6">
                    <?php echo e($field->coin); ?>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3">Is Enable：</label>
                <div class="formControls col-6 skin-minimal">
                    <div class="radio-box">
                        <input type="radio" name="is_use" value="1" <?php echo $field->is_use == 1 ? 'checked' : ''; ?>>
                        <label>Enable</label>
                    </div>
                    <div class="radio-box">
                        <input type="radio" name="is_use" value="0" <?php echo $field->is_use == 0 ? 'checked' : ''; ?>>
                        <label>Disable</label>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-3">Create Time：</label>
                <div class="formControls col-6">
                    <?php echo e(date('Y-m-d H:i', $field->addtime)) ?>
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