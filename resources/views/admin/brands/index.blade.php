@extends('admin.layout')
@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Main <span class="c-gray en">&gt;</span> Brands <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="Refresh" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="text-c">
		<form action="<?php echo e(url('admin/brands')); ?>" method="get">
			<input type="text" name="name" value="<?php echo e(app('request')->input('name')) ?>" id="" placeholder=" Name" style="width:300px" class="input-text">
			<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> Search</button>
		</form>
	</div>
	<div class="cl pd-5 bg-1 bk-gray"> <span class="l"> <a class="btn btn-primary radius" href="<?php echo e(url('/admin/brands/create')); ?>"><i class="Hui-iconfont">&#xe600;</i> Add</a> </span> <span class="r">Total：<strong><?php echo e($dataCount); ?></strong> Rows</span> </div>
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="8">Brands</th>
			</tr>
			<tr class="text-c">
				<th>ID</th>
				<th>Name</th>
				<th>Image</th>
				<th>Is Enable</th>
				<th>Is Rec.</th>
				<th>Is K.House</th>
				<th>Sort</th>
				<th>OPT</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($brandData as $v): ?>
			<tr class="text-c">
				<td><?php echo e($v->id); ?></td>
				<td><?php echo e($v->name); ?></td>
				<td><img src="<?php echo e(url('public/'.($v->image ? $v->image : $system->sys_value))); ?>" width="60" height="60" alt="" /></td>
				<td><span class="label <?php echo $v->is_use == 1 ? 'label-success' : ''; ?> radius"><?php echo $v->is_use == 1 ? 'Enable' : 'Disable'; ?></span></td>
				<td><span class="label <?php echo $v->is_rec == 1 ? 'label-success' : ''; ?> radius"><?php echo $v->is_rec == 1 ? 'Enable' : 'Disable'; ?></span></td>
				<td><span class="label <?php echo $v->is_korea == 1 ? 'label-success' : ''; ?> radius"><?php echo $v->is_korea == 1 ? 'Enable' : 'Disable'; ?></span></td>
				<td><?php echo e($v->sort); ?></td>
				<td class="f-14">
					<a title="Edit" href="<?php echo e(url('/admin/brands/'.e($v->id).'/edit?page='.app('request')->input('page'))) ?>" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
					<a title="Del" href="javascript:void(0);"class="ml-5" style="text-decoration:none" onclick="destroy(<?php echo e($v->id); ?>)"><i class="Hui-iconfont">&#xe6e2;</i></a>
				</td>
			</tr>
			<?php endforeach; ?>
			<tr class="text-c">
				<td class="page_td" colspan="8">
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
            $.post('<?php echo e(url('/admin/brands')); ?>/'+id, {'_method':'delete','_token':'<?php echo e(csrf_token()); ?>'}, function(data){
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