@extends('admin.layout')
@section('content')
<div class="pd-20">
  <form action="<?php echo e(url('/admin/topup_moneys')); ?>" method="post" class="form form-horizontal" id="form-member-add">
  	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
    <div class="row cl">
		<label class="form-label col-3">Amount：</label>
		<div class="formControls col-6">
			<input type="number" class="input-text" value="<?php echo e(session('input')['money']); ?>" placeholder="" name="money" style="width:200px;" >
		</div>
    </div>
      <div class="row cl">
          <label class="form-label col-3">Memo：</label>
          <div class="formControls col-6">
              <textarea name="memo" cols="" rows="" class="textarea"  placeholder="" dragonfly="true" onKeyUp="textarealength(this,1000)"><?php echo e(session('input')['memo']); ?></textarea>
              <p class="textarea-numberbar"><em class="textarea-length">0</em>/1000</p>
          </div>
          <div class="col-3"> </div>
      </div>
    <div class="row cl">
      <div class="col-8 col-offset-4">
        <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;Submit&nbsp;&nbsp;">
      </div>
    </div>
  </form>
</div>
<script>

</script>
@parent
@endsection