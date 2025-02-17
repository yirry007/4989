@extends('admin.layout')
@section('content')
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="6">Order Goods</th>
			</tr>
			<tr class="text-c">
				<th>ID</th>
				<th>Goods Name</th>
				<th>Image</th>
				<th>Brand</th>
				<th>Price</th>
				<th>Number</th>
			</tr>
		</thead>
		<tbody>
        <?php foreach($goodsData as $v): ?>
		<tr class="text-c">
			<td><?php echo e($v->id); ?></td>
			<td><?php echo e($v->name); ?></td>
			<td><img src="<?php echo e(url('public/'.$v->image)); ?>" width="60" alt="" /></td>
			<td><?php echo e($v->brand_name); ?></td>
			<td><?php echo e($v->price); ?></td>
			<td><?php echo e($v->goods_num); ?></td>
		</tr>
        <?php endforeach; ?>
		</tbody>
	</table>
	<script>

	</script>
@endsection