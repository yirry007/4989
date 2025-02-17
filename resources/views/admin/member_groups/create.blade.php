@extends('admin.layout')
@section('content')
<div class="pd-20">
  <form action="<?php echo e(url('/admin/member_groups')); ?>" method="post" class="form form-horizontal" id="form-member-add">
  	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
    <div class="row cl">
		<label class="form-label col-3">Name：</label>
		<div class="formControls col-6">
			<input type="text" class="input-text" value="<?php echo e(session('input')['name']); ?>" placeholder="" name="name" >
		</div>
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