@extends('admin.layout')
@section('content')
<link href="<?php echo e(asset('public/root/lib/icheck/icheck.css')); ?>" rel="stylesheet" type="text/css" />
<div class="pd-20">
  <form action="<?php echo e(url('/admin/articles')); ?>" method="post" class="form form-horizontal" id="form-member-add">
  	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
    <div class="row cl">
		<label class="form-label col-3">Title：</label>
		<div class="formControls col-6">
			<input type="text" class="input-text" value="<?php echo e(session('input')['title']); ?>" placeholder="" name="title" >
		</div>
        <div class="col-3">
            <?php if($errors->first('title')): ?>
            <span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('title')); ?></span>
            <?php endif; ?>
        </div>
    </div>
      <div class="row cl">
          <label class="form-label col-3">Author：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo session('input')['author']; ?>" placeholder="" name="author" >
          </div>
          <div class="col-3"></div>
      </div>
      <div class="row cl">
          <label class="form-label col-3">Is Share：</label>
          <div class="formControls col-6 skin-minimal">
              <div class="radio-box">
                  <input type="radio" name="sharing" value="1" <?php if(session('input')['sharing'] === '1' || session('input')['sharing'] === NULL) echo 'checked'; ?>>
                  <label>Enable</label>
              </div>
              <div class="radio-box">
                  <input type="radio" name="sharing" value="0" <?php if(session('input')['sharing'] === '0') echo 'checked'; ?>>
                  <label>Disable</label>
              </div>
          </div>
      </div>
      <div class="row cl">
          <label class="form-label col-3">Is Follow：</label>
          <div class="formControls col-6 skin-minimal">
              <div class="radio-box">
                  <input type="radio" name="following" value="1" <?php if(session('input')['following'] === '1' || session('input')['following'] === NULL) echo 'checked'; ?>>
                  <label>Enable</label>
              </div>
              <div class="radio-box">
                  <input type="radio" name="following" value="0" <?php if(session('input')['following'] === '0') echo 'checked'; ?>>
                  <label>Disable</label>
              </div>
          </div>
      </div>
      <div class="row cl">
          <label class="form-label col-3">Subscribe Text：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo session('input')['msg']; ?>" placeholder="" name="msg" >
          </div>
          <div class="col-3"></div>
      </div>
      <div class="row cl">
          <label class="form-label col-3">Content：</label>
          <div class="formControls col-9">
              <textarea name="content" id="content"><?php echo e(session('input')['content']); ?></textarea>
          </div>
      </div>
      <div class="row cl">
          <label class="form-label col-3">Hit：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo session('input')['hit'] ?: '0'; ?>" placeholder="" name="hit" >
          </div>
          <div class="col-3"></div>
      </div>
    <div class="row cl">
      <div class="col-8 col-offset-4">
        <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;Submit&nbsp;&nbsp;">
      </div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/icheck/jquery.icheck.min.js')); ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo e(asset('public/root/lib/ueditor/ueditor.config.js')); ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo e(asset('public/root/lib/ueditor/ueditor.all.min.js')); ?>"> </script>
<script type="text/javascript" charset="utf-8" src="<?php echo e(asset('public/root/lib/ueditor/lang/zh-cn/zh-cn.js')); ?>"></script>
<script>
    $(function() {
        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });

        UE.getEditor('content', {
            "initialFrameWidth" : "100%",
            "initialFrameHeight" : 300,
            "maximumWords" : 10000
        });
    });
</script>
@parent
@endsection