@extends('admin.layout')
@section('content')
<link href="<?php echo e(asset('public/root/lib/icheck/icheck.css')); ?>" rel="stylesheet" type="text/css" />
<div class="pd-20">
  <form action="<?php echo e(url('/admin/admin')); ?>" method="post" class="form form-horizontal" id="form-member-add">
  	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
    <div class="row cl">
		<label class="form-label col-3"><span class="c-red">*</span>Username：</label>
		<div class="formControls col-6">
			<input type="text" class="input-text" value="<?php echo e(session('input')['username']); ?>" placeholder="" name="username" >
		</div>
		<div class="col-3">
            <?php if($errors->first('username')): ?>
			<span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('username')); ?></span>
            <?php endif; ?>
		</div>
    </div>
	<div class="row cl">
		<label class="form-label col-3"><span class="c-red">*</span>Password：</label>
		<div class="formControls col-6">
			<input type="password" class="input-text" value="" placeholder="" name="password" >
		</div>
		<div class="col-3">
            <?php if($errors->first('password')): ?>
			<span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('password')); ?></span>
            <?php endif; ?>
		</div>
    </div>
    <div class="row cl">
		<label class="form-label col-3"><span class="c-red">*</span>Is Enable：</label>
		<div class="formControls col-6 skin-minimal">
			<div class="radio-box">
				<input type="radio" name="is_use" value="1" <?php if(session('input')['is_use'] === '1' || session('input')['is_use'] === NULL) echo 'checked'; ?>>
				<label>Enable</label>
			</div>
			<div class="radio-box">
				<input type="radio" name="is_use" value="0" <?php if(session('input')['is_use'] === '0') echo 'checked'; ?>>
				<label>Disable</label>
			</div>
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
<script>
    $(function() {
        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });
    });
</script>
@parent
@endsection