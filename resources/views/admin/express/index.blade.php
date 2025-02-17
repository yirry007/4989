@extends('admin.layout')
@section('content')
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Main <span class="c-gray en">&gt;</span> Express <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="Refresh" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="pd-20">
        <form action="<?php echo e(url('/admin/express_edit')); ?>" method="post" class="form form-horizontal">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <table class="table table-border table-bordered table-hover table-bg">
                <thead>
                <tr>
                    <th scope="col" colspan="6">Express</th>
                </tr>
                <tr>
                    <th scope="col" colspan="6">YOUSU/TIANTIAN Express</th>
                </tr>
                <tr class="text-c">
                    <th>Destination</th>
                    <th>Starting price</th>
                    <th>Overweight(kg)</th>
                    <th>Overweight Per Price</th>
                    <th>Arrival days</th>
                    <th>Is Same City</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($express as $v): ?>
                <tr class="text-c">
                <td><?php echo e($v->province_name); ?></td>
                <td><input type="text" class="input-text" value="<?php echo e($v->default_price); ?>" onkeyup="this.value=this.value.replace(/[^\d.]/g, '');" placeholder="default 0" name="default_price[<?php echo e($v->province); ?>]" ></td>
                <td><input type="text" class="input-text" value="<?php echo e($v->base_weight); ?>" onkeyup="this.value=this.value.replace(/[^\d.]/g, '');" placeholder="default 0" name="base_weight[<?php echo e($v->province); ?>]" ></td>
                <td><input type="text" class="input-text" value="<?php echo e($v->pre_weight_price); ?>" onkeyup="this.value=this.value.replace(/[^\d.]/g, '');" placeholder="default 0" name="pre_weight_price[<?php echo e($v->province); ?>]" ></td>
                <td><input type="text" class="input-text" value="<?php echo e($v->days); ?>" onkeyup="this.value=this.value.replace(/[^\d.]/g, '');" placeholder="default 0" name="days[<?php echo e($v->province); ?>]" ></td>
                <td>
					<div class="formControls col-6 skin-minimal">
					  <div class="radio-box">
						  <input type="radio" name="is_local[<?php echo e($v->province); ?>]" value="1" <?php if($v->is_local == '1') echo 'checked'; ?>>
						  <label>Same City</label>
					  </div>
					  <div class="radio-box">
						  <input type="radio" name="is_local[<?php echo e($v->province); ?>]" value="0" <?php if($v->is_local == '0') echo 'checked'; ?>>
						  <label>Different City</label>
					  </div>
				  </div>
				</td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="row cl">
                <div class="col-offset-5">
                    <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;Submit&nbsp;&nbsp;">
                </div>
            </div>
        </form>
    </div>
    @parent
@endsection