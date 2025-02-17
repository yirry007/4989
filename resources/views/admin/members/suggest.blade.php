@extends('admin.layout')
@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Main <span class="c-gray en">&gt;</span> Suggest <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="Refresh" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray"> <span class="r">Total：<strong><?php echo e($dataCount); ?></strong> Rows</span> </div>
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="8">Suggest</th>
			</tr>
			<tr class="text-c">
				<th>ID</th>
				<th>Nickname</th>
				<th>Type</th>
				<th width="70%">Content</th>
				<th>
					Image
					<img src="" id="tmp_url" />
				</th>
				<th>Created Time</th>
				<th>Is Confirmed</th>
				<th>OPT</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($wishData as $k=>$v): ?>
			<tr class="text-c">
				<td><?php echo e($v->id); ?></td>
				<td><?php echo e($v->nickname); ?></td>
				<td><?php echo $v->types == '1' ? 'Complaint' : 'Suggest'; ?></td>
				<td><?php echo e($v->content); ?></td>
				<td>
					<span class="label label-success radius" style="cursor:pointer;" image="<?php echo e($v->image); ?>" onclick="showImage(this);">Preview</span>
				</td>
				<td><?php echo e(date('Y-m-d H:i', $v->addtime)) ?></td>
				<td>
					<span class="label <?php echo $v->is_confirm == '1' ? 'label-success radius' : ''; ?>" style="cursor:pointer;" sending="0" <?php echo $v->is_confirm == '0' ? 'onclick="suggest_confirm(this, '.$v->id.');"' : ''; ?>><?php echo $v->is_confirm == '1' ? 'Confirmed' : 'Not Confirmed'; ?></span>
				</td>
				<td class="f-14">
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
<script>
    function showImage(obj){
        var images = $(obj).attr('image');

        if(images == ''){
            return false;
		}

        var prefix = '<?php echo e(url('public')) ?>/';
		var imgArr = images.split('@');

		var html = '<div style="width:400px;text-align:center;">';
		$(imgArr).each(function(k, v){
		    html += '<a href="'+ prefix + v+'" target="_blank" style="width:92%;display:block;border:1px solid #dddddd;margin:8px 0;"><img src="'+ prefix + v+'" style="max-width:100%;" /></a>';
		});
		html += '</div>';

        layer.open({
            type: 1,
            shade: 0.6,
            shadeClose: true,
            title: false,
            area: ['400px', '80%'],
            content: html,
			success: function(){

			}
        });
    }

    function suggest_confirm(obj, id){
        var pay = '<?php echo e($system->sys_value) ?>';
        var sending = $(obj).attr('sending');

        layer.confirm('To Pay'+pay+'元，Sure to continue?', {
            btn : ['Yes', 'No']
        }, function(){
            if(sending == '0'){
                $(obj).attr('sending', '1');
                $.post('<?php echo e(url('/admin/suggest_confirm')); ?>/'+id, {'_token':'<?php echo e(csrf_token()); ?>'}, function(data){
                    if(data.status == '0'){
                        layer.msg(data.msg, {icon:1, time:2000}, function(){
                            window.location.reload();
                        });
                    }else{
                        layer.msg(data.msg, {icon:2, time:2000});
                    }
                    $(obj).attr('sending', '0');
                });
            }
        }, function(){
            layer.closeAll();
        });
	}

    function destroy(id){
        layer.confirm('Are you sure to delete it?', {
            btn : ['Yes', 'No']
        }, function(){
            $.post('<?php echo e(url('/admin/suggest_del')); ?>/'+id, {'_token':'<?php echo e(csrf_token()); ?>'}, function(data){
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