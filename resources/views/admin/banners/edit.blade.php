@extends('admin.layout')
@section('content')
<link href="<?php echo e(asset('public/root/lib/uploadify/uploadify.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('public/root/lib/icheck/icheck.css')); ?>" rel="stylesheet" type="text/css" />
<div class="pd-20">
  <form action="<?php echo e(url('/admin/banners/'.$field->id)); ?>" method="post" class="form form-horizontal">
  	<input type="hidden" name="_method" value="put" />
  	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
  	<input type="hidden" name="page" value="<?php echo e(app('request')->input('page')); ?>" />
      <div class="row cl">
          <label class="form-label col-3">Position：</label>
          <div class="formControls col-6 skin-minimal">
              <div class="radio-box">
                  <input type="radio" name="position" value="main" <?php if($field->position == 'main') echo 'checked'; ?>>
                  <label>Main</label>
              </div>
              <div class="radio-box">
                  <input type="radio" name="position" value="list" <?php if($field->position == 'list') echo 'checked'; ?>>
                  <label>List</label>
              </div>
              <div class="radio-box">
                  <input type="radio" name="position" value="ads" <?php if($field->position == 'ads') echo 'checked'; ?>>
                  <label>Ads</label>
              </div>
              <div class="radio-box">
                  <input type="radio" name="position" value="main_mid" <?php if($field->position == 'main_mid') echo 'checked'; ?>>
                  <label>Main Mid</label>
              </div>
              <div class="radio-box">
                  <input type="radio" name="position" value="main_popup" <?php if($field->position == 'main_popup') echo 'checked'; ?>>
                  <label>Main Popup</label>
              </div>
              <div class="radio-box">
                  <input type="radio" name="position" value="sign" <?php if($field->position == 'sign') echo 'checked'; ?>>
                  <label>Sign Page</label>
              </div>
              <div class="radio-box">
                  <input type="radio" name="position" value="mypage_ad" <?php if($field->position == 'mypage_ad') echo 'checked'; ?>>
                  <label>My page</label>
              </div>
              <div class="radio-box">
                  <input type="radio" name="position" value="fiveday_off" <?php if($field->position == 'fiveday_off') echo 'checked'; ?>>
                  <label>Five Day</label>
              </div>
          </div>
      </div>
      <div class="row cl">
          <label class="form-label col-3">Title：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo e($field->title); ?>" placeholder="" name="title" >
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
              <span style="color: #999">Best upload image size</span> W : H(2 : 1)
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
          <label class="form-label col-3">Link：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo e($field->link); ?>" placeholder="例：https://www.baidu.com/" name="link" >
          </div>
      </div>
      <div class="row cl">
          <label class="form-label col-3">End Time：</label>
          <div class="formControls col-2">
              <input type="hidden" id="datemin" value="" />
              <input type="text" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:'#F{$dp.$D(\'datemin\')}'})" id="datemax" class="input-text Wdate" style="width:180px;" name="end_time" value="<?php echo e(date('Y-m-d H:i:s', $field->end_time)); ?>">
          </div>
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
          <label class="form-label col-3">Sort：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo e($field->sort); ?>" placeholder="default 0" onkeyup="this.value=this.value.replace(/\D/g, '');" name="sort" >
          </div>
      </div>
    <div class="row cl">
      <div class="col-8 col-offset-4">
        <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;Submit&nbsp;&nbsp;">
      </div>
    </div>
  </form>
</div>
<!-- 选择日期js -->
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/My97DatePicker/WdatePicker.js')) ?>"></script>
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
        'uploader' : "<?php echo e(url('/admin/upload_one/banners')) ?>",
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