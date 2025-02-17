@extends('admin.layout')
@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Main <span class="c-gray en">&gt;</span> Print Orders <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="Refresh" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="text-c">
		<form action="{{ url('admin/order_print_view') }}" method="get">
			<input type="text" name="time_from" value="{{ $search['time_from'] }}" onfocus="WdatePicker({ maxDate:'#F{$dp.$D(\'datemax\')||\'%y-%M-%d\'}' })" id="datemin" class="input-text Wdate" style="width:100px;">
			-
			<input type="text" name="time_to" value="{{ $search['time_to'] }}" onfocus="WdatePicker({ minDate:'#F{$dp.$D(\'datemin\')}',maxDate:'%y-%M-%d' })" id="datemax" class="input-text Wdate" style="width:100px;">
			<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> Search</button>
		</form>
	</div>
	<div class="cl pd-5 bg-1 bk-gray"><span class="l"> <a class="btn btn-primary radius" href="{{ url('admin/order_print?time_from='.$search['time_from'].'&time_to='.$search['time_to']) }}" target="_blank">Print Excel</a> </span> <span class="r">Total：<strong>{{ count($datas) }}</strong> Rows</span> </div>
	<table class="table table-border table-bordered table-hover table-bg" style="min-width:860px;">
		<thead>
			<tr>
				<th scope="col" colspan="6">Print Orders</th>
			</tr>
			<tr class="text-c">
				<th width="120">Order Time</th>
				<th width="180">Brand</th>
				<th width="200">Goods Name</th>
				<th width="40">Number</th>
				<th width="60">Receiver</th>
				<th width="260">Address</th>
			</tr>
		</thead>
		<tbody>
			@foreach($datas as $v)
			<tr class="text-l">
				<td>{{ date('Y-m-d H:i', $v->addtime) }}</td>
				<td>{{ $v->brand_name }}</td>
				<td>{{ $v->name }}</td>
				<td>{{ $v->goods_num }}</td>
				<td>{{ $v->consignee }}</td>
				<td>{{ $v->address }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
<script type="text/javascript" src="{{ asset('public/root/lib/My97DatePicker/WdatePicker.js') }}"></script>
<script>

</script>
@endsection