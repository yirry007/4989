@extends('admin.layout')
@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Main <span class="c-gray en">&gt;</span> Top up <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="text-c">
		<form action="<?php echo e(url('admin/topups')); ?>" method="get">
			Payment Status：
			<select class="input-text" style="width: 100px; vertical-align: -2px;" name="is_pay">
				<option value="-1" <?php echo app('request')->input('is_pay') == -1 ? 'selected' : ''; ?>>Please Select</option>
				<option value="1" <?php echo app('request')->input('is_pay') == 1 ? 'selected' : ''; ?>>Paid</option>
				<option value="0" <?php echo app('request')->input('is_pay') == 0 ? 'selected' : ''; ?>>Not Paid</option>
			</select>
			<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> Search</button>
		</form>
	</div>
	<div class="cl pd-5 bg-1 bk-gray"> <span class="r">Total：<strong><?php echo e($dataCount); ?></strong> Rows</span> </div>
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="6">Top up</th>
			</tr>
			<tr class="text-c">
				<th>ID</th>
				<th>Serial No.</th>
				<th>Nickname</th>
				<th>Amount</th>
				<th>Created Time</th>
				<th>Payment Status</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($topupData as $v): ?>
			<tr class="text-c">
				<td><?php echo e($v->id); ?></td>
				<td><?php echo e($v->topup_sn); ?></td>
				<td><?php echo e($v->nickname); ?></td>
				<td><?php echo e($v->money); ?></td>
				<td><?php echo e(date('Y-m-d H:i', $v->addtime)); ?></td>
				<td>
					<span class="label <?php echo $v->is_pay ? 'label-success' : ''; ?> radius">
					<?php
                        switch($v->is_pay){
                            case '0':echo 'Not Paid';break;
                            case '1':echo 'Paid';break;
                        }
                        ?>
					</span>
				</td>
			</tr>
			<?php endforeach; ?>
			<tr class="text-c">
				<td class="page_td" colspan="6">
					<div class="page_list"><?php echo $pageShow; ?></div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<script>

</script>
@endsection