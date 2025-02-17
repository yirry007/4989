@extends('admin.layout')
@section('content')
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> Member <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="Refresh" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="text-c">
        <?php $money = 1; if(app('request')->input('money') == 1) $money = 2;?>
		<input class="btn btn-default radius" type="button" onclick="window.location.href = '<?php echo url('/admin/members?money='.$money); ?>';" value="Sort By Balance">
        <?php $amount = 1; if(app('request')->input('amount') == 1) $amount = 2;?>
		<input class="btn btn-default radius" type="button" onclick="window.location.href = '<?php echo url('/admin/members?amount='.$amount); ?>';" value="Sort By Expended">
			<div style="height: 10px;"></div>
			<form action="<?php echo e(url('admin/members')); ?>" method="get">
				Group：
				<select class="input-text" style="width: 100px; vertical-align: -2px;" name="group_id">
					<option value="-1">Please Select</option>
					<option value="0">No Group</option>
                    <?php foreach($groupData as $v): ?>
					<option value="<?php echo e($v->id); ?>" <?php echo app('request')->input('group_id') == $v->id ? 'selected' : ''; ?>><?php echo e($v->name); ?></option>
                    <?php endforeach; ?>
				</select>
				<input type="text" name="name" value="<?php echo e(app('request')->input('name')) ?>" id="" placeholder=" Nickname" style="width:300px" class="input-text">
				<button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> Search</button>
			</form>
	</div>
	<div class="cl pd-5 bg-1 bk-gray"> <span class="r">Total：<strong><?php echo e($dataCount); ?></strong> Rows</span> </div>
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="13">Members</th>
			</tr>
			<tr class="text-c">
				<th>ID</th>
				<th>Group</th>
				<th>Wechat Openid</th>
				<th>Portrait</th>
				<th>Nickname</th>
				<th>Balance</th>
				<th>Expended</th>
				<th>Level</th>
				<th>Sourced By</th>
				<th>Is Super</th>
				<th>Created Time</th>
				<th>Is Subscribed</th>
				<th>OPT</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($memberData as $v): ?>
			<tr class="text-c">
				<td><?php echo e($v->id); ?></td>
				<td>
					<select onchange="set_group(this, '<?php echo e($v->id) ?>');">
						<option value="0">No Group</option>
						<?php foreach($groupData as $v1): ?>
						<option value="<?php echo e($v1->id); ?>" <?php echo $v->group_id == $v1->id ? 'selected' :  ''; ?>><?php echo e($v1->name); ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td><?php echo e($v->openid); ?></td>
				<td><img src="<?php echo e($v->portrait); ?>" width="60" alt="" /></td>
				<td><?php echo e($v->nickname); ?></td>
				<td><?php echo e($v->money); ?></td>
				<td><?php echo e($v->amount); ?></td>
				<td>
					<span class="label <?php echo $v->is_vip ? 'label-success' : ''; ?> radius">
					<?php
                        switch($v->is_vip){
                            case '0':echo 'Normal';break;
                            case '1':echo 'Super';break;
                        }
                        ?>
					</span>
				</td>
				<td><?php echo e($v->partner_name); ?></td>
				<td>
					<span class="label <?php echo $v->is_use ? 'label-success' : ''; ?> radius">
					<?php
                        switch($v->is_use){
                            case '0':echo 'Disable';break;
                            case '1':echo 'Enable';break;
                        }
                        ?>
					</span>
				</td>
				<td><?php echo e(date('Y-m-d H:i', $v->addtime)) ?></td>
				<td>
					<span class="label <?php echo $v->is_scr ? 'label-success' : ''; ?> radius">
					<?php
                        switch($v->is_scr){
                            case '0':echo 'Not Subscribed';break;
                            case '1':echo 'Subscribed';break;
                        }
                        ?>
					</span>
				</td>
				<td>
					<a title="Bonus" href="javascript:void(0);" sending="0" onclick="bonus(this, '<?php echo e($v->id); ?>');" style="text-decoration:none"><i class="Hui-iconfont">&#xe6b7;</i></a>
					<a title="Bonus List" href="javascript:void(0);" onclick="bonusView('<?php echo e($v->id); ?>');" style="text-decoration:none"><i class="Hui-iconfont">&#xe6b6;</i></a>
					<a title="Edit" href="<?php echo e(url('/admin/members/'.e($v->id).'/edit?page='.app('request')->input('page'))); ?>" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>
				</td>
			</tr>
			<?php endforeach; ?>
			<tr class="text-c">
				<td class="page_td" colspan="13">
					<div class="page_list"><?php echo $pageShow; ?></div>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/My97DatePicker/WdatePicker.js')) ?>"></script>
<script>
    function bonus(obj, id){
        var html = '<p style="">Please input amount</p><input type="number" style="border:1px solid #ddd;font-size:14px;padding:4px;color:#333;" name="bonus" value="" />';

        layer.confirm(html, {
            btn : ['Yes', 'No']
        }, function(){
            var sending = $(obj).attr('sending');

            if(sending == '0'){
                $(obj).attr('sending', '1');
                var money = $('input[name=bonus]').val();

                if(money == '0' || money == ''){
                    layer.msg('Please input amount', {icon:2, time:2000});
                    $(obj).attr('sending', '0');
                    return false;
                }

                $.post('<?php echo e(url('/admin/bonus')); ?>', {'id':id, 'money':money,'_token':'<?php echo e(csrf_token()); ?>'}, function(data){
                    if(data.status == '0'){
                        layer.msg(data.msg, {icon:1, time:2000}, function(){
                            window.location.reload();
                        });
                    }else{
                        layer.msg(data.msg, {icon:2, time:2000});
                        $(obj).attr('sending', '0');
                    }
                });
			}
        }, function(){
            layer.closeAll();
        });
    }

    function bonusView(id)
	{
        layer.open({
            type: 2,
            title: 'Bonus List',
            shadeClose: false,
            shade: 0.6,
            area: ['30%', '90%'],
            maxmin: true,
            content: '<?php echo e(url('/admin/bonus_view')); ?>/'+id //iframe的url
        });
	}

    function set_group(obj, id){
        var group_id = $(obj).val();
        $.post('<?php echo e(url('admin/set_group')); ?>', {'group_id':group_id, 'id':id, '_token':'<?php echo e(csrf_token()); ?>'}, function(data){
            layer.msg(data.msg);
		});
	}
</script>
@endsection