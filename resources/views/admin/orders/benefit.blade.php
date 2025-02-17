@extends('admin.layout')
@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Main <span class="c-gray en">&gt;</span> Partner Benefit <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="Refresh" ><i class="Hui-iconfont">&#xe68f;</i></a><a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="<?php echo e(url('admin/partner_excel?partner_id='.app('request')->input('partner_id').'&addtime_from='.app('request')->input('addtime_from').'&addtime_to='.app('request')->input('addtime_to'))); ?>" title="Download Excel" target="_blank">Excel</a></nav>
<div class="pd-20">
	<div class="text-c">
		<form action="<?php echo e(url('admin/benefit')); ?>" method="get">
			Member Group：
			<select class="input-text" style="width: 100px; vertical-align: -2px;" name="partner_id">
				<option value="-1">Please Select</option>
				<?php foreach($partners as $v): ?>
				<option value="<?php echo e($v->id); ?>" <?php echo app('request')->input('partner_id') == $v->id ? 'selected' : ''; ?>><?php echo e($v->name); ?></option>
				<?php endforeach; ?>
			</select>
			Order Time：
			<input type="text" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'logmax\')||\'%y-%M-%d\'}'})" name="addtime_from" value="<?php echo e(app('request')->input('addtime_from')) ?>" id="logmin" class="input-text Wdate" style="width:120px;" autocomplete="off">
			-
			<input type="text" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'logmin\')||\'%y-%M-%d\'}'})" name="addtime_to" value="<?php echo e(app('request')->input('addtime_to')) ?>" id="logmax" class="input-text Wdate" style="width:120px;" autocomplete="off">
			<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> Search</button>
		</form>
	</div>
	<div class="cl pd-5 bg-1 bk-gray"> <span class="r">Total：<strong><?php echo e(count($orderData)); ?></strong> Rows</span> </div>
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="10">Partner Benefit</th>
			</tr>
			<tr class="text-c">
				<th>ID</th>
				<th>Partner name</th>
				<th>Order Number</th>
				<th>Receiver</th>
				<th>Phone</th>
				<th>Address</th>
				<th>Payment price</th>
				<th>Benefit Rate</th>
				<th>Benefit Amount</th>
				<th>Order Time</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$total = 0;
				foreach($orderData as $v):
			?>
			<tr class="text-c">
				<td><?php echo e($v->id); ?></td>
				<td><?php echo e($v->partner_name); ?></td>
				<td><?php echo e($v->order_num); ?></td>
				<td><?php echo e($v->name); ?></td>
				<td><?php echo e($v->phone); ?></td>
				<td><?php echo  e($v->address); ?></td>
				<td><?php echo e($v->all_price); ?></td>
				<td><?php echo e($v->rate); ?></td>
				<td><?php $subTotal = e(number_format($v->all_price*$v->rate/100, 2, '.', ''));$total += $subTotal;echo $subTotal; ?></td>
				<td><?php echo e(date('Y-m-d H:i', $v->addtime)) ?></td>
			</tr>
			<?php endforeach; ?>
			<tr class="text-r">
				<td colspan="10">Benefit Total： <?php echo e($total); ?></td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/My97DatePicker/WdatePicker.js')) ?>"></script>
@endsection