@extends('admin.layout')
@section('content')
	<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Main <span class="c-gray en">&gt;</span> Ads <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="Refresh" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
	<div class="pd-20">
		<div class="cl pd-5 bg-1 bk-gray"> <span class="l"> <a class="btn btn-primary radius" href="<?php echo e(url('/admin/ads/create')); ?>"><i class="Hui-iconfont">&#xe600;</i> Add</a> </span> <span class="r">Total：<strong><?php echo e($dataCount); ?></strong> Rows</span> </div>
		<table class="table table-border table-bordered table-hover table-bg">
			<thead>
			<tr>
				<th scope="col" colspan="5">Main Banner</th>
			</tr>
			<tr class="text-c">
				<th>ID</th>
				<th>Image</th>
				<th>Link</th>
				<th>Sort</th>
				<th>OPT</th>
			</tr>
			</thead>
			<tbody>
            <?php foreach($adsData as $v): ?>
			<tr class="text-c">
				<td><?php echo e($v->id); ?></td>
				<td><img src="<?php echo e(url('public/'.$v->image)); ?>" width="200" alt="" /></td>
				<td><?php echo e($v->url); ?></td>
				<td><?php echo e($v->sort); ?></td>
				<td class="f-14">
					<a title="Edit" href="<?php echo e(url('/admin/ads/'.e($v->id).'/edit?page='.app('request')->input('page'))) ?>" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
					<a title="Delete" href="javascript:void(0);"class="ml-5" style="text-decoration:none" onclick="destroy(<?php echo e($v->id); ?>)"><i class="Hui-iconfont">&#xe6e2;</i></a>
				</td>
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
        function destroy(id){
            layer.confirm('Are you sure to delete it?', {
                btn : ['Yes', 'No']
            }, function(){
                $.post('<?php echo e(url('/admin/ads')); ?>/'+id, {'_method':'delete','_token':'<?php echo e(csrf_token()); ?>'}, function(data){
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