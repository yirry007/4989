@extends('admin.layout')
@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Main <span class="c-gray en">&gt;</span> Sale Volume <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="Refresh" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="text-c">
		<form action="<?php echo e(url('admin/sale_goods')); ?>" method="get">
			Sort：
			<select class="input-text" style="width: 100px; vertical-align: -2px;" name="order">
				<option value="">Please Select</option>
				<option value="DESC" <?php echo app('request')->input('order') == 'DESC' ? 'selected' : ''; ?>>DESC</option>
				<option value="ASC" <?php echo app('request')->input('order') == 'ASC' ? 'selected' : ''; ?>>ASC</option>
			</select>
			<input type="text" name="name" value="<?php echo e(app('request')->input('name')) ?>" id="" placeholder=" Goods Name" style="width:200px" class="input-text">
			<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> Search</button>
		</form>
	</div>
	<div class="cl pd-5 bg-1 bk-gray"></div>
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
		<tr>
			<th scope="col" colspan="5">Sale Volume</th>
		</tr>
		<tr class="text-c">
			<th width="35%">Goods Name</th>
			<th width="20%">Image</th>
			<th width="15%">Price</th>
			<th width="15%">Weight</th>
			<th width="15%">Volume</th>
		</tr>
		</thead>
		<tbody>
        <?php foreach($saleGoods as $v): ?>
		<tr class="text-c">
			<td><?php echo e($v->name); ?></td>
			<td><img src="<?php echo e(url('public/'.$v->image)); ?>" width="80" alt="" /></td>
			<td><?php echo e($v->price); ?></td>
			<td><?php echo e($v->weight); ?></td>
			<td><?php echo e($v->sale_num); ?></td>
		</tr>
        <?php endforeach; ?>
		<tr class="text-c">
			<td class="page_td" colspan="5">
				<div class="page_list"><?php echo $pageShow; ?></div>
			</td>
		</tr>
		</tbody>
	</table>
</div>
@endsection