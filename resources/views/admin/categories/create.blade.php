@extends('admin.layout')
@section('content')
<div class="pd-20">
  <form action="<?php echo e(url('/admin/categories')); ?>" method="post" class="form form-horizontal" id="form-member-add">
  	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
    <div class="row cl">
		<label class="form-label col-3"><span class="c-red">*</span>Name：</label>
		<div class="formControls col-6">
			<input type="text" class="input-text" value="<?php echo e(session('input')['name']); ?>" placeholder="" name="name" >
		</div>
		<div class="col-3">
            <?php if($errors->first('name')): ?>
			<span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('name')); ?></span>
            <?php endif; ?>
		</div>
    </div>
      <div class="row cl">
          <label class="form-label col-3">Parent Cate：</label>
          <div class="formControls col-6"> <span class="select-box">
			<select class="select" size="1" name="parent_id">
				<option value="0" <?php if(!session('input')['parent_id']) echo 'selected'; ?> >Top Cate</option>
                <?php foreach($categoryData as $v): ?>
                <option value="<?php echo e($v->id); ?>" <?php if(session('input')['parent_id'] == $v->id) echo 'selected'; ?> ><?php echo e(str_repeat('▶', 4*$v->level).$v->name); ?></option>
                <?php endforeach; ?>
			</select>
        </span> </div>
          <div class="col-3"> </div>
      </div>
	  <div class="row cl">
		  <label class="form-label col-3">Image：</label>
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
              <span style="color: #999">Best upload image size</span> W : H(1 : 1)
          </div>
      </div>
	  <div class="row cl">
		  <label class="form-label col-3">Preview：</label>
		  <div class="formControls col-6">
			  <img src="<?php echo e(url('public/'.session('input')['image'])); ?>" width="240" id="image_preview" />
		  </div>
	  </div>
      <div class="row cl">
          <label class="form-label col-3">Is Enable：</label>
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
          <label class="form-label col-3">Is Rec.：</label>
          <div class="formControls col-6 skin-minimal">
              <div class="radio-box">
                  <input type="radio" name="is_rec" value="1" <?php if(session('input')['is_rec'] === '1') echo 'checked'; ?>>
                  <label>Enable</label>
              </div>
              <div class="radio-box">
                  <input type="radio" name="is_rec" value="0" <?php if(session('input')['is_rec'] === '0' || session('input')['is_rec'] === NULL) echo 'checked'; ?>>
                  <label>Disable</label>
              </div>
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
<!-- 图片上传css+js -->
<link href="<?php echo e(asset('public/root/lib/uploadify/uploadify.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('public/root/lib/icheck/icheck.css')); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="<?php echo e(asset('public/root/lib/uploadify/jquery.uploadify.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/icheck/jquery.icheck.min.js')); ?>"></script>
<script>
    $(function() {
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
        'uploader' : "<?php echo e(url('/admin/upload_one/categories')) ?>",
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