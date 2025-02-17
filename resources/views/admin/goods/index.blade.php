@extends('admin.layout')
@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Main <span class="c-gray en">&gt;</span> Goods <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="Refresh" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="text-c">
		<form action="<?php echo e(url('admin/goods')); ?>" method="get">
			<input type="text" name="name" value="<?php echo e(app('request')->input('name')); ?>" id="" placeholder=" Name" style="width:150px" class="input-text">
			<input type="text" name="other_name" value="<?php echo e(app('request')->input('other_name')); ?>" id="" placeholder=" Other Name" style="width:150px" class="input-text">
			Category：
			<select class="input-text" style="width: 100px; vertical-align: -2px;" name="category_id">
				<option value="-1">Please Select</option>
				<?php foreach($categoryData as $v): ?>
				<option value="<?php echo e($v->id); ?>" <?php echo app('request')->input('category_id') == $v->id ? 'selected' : ''; ?>><?php echo e($v->name); ?></option>
				<?php endforeach; ?>
			</select>
			Brand：
			<select class="input-text" style="width: 100px; vertical-align: -2px;" name="brand_id">
				<option value="-1">Please Select</option>
                <?php foreach($brandData as $v): ?>
				<option value="<?php echo e($v->id); ?>" <?php echo app('request')->input('brand_id') == $v->id ? 'selected' : ''; ?>><?php echo e($v->name); ?></option>
                <?php endforeach; ?>
			</select>
			Province：
			<select class="input-text" style="width: 100px; vertical-align: -2px;" name="express_id">
				<option value="-1">Please Select</option>
				<option value="0" <?php echo app('request')->input('express_id') == '0' ? 'selected' : ''; ?>>National</option>
                <?php foreach($expressData as $v): ?>
				<option value="<?php echo e($v->id); ?>" <?php echo app('request')->input('express_id') == $v->id ? 'selected' : ''; ?>><?php echo e($v->province_name); ?></option>
                <?php endforeach; ?>
			</select>
			Five Day：
			<select class="input-text" style="width: 100px; vertical-align: -2px;" name="fiveday">
				<option value="-1">选择</option>
				<option value="0" <?php echo app('request')->input('fiveday') == '0' ? 'selected' : ''; ?>>Normal</option>
				<option value="1" <?php echo app('request')->input('fiveday') == '1' ? 'selected' : ''; ?>>Five Day</option>
			</select>
			<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> Search</button>
		</form>
	</div>
	<div class="cl pd-5 bg-1 bk-gray"> <span class="l"> <a class="btn btn-primary radius" href="<?php echo e(url('/admin/goods/create')); ?>"><i class="Hui-iconfont">&#xe600;</i> Add</a> </span> <span class="r">Total：<strong><?php echo e($dataCount); ?></strong> Rows</span> </div>
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="24">Goods</th>
			</tr>
			<tr class="text-c">
				<th>ID</th>
				<th>Name</th>
				<th>Other Name</th>
				<th>Category</th>
				<th>Brand</th>
				<th>Video</th>
				<th>Image</th>
				<th>VIP price</th>
				<th>O.Price</th>
				<th>Weight(kg)</th>
				<th>Province</th>
				<th>Stock</th>
				<th>Minimum Buy</th>
				<th>Sale Volume</th>
				<th>Is Enable</th>
				<th>Is Rec.</th>
				<th>Is Promotion</th>
				<th>Single Buy</th>
				<th>Is Special</th>
				<th>Five Day</th>
				<th>F.price</th>
				<th>Sort</th>
				<th>OPT</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($goodsData as $v): ?>
			<tr class="text-c">
				<td><?php echo e($v->id); ?></td>
				<td><?php echo e($v->name); ?></td>
				<td><?php echo e($v->other_name); ?></td>
				<td><?php echo e($v->cat_name); ?></td>
				<td><?php echo e($v->brand_name); ?></td>
				<td><span class="label <?php echo $v->video ? 'label-success' : ''; ?> radius"><?php echo $v->video ? 'Has' : 'None'; ?></span></td>
				<td><img src="<?php echo e(url('public/'.$v->image)); ?>" width="60" height="60" alt="" /></td>
				<td><?php echo e($v->price); ?></td>
				<td><?php echo e($v->org_price); ?></td>
				<td><?php echo e($v->weight); ?></td>
				<td><?php echo $v->express_id ? e($v->province_name) : 'National'; ?></td>
				<td><?php echo e($v->stock); ?></td>
				<td><?php echo e($v->limit_num) ?></td>
				<td><?php echo e($v->sale) ?></td>
				<td><span class="label <?php echo $v->is_use == 1 ? 'label-success' : ''; ?> radius"><?php echo $v->is_use == 1 ? 'Enable' : 'Disable'; ?></span></td>
				<td><span class="label <?php echo $v->is_rec == 1 ? 'label-success' : ''; ?> radius"><?php echo $v->is_rec == 1 ? 'Enable' : 'Disable'; ?></span></td>
				<td><span class="label <?php echo $v->is_sale == 1 ? 'label-success' : ''; ?> radius"><?php echo $v->is_sale == 1 ? 'Enable' : 'Disable'; ?></span></td>
				<td><span class="label <?php echo $v->is_one == 1 ? 'label-success' : ''; ?> radius"><?php echo $v->is_one == 1 ? 'Single' : 'Multi'; ?></span></td>
				<td><span class="label <?php echo $v->special == 1 ? 'label-success' : ''; ?> radius"><?php echo $v->special == 1 ? 'Special' : 'Normal'; ?></span></td>
				<td><span class="label <?php echo $v->fiveday == 1 ? 'label-success' : ''; ?> radius"><?php echo $v->fiveday == 1 ? 'Five Day' : 'Normal'; ?></span></td>
				<td><?php echo e($v->market_price); ?></td>
				<td><?php echo e($v->sort); ?></td>
				<td class="f-14">
					<a title="Edit" href="<?php echo e(url('/admin/goods/'.e($v->id).'/edit?page='.app('request')->input('page').'&category_id='.app('request')->input('category_id').'&brand_id='.app('request')->input('brand_id').'&fiveday='.app('request')->input('fiveday'))) ?>" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
					<a title="Del" href="javascript:void(0);" class="ml-5" style="text-decoration:none" onclick="destroy(<?php echo e($v->id); ?>)"><i class="Hui-iconfont">&#xe6e2;</i></a>
				</td>
			</tr>
			<?php endforeach; ?>
			<tr class="text-c">
				<td class="page_td" colspan="24">
					<div class="page_list"><?php echo $pageShow; ?></div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/My97DatePicker/WdatePicker.js')) ?>"></script>
<script>
    function destroy(id){
//        if(id == 1){
//            layer.msg('不能删除超级管理员', {icon:2, time:2000});
//            return false;
//        }
        layer.confirm('Are you sure to delete it?', {
            btn : ['Yes', 'No']
        }, function(){
            $.post('<?php echo e(url('/admin/goods')); ?>/'+id, {'_method':'delete','_token':'<?php echo e(csrf_token()); ?>'}, function(data){
                if(data.status == '0'){
                    layer.msg(data.msg, {icon:1, time:2000}, function(){
                        window.location.reload();
                    });
                }else{
                    layer.msg(data.msg, {icon:2, time:2000});
                }
            });
        }, function(){
            layer.closeAll();
        });
    }
</script>
@endsection