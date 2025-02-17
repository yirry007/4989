@extends('admin.layout')
@section('content')
<div class="pd-20">
	<form action="<?php echo e(url('/admin/env')); ?>" method="post" class="form form-horizontal" id="form-category-add">
		<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
		<div id="tab-category" class="HuiTab">
			<div class="tabBar cl"><span>Email SMTP</span><span>Official Account</span><span>Wechat Pay</span></div>
			<div class="tabCon">
				<div class="row cl">
					<label class="form-label col-3"><span class="c-red">*</span>Email Driver：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.MAIL_DRIVER')); ?>" placeholder="" id="" name="MAIL_DRIVER">
					</div>
					<div class="col-3"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-3"><span class="c-red">*</span>Server Host：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.MAIL_HOST')); ?>" placeholder="" id="" name="MAIL_HOST">
					</div>
					<div class="col-3"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-3"><span class="c-red">*</span>Server Port：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.MAIL_PORT')); ?>" placeholder="" id="" name="MAIL_PORT">
					</div>
					<div class="col-3"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-3"><span class="c-red">*</span>Username：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.MAIL_USERNAME')); ?>" placeholder="" id="" name="MAIL_USERNAME">
					</div>
					<div class="col-3"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-3"><span class="c-red">*</span>Password：</label>
					<div class="formControls col-6">
						<input type="password" class="input-text" value="<?php echo e(Config::get('web.MAIL_PASSWORD')); ?>" placeholder="" id="" name="MAIL_PASSWORD">
					</div>
					<div class="col-3"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-3"><span class="c-red">*</span>Address：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.MAIL_ADDRESS')); ?>" placeholder="" id="" name="MAIL_ADDRESS">
					</div>
					<div class="col-3"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-3"><span class="c-red">*</span>Sender：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.MAIL_NAME')); ?>" placeholder="" id="" name="MAIL_NAME">
					</div>
					<div class="col-3"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-3"><span class="c-red">*</span>Title：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.MAIL_SUBJECT')); ?>" placeholder="" id="" name="MAIL_SUBJECT">
					</div>
					<div class="col-3"> </div>
				</div>
			</div>
			<div class="tabCon">
				<div class="row cl">
					<label class="form-label col-4"><span class="c-red">*</span>APPID：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.WE_APPID')); ?>" placeholder="" id="" name="WE_APPID">
					</div>
					<div class="col-2"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-4"><span class="c-red">*</span>TOKEN：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.WE_TOKEN')); ?>" placeholder="" id="" name="WE_TOKEN">
					</div>
					<div class="col-2"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-4"><span class="c-red">*</span>SECRET：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.WE_SECRET')); ?>" placeholder="" id="" name="WE_SECRET">
					</div>
					<div class="col-2"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-4"><span class="c-red">*</span>AESKey：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.WE_AESKEY')); ?>" placeholder="" id="" name="WE_AESKEY">
					</div>
					<div class="col-2"> </div>
				</div>
			</div>
			<div class="tabCon">
				<div class="row cl">
					<label class="form-label col-4"><span class="c-red">*</span>Official Account APPID：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.PUBLIC_APPID')); ?>" placeholder="" id="" name="PUBLIC_APPID">
					</div>
					<div class="col-2"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-4"><span class="c-red">*</span>Merchant Id：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.MCHID')); ?>" placeholder="" id="" name="MCHID">
					</div>
					<div class="col-2"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-4"><span class="c-red">*</span>Merchant Key：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.KEY')); ?>" placeholder="" id="" name="KEY">
					</div>
					<div class="col-2"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-4"><span class="c-red">*</span>App Secret：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(urldecode(Config::get('web.APPSECRET'))); ?>" placeholder="" id="" name="APPSECRET">
					</div>
					<div class="col-2"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-4"><span class="c-red">*</span>Redirect Url：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(urldecode(Config::get('web.PAY_REDIRECT'))); ?>" placeholder="" id="" name="PAY_REDIRECT">
					</div>
					<div class="col-2"> </div>
				</div>
				<div class="row cl">
					<label class="form-label col-4"><span class="c-red">*</span>Payment Key：</label>
					<div class="formControls col-6">
						<input type="text" class="input-text" value="<?php echo e(Config::get('web.PAYMENT_KEY')); ?>" placeholder="" id="" name="PAYMENT_KEY">
					</div>
					<div class="col-2"> </div>
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
<script type="text/javascript">
    $(function(){
        $.Huitab("#tab-category .tabBar span","#tab-category .tabCon","current","click","0");
    });
</script>
@parent
@endsection