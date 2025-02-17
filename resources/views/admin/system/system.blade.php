@extends('admin.layout')
@section('content')
<link href="<?php echo e(asset('public/root/lib/uploadify/uploadify.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('public/root/lib/icheck/icheck.css')); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="<?php echo e(asset('public/root/lib/uploadify/jquery.uploadify.min.js')); ?>"></script>
<div class="pd-20">
	<form action="<?php echo e(url('/admin/system')); ?>" method="post" class="form form-horizontal" id="form-category-add">
		<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
		<div id="tab-category" class="HuiTab">
			<div class="tabBar cl"><span>Basic Setting</span><span>Function Setting</span></div>
			<div class="tabCon">
				<?php foreach($systemData1 as $v): ?>
				<?php if($v->input_type == 0): ?>
				<div class="row cl">
					<label class="form-label col-3"><?php echo e($v->description); ?>：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e($v->sys_value); ?>" placeholder="" id="" name="<?php echo e($v->sys_key); ?>">
					</div>
					<div class="col-3"> </div>
				</div>
				<?php elseif($v->input_type == 1): ?>
				<div class="row cl">
					<label class="form-label col-3"><?php echo e($v->description); ?>：</label>
					<div class="formControls col-6 skin-minimal">
						<div class="radio-box">
							<input type="radio" id="<?php echo e($v->sys_key); ?>1" name="<?php echo e($v->sys_key); ?>" value="1" <?php echo $v->sys_value == 1 ? 'checked' : ''; ?>>
							<label for="<?php echo e($v->sys_key); ?>1">Yes</label>
						</div>
						<div class="radio-box">
							<input type="radio" id="<?php echo e($v->sys_key); ?>2" name="<?php echo e($v->sys_key); ?>" value="0" <?php echo $v->sys_value == 0 ? 'checked' : ''; ?>>
							<label for="<?php echo e($v->sys_key); ?>2">No</label>
						</div>
					</div>
					<div class="col-3"> </div>
				</div>
				<?php elseif($v->input_type == 2): ?>
				<div class="row cl">
					<label class="form-label col-3"><?php echo e($v->description); ?>：</label>
					<div class="formControls col-6">
						<textarea name="<?php echo e($v->sys_key); ?>" cols="" rows="" class="textarea"  placeholder="At least 10 characters" datatype="*10-1000" dragonfly="true" nullmsg="Required" onKeyUp="textarealength(this,1000)"><?php echo e($v->sys_value); ?></textarea>
						<p class="textarea-numberbar"><em class="textarea-length">0</em>/1000</p>
					</div>
					<div class="col-3"> </div>
				</div>
				<?php elseif($v->input_type == 3): ?>
				<div class="row cl">
					<label class="form-label col-3"><?php echo e($v->description); ?>：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e($v->sys_value); ?>" placeholder="" id="" name="<?php echo e($v->sys_key); ?>" readonly>
					</div>
					<div class="formControls col-3">
						<input id="<?php echo e($v->sys_key); ?>" name="<?php echo e($v->sys_key); ?>" type="file" multiple="true">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-3">Preview：</label>
					<div class="formControls col-6">
						<img src="<?php echo e(url('/public/'.$v->sys_value)); ?>" width="100" class="<?php echo e($v->sys_key); ?>" />
					</div>
				</div>
				<script>
                    $('#<?php echo e($v->sys_key); ?>').uploadify({
                        'buttonText' : 'BROWSE...',
                        'formData'     : {
                            'timestamp' : '<?php echo time(); ?>',
                            '_token'     : '<?php echo e(csrf_token()); ?>'
                        },
                        'swf'      : "<?php echo e(asset('/public/root/lib/uploadify/uploadify.swf')) ?>",
                        'uploader' : "<?php echo e(url('/admin/upload_one/system')) ?>",
                        'onUploadSuccess' : function(file, data, response){

                            var oldImg = $('input[name=<?php echo e($v->sys_key); ?>]').val();
                            if(oldImg){
                                $.post('<?php echo e(url('/admin/delete_one')); ?>', {'_token':'<?php echo e(csrf_token()); ?>','url':oldImg}, function(data){
                                    if(data.status == '0'){
                                        layer.msg(data.msg, {icon:1, time:2000});
                                    }else{
                                        layer.msg(data.msg, {icon:2, time:2000});
                                    }
                                });
                            }

                            $('input[name=<?php echo e($v->sys_key); ?>]').val(data);
                            $('.<?php echo e($v->sys_key); ?>').attr('src', '/public/'+data);
                        }
                    });
				</script>
				<?php elseif($v->input_type == 4): ?>
				<div class="row cl">
					<label class="form-label col-3"><?php echo e($v->description); ?>：</label>
					<div class="formControls col-2">
						<input type="text" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="input-text Wdate" style="width:240px;" name="<?php echo e($v->sys_key); ?>" value="<?php echo e(date('Y-m-d', $v->sys_value)); ?>">
					</div>
				</div>
				<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<div class="tabCon">
                <?php foreach($systemData2 as $v): ?>
                <?php if($v->input_type == 0): ?>
				<div class="row cl">
					<label class="form-label col-3"><?php echo e($v->description); ?>：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e($v->sys_value); ?>" placeholder="" id="" name="<?php echo e($v->sys_key); ?>">
					</div>
					<div class="col-3"> </div>
				</div>
                <?php elseif($v->input_type == 1): ?>
				<div class="row cl">
					<label class="form-label col-3"><?php echo e($v->description); ?>：</label>
					<div class="formControls col-6 skin-minimal">
						<div class="radio-box">
							<input type="radio" id="<?php echo e($v->sys_key); ?>1" name="<?php echo e($v->sys_key); ?>" value="1" <?php echo $v->sys_value == 1 ? 'checked' : ''; ?>>
							<label for="<?php echo e($v->sys_key); ?>1">Yes</label>
						</div>
						<div class="radio-box">
							<input type="radio" id="<?php echo e($v->sys_key); ?>2" name="<?php echo e($v->sys_key); ?>" value="0" <?php echo $v->sys_value == 0 ? 'checked' : ''; ?>>
							<label for="<?php echo e($v->sys_key); ?>2">No</label>
						</div>
					</div>
					<div class="col-3"> </div>
				</div>
                <?php elseif($v->input_type == 2): ?>
				<div class="row cl">
					<label class="form-label col-3"><?php echo e($v->description); ?>：</label>
					<div class="formControls col-6">
						<textarea name="<?php echo e($v->sys_key); ?>" cols="" rows="" class="textarea"  placeholder="At least 10 characters" datatype="*10-100" dragonfly="true" nullmsg="Required" onKeyUp="textarealength(this,100)"><?php echo e($v->sys_value); ?></textarea>
						<p class="textarea-numberbar"><em class="textarea-length">0</em>/100</p>
					</div>
					<div class="col-3"> </div>
				</div>
                <?php elseif($v->input_type == 3): ?>
				<div class="row cl">
					<label class="form-label col-3"><?php echo e($v->description); ?>：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e($v->sys_value); ?>" placeholder="" id="" name="<?php echo e($v->sys_key); ?>" readonly>
					</div>
					<div class="formControls col-3">
						<input id="<?php echo e($v->sys_key); ?>" name="<?php echo e($v->sys_key); ?>" type="file" multiple="true">
					</div>
				</div>
				<div class="row cl">
					<label class="form-label col-3">Preview：</label>
					<div class="formControls col-6">
						<img src="<?php echo e(url('/'.$v->sys_value)); ?>" width="240" class="<?php echo e($v->sys_key); ?>" />
					</div>
				</div>
				<script>
                    $('#<?php echo e($v->sys_key); ?>').uploadify({
                        'buttonText' : 'BROWSE...',
                        'formData'     : {
                            'timestamp' : '<?php echo time(); ?>',
                            '_token'     : '<?php echo e(csrf_token()); ?>'
                        },
                        'swf'      : "<?php echo e(asset('public/root/lib/uploadify/uploadify.swf')) ?>",
                        'uploader' : "<?php echo e(url('/admin/upload_one/system')) ?>",
                        'onUploadSuccess' : function(file, data, response){

                            var oldImg = $('input[name=<?php echo e($v->sys_key); ?>]').val();
                            if(oldImg){
                                $.post('<?php echo e(url('/admin/delete_one')); ?>', {'_token':'<?php echo e(csrf_token()); ?>','url':oldImg}, function(data){
                                    if(data.status == '0'){
                                        layer.msg(data.msg, {icon:1, time:2000});
                                    }else{
                                        layer.msg(data.msg, {icon:2, time:2000});
                                    }
                                });
                            }

                            $('input[name=<?php echo e($v->sys_key); ?>]').val(data);
                            $('.<?php echo e($v->sys_key); ?>').attr('src', '/'+data);
                        }
                    });
				</script>
                <?php endif; ?>
                <?php endforeach; ?>
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
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/My97DatePicker/WdatePicker.js')) ?>"></script>
<script type="text/javascript">
    $(function(){
        $.Huitab("#tab-category .tabBar span","#tab-category .tabCon","current","click","0");

        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });
    });
</script>
@parent
@endsection