@extends('admin.layout')
@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Main <span class="c-gray en">&gt;</span> Withdraw <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray"> <span class="r">Total：<strong><?php echo e($dataCount); ?></strong> Rows</span> </div>
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="5">Withdraw</th>
			</tr>
			<tr class="text-c">
				<th>ID</th>
				<th>Serial No.</th>
				<th>Nickname</th>
				<th>Amount</th>
				<th>Created Time</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($withdrawData as $v): ?>
			<tr class="text-c">
				<td><?php echo e($v->id); ?></td>
				<td><?php echo e($v->sn); ?></td>
				<td><?php echo e($v->nickname); ?></td>
				<td><?php echo e($v->coin); ?></td>
				<td><?php echo e(date('Y-m-d H:i', $v->addtime)); ?></td>
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
<script>

</script>
@endsection