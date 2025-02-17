@extends('admin.layout')
@section('content')
<div class="pd-20">
  <form action="<?php echo e(url('/admin/member_groups/'.$field->id)); ?>" method="post" class="form form-horizontal">
  	<input type="hidden" name="_method" value="put" />
  	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
  	<input type="hidden" name="page" value="<?php echo e(app('request')->input('page')); ?>" />
      <div class="row cl">
          <label class="form-label col-3">Name：</label>
          <div class="formControls col-6">
              <input type="text" class="input-text" value="<?php echo e($field->name); ?>" placeholder="" name="name" >
          </div>
      </div>
    <div class="row cl">
      <div class="col-8 col-offset-4">
        <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;submit&nbsp;&nbsp;">
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">

</script>
@parent
@endsection