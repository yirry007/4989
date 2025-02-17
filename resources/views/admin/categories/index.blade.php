@extends('admin.layout')
@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Main <span class="c-gray en">&gt;</span> Category <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="Refresh" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray"> <span class="l"> <a class="btn btn-primary radius" href="<?php echo e(url('/admin/categories/create')); ?>"><i class="Hui-iconfont">&#xe600;</i> Add</a> </span> <span class="r">Total：<strong><?php echo e(count($categoryData)); ?></strong> Rows</span> </div>
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="7">Category</th>
			</tr>
			<tr class="text-c">
				<th>ID</th>
				<th>Name</th>
				<th>Image</th>
				<th>Is Enable</th>
				<th>Is Rec.</th>
				<th>Sort</th>
				<th>OPT</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($categoryData as $v): ?>
			<tr class="text-c tr_level<?php echo e($v->level); ?>">
				<td><?php echo e($v->id); ?></td>
				<td style="text-align:left;"><?php echo e(str_repeat('&nbsp;', 16*$v->level)); ?><a href="javascript:void(0);" class="level<?php echo e($v->level); ?>">+</a> <?php echo e($v->name); ?></td>
				<td><img src="<?php echo e(url('public/'.$v->image)); ?>" width="60" height="60" alt="" /></td>
				<td><span class="label <?php echo $v->is_use == 1 ? 'label-success' : ''; ?> radius"><?php echo $v->is_use == 1 ? 'Enable' : 'Disable'; ?></span></td>
				<td><span class="label <?php echo $v->is_rec == 1 ? 'label-success' : ''; ?> radius"><?php echo $v->is_rec == 1 ? 'Enable' : 'Disable'; ?></span></td>
				<td><?php echo e($v->sort); ?></td>
				<td class="f-14">
					<a title="Edit" href="<?php echo e(url('/admin/categories/'.e($v->id).'/edit?page='.app('request')->input('page'))) ?>" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
					<a title="Del" href="javascript:void(0);"class="ml-5" style="text-decoration:none" onclick="destroy(<?php echo e($v->id); ?>)"><i class="Hui-iconfont">&#xe6e2;</i></a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/My97DatePicker/WdatePicker.js')) ?>"></script>
<script>

    $('.level1, .level2').parents('tr').hide();

    $('.level0').click(function(){
        var html = $(this).html();

        if(html == '+'){
            $(this).parents('tr').nextUntil('.tr_level0').show();
            $('.level2').parents('tr').hide();
            $(this).html('-');
        }else if(html == '-'){
            $(this).parents('tr').nextUntil('.tr_level0').hide();
            $(this).html('+');
        }
    });

    $('.level1').click(function(){
        var html = $(this).html();

        if(html == '+'){
            $(this).parents('tr').nextUntil('.tr_level1').show();
            $(this).html('-');
        }else if(html == '-'){
            $(this).parents('tr').nextUntil('.tr_level1').hide();
            $(this).html('+');
        }
    });

    function destroy(id){
//        if(id == 1){
//            layer.msg('不能删除超级管理员', {icon:2, time:2000});
//            return false;
//        }
        layer.confirm('Are you sure to delete it?', {
            btn : ['Yes', 'No']
        }, function(){
            $.post('<?php echo e(url('/admin/categories')); ?>/'+id, {'_method':'delete','_token':'<?php echo e(csrf_token()); ?>'}, function(data){
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