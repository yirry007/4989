@extends('admin.layout')
@section('content')
<link href="<?php echo e(asset('public/root/lib/uploadify/uploadify.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('public/root/lib/icheck/icheck.css')); ?>" rel="stylesheet" type="text/css" />
<div class="pd-20">
  <form action="<?php echo e(url('/admin/partners/'.$field->id)); ?>" method="post" class="form form-horizontal">
  	<input type="hidden" name="_method" value="put" />
  	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
  	<input type="hidden" name="page" value="<?php echo e(app('request')->input('page')); ?>" />
      <div class="row cl">
          <label class="form-label col-3"><span class="c-red">*</span>Name：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo e($field->name); ?>" placeholder="" name="name" >
          </div>
          <div class="col-3">
              <?php if($errors->first('name')): ?>
              <span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('name')); ?></span>
              <?php endif; ?>
          </div>
      </div>
      <div class="row cl">
          <label class="form-label col-3"><span class="c-red">*</span>Image：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo e($field->image); ?>" placeholder="" id="" name="image" readonly>
          </div>
          <div class="formControls col-3">
              <input id="file_upload" type="file" multiple="true">
          </div>
      </div>
      <div class="row cl" style="margin: 0;">
          <label class="form-label col-3"></label>
          <div class="formControls col-6" style="color: #f8658f;">
              <span style="color: #999">Best upload image size</span> W : H(1 : 1)
          </div>
      </div>
      <div class="row cl">
          <label class="form-label col-3"><span class="c-red">*</span>Preview：</label>
          <div class="formControls col-6">
              <img src="<?php echo e(url('public/'.$field->image)); ?>" width="240" id="image_preview" />
          </div>
          <div class="col-3">
              <?php if($errors->first('image')): ?>
              <span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('image')); ?></span>
              <?php endif; ?>
          </div>
      </div>
      <div class="row cl">
          <label class="form-label col-3"><span class="c-red">*</span>Partner Code：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo e($field->code); ?>" placeholder="" disabled >
          </div>
          <div class="col-3"></div>
      </div>
      <div class="row cl">
          <label class="form-label col-3"><span class="c-red">*</span>Banner：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo e($field->banner); ?>" placeholder="" id="" name="banner" readonly>
          </div>
          <div class="formControls col-3">
              <input id="file_upload_1" type="file" multiple="true">
          </div>
      </div>
      <div class="row cl" style="margin: 0;">
          <label class="form-label col-3"></label>
          <div class="formControls col-6" style="color: #f8658f;">
              <span style="color: #999">Best upload image size</span> W : H(2 : 1)
          </div>
      </div>
      <div class="row cl">
          <label class="form-label col-3"><span class="c-red">*</span>Preview：</label>
          <div class="formControls col-6">
              <img src="<?php echo e(url('public/'.$field->banner)); ?>" width="240" id="banner_preview" />
          </div>
          <div class="col-3">
              <?php if($errors->first('banner')): ?>
              <span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('banner')); ?></span>
              <?php endif; ?>
          </div>
      </div>
      <div class="row cl">
          <label class="form-label col-3"><span class="c-red">*</span>Link：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo e($field->url); ?>" placeholder="" name="url" >
          </div>
          <div class="col-3">
              <?php if($errors->first('url')): ?>
              <span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('url')); ?></span>
              <?php endif; ?>
          </div>
      </div>
      <div class="row cl">
          <label class="form-label col-3">Benefit Rate：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo e($field->rate); ?>" placeholder="" name="rate" >
          </div>
          <div class="col-3"></div>
      </div>
      <div class="row cl">
          <label class="form-label col-3">Email：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo e($field->email); ?>" placeholder="" name="email" >
          </div>
          <div class="col-3"></div>
      </div>
      <div class="row cl">
          <label class="form-label col-3">Is Enable：</label>
          <div class="formControls col-6 skin-minimal">
              <div class="radio-box">
                  <input type="radio" name="is_use" value="1" <?php if($field->is_use == 1) echo 'checked'; ?>>
                  <label>Enable</label>
              </div>
              <div class="radio-box">
                  <input type="radio" name="is_use" value="0" <?php if($field->is_use == 0) echo 'checked'; ?>>
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
<script type="text/javascript" charset="utf-8" src="<?php echo e(asset('public/root/lib/uploadify/jquery.uploadify.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/icheck/jquery.icheck.min.js')); ?>"></script>
<script type="text/javascript">
    $(function(){
        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });
    });

    $('#file_upload').uploadify({
        'buttonText' : 'BROWSE...',
        'formData'     : {
            'timestamp' : '<?php echo time(); ?>',
            '_token'     : '<?php echo e(csrf_token()); ?>'
        },
        'swf'      : "<?php echo e(asset('/public/root/lib/uploadify/uploadify.swf')) ?>",
        'uploader' : "<?php echo e(url('/admin/upload_one/bgs')) ?>",
        'onUploadSuccess' : function(file, data, response){

            var oldImg = $('input[name=image]').val();
            if(oldImg){
                $.post('<?php echo e(url('/admin/delete_one')); ?>', {'_token':'<?php echo e(csrf_token()); ?>','url':oldImg}, function(data){
                    if(data.status == '0'){
                        layer.msg(data.msg, {icon:1, time:2000});
                    }else{
                        layer.msg(data.msg, {icon:2, time:2000});
                    }
                });
            }

            $('input[name=image]').val(data);
            $('#image_preview').attr('src','/public/'+data);
        }
    });

    $('#file_upload_1').uploadify({
        'buttonText' : 'BROWSE...',
        'formData'     : {
            'timestamp' : '<?php echo time(); ?>',
            '_token'     : '<?php echo e(csrf_token()); ?>'
        },
        'swf'      : "<?php echo e(asset('/public/root/lib/uploadify/uploadify.swf')) ?>",
        'uploader' : "<?php echo e(url('/admin/upload_one/partners')) ?>",
        'onUploadSuccess' : function(file, data, response){

            var oldImg = $('input[name=banner]').val();
            if(oldImg){
                $.post('<?php echo e(url('/admin/delete_one')); ?>', {'_token':'<?php echo e(csrf_token()); ?>','url':oldImg}, function(data){
                    if(data.status == '0'){
                        layer.msg(data.msg, {icon:1, time:2000});
                    }else{
                        layer.msg(data.msg, {icon:2, time:2000});
                    }
                });
            }

            $('input[name=banner]').val(data);
            $('#banner_preview').attr('src','/public/'+data);
        }
    });
</script>
@parent
@endsection