@extends('admin.layout')
@section('content')
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
		<tr>
			<th scope="col" colspan="3">Member Bonus</th>
		</tr>
		<tr class="text-c">
			<th>Bonus</th>
			<th>Create Time</th>
			<th>OPT</th>
		</tr>
		</thead>
		<tbody>
        <?php foreach($bonusData as $v): ?>
		<tr class="text-c">
			<td><?php echo e($v->money); ?></td>
			<td><?php echo e(date('Y-m-d H:i', $v->addtime)); ?></td>
			<td class="f-14">
				<a title="Del" href="javascript:void(0);"class="ml-5" style="text-decoration:none" onclick="destroy(<?php echo e($v->id); ?>)"><i class="Hui-iconfont">&#xe6e2;</i></a>
			</td>
		</tr>
        <?php endforeach; ?>
		</tbody>
	</table>
	<script>
        function destroy(id){
            layer.confirm('Are you sure to delete it?', {
                btn : ['Yes', 'No']
            }, function(){
                $.post('<?php echo e(url('/admin/bonus_del')); ?>/'+id, {'_token':'<?php echo e(csrf_token()); ?>'}, function(data){
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