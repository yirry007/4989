@extends('admin.layout')
@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Main <span class="c-gray en">&gt;</span> Orders <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="Refresh" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="text-c">
		<form action="<?php echo e(url('admin/orders')); ?>" method="get">
			Payment Price：
			<select class="input-text" style="width: 100px; vertical-align: -2px;" name="is_pay">
				<option value="">선택</option>
				<option value="0" <?php echo app('request')->input('is_pay') == '0' ? 'selected' : ''; ?>>Not Paid</option>
				<option value="1" <?php echo app('request')->input('is_pay') == '1' ? 'selected' : ''; ?>>Paid</option>
			</select>
			Shipment：
			<select class="input-text" style="width: 100px; vertical-align: -2px;" name="is_send">
				<option value="">Please Select</option>
				<option value="0" <?php echo app('request')->input('is_send') == '0' ? 'selected' : ''; ?>>Unshipped</option>
				<option value="1" <?php echo app('request')->input('is_send') == '1' ? 'selected' : ''; ?>>Shipped</option>
				<option value="2" <?php echo app('request')->input('is_send') == '2' ? 'selected' : ''; ?>>Received</option>
			</select>
			<input type="text" name="nickname" value="<?php echo e(app('request')->input('nickname')) ?>" id="" placeholder=" Nickname" style="width:150px" class="input-text">
			<input type="text" name="name" value="<?php echo e(app('request')->input('name')) ?>" id="" placeholder=" Receiver" style="width:150px" class="input-text">
			<input type="text" name="phone" value="<?php echo e(app('request')->input('phone')) ?>" id="" placeholder=" Phone" style="width:150px" class="input-text">
			<input type="text" name="order_num" value="<?php echo e(app('request')->input('order_num')) ?>" id="" placeholder=" Order Number" style="width:300px" class="input-text">
			<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> Search</button>
		</form>
	</div>
	<div class="cl pd-5 bg-1 bk-gray"> <span class="r">Total：<strong><?php echo e($dataCount); ?></strong> Rows</span> </div>
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="12">Orders</th>
			</tr>
			<tr class="text-c">
				<th>ID</th>
				<th>Order Number</th>
				<th>Nickname</th>
				<th>Receiver</th>
				<th>Phone</th>
				<th>Address</th>
				<th>Payment Price</th>
				<th>Payment Status</th>
				<th>Shipment</th>
				<th>Express</th>
				<th>Waybill</th>
				<th>Order Time</th>
				<th>OPT</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($orderData as $v):
				switch($v->express){
					case 'tiantian':
                        $express = "tian tian";
						break;
                    case 'debang':
                        $express = "de bang";
                        break;
					case 'yousuwuliu':
                        $express = "you su";
						break;
					default:
                        $express = $v->express;
				}
			?>
			<tr class="text-c">
				<td><?php echo e($v->id); ?></td>
				<td><?php echo e($v->order_num); ?></td>
				<td><?php echo e($v->nickname); ?></td>
				<td><?php echo e($v->name); ?></td>
				<td><?php echo e($v->phone); ?></td>
				<td><?php echo e($v->address); ?></td>
				<td><?php echo e($v->all_price + $v->express_price); ?></td>
				<td><span class="label <?php echo $v->is_pay == 1 ? 'label-success' : ''; ?> radius"><?php echo $v->is_pay == 1 ? 'Paid' : 'Not Paid'; ?></span></td>
				<td><span class="label <?php echo $v->is_send == 0 ? '' : 'label-success'; ?> radius"><?php $info = ''; switch($v->is_send){case 0: $info = 'Unshipped'; break; case 1: $info = 'Shipped'; break; case 2: $info = 'Received'; break; default: $info = 'undefined';} echo $info; ?></span></td>
				<td><?php echo $express; ?></td>
				<td><?php echo str_replace(';', '<br/>', $v->express_num); ?></td>
				<td><?php echo e(date('Y-m-d H:i', $v->addtime)) ?></td>
				<td class="f-14">
					<a title="Order Goods" href="javascript:void(0);" onclick="orderGoods(<?php echo e($v->id); ?>);" style="text-decoration:none"><i class="Hui-iconfont">&#xe620;</i></a>
					<a title="Edit" href="<?php echo e(url('/admin/orders/'.e($v->id).'/edit?page='.app('request')->input('page'))); ?>" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
				</td>
			</tr>
			<?php endforeach; ?>
			<tr class="text-c">
				<td class="page_td" colspan="12">
					<div class="page_list"><?php echo $pageShow; ?></div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/My97DatePicker/WdatePicker.js')) ?>"></script>
<script>
    function orderGoods(id){
        layer.open({
            type: 2,
            title: 'Order Goods',
            shadeClose: false,
            shade: 0.6,
            area: ['90%', '90%'],
            maxmin: true,
            content: '<?php echo e(url('/admin/order_goods')); ?>/'+id //iframe的url
        });
    }
</script>
@endsection