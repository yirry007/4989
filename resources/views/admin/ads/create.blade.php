@extends('admin.layout')
@section('content')
<link href="<?php echo e(asset('public/root/lib/uploadify/uploadify.css')); ?>" rel="stylesheet" type="text/css" />
<div class="pd-20">
  <form action="<?php echo e(url('/admin/ads')); ?>" method="post" class="form form-horizontal" id="form-member-add">
  	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
	  <div class="row cl">
		  <label class="form-label col-3"><span class="c-red">*</span>Image：</label>
		  <div class="formControls col-6">
			  <input type="text" class="input-text" value="<?php echo e(session('input')['image']); ?>" placeholder="" id="" name="image" readonly>
		  </div>
		  <div class="formControls col-3">
			  <input id="file_upload" type="file" multiple="true">
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
			  <img src="<?php echo e(url('public/'.session('input')['image'])); ?>" width="240" id="image_preview" />
		  </div>
          <div class="col-3">
              <?php if($errors->first('image')): ?>
              <span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('image')); ?></span>
              <?php endif; ?>
          </div>
	  </div>
      <div class="row cl">
          <label class="form-label col-3">Link：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo e(session('input')['url']); ?>" placeholder="ex：https://www.baidu.com/" name="url" >
          </div>
      </div>
      <div class="row cl">
          <label class="form-label col-3">Sort：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo e(session('input')['sort']); ?>" placeholder="default 0" onkeyup="this.value=this.value.replace(/\D/g, '');" name="sort" >
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
<script>
    $('#file_upload').uploadify({
        'buttonText' : 'BROWSE...',
        'formData'     : {
            'timestamp' : '<?php echo time(); ?>',
            '_token'     : '<?php echo e(csrf_token()); ?>'
        },
        'swf'      : "<?php echo e(asset('/public/root/lib/uploadify/uploadify.swf')) ?>",
        'uploader' : "<?php echo e(url('/admin/upload_one/ads')) ?>",
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
</script>
@parent
@endsection