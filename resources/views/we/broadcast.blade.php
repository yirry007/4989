@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">群发消息</a>
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form>
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="160"><i class="require">*</i>发送对象：</th>
                    <td class="event_type">
                        <label><input type="radio" name="orientation" value="1" checked />全部</label>
                        <label><input type="radio" name="orientation" value="2" />用户组</label>
                        <label><input type="radio" name="orientation" value="3" />指定用户</label>
                    </td>
                </tr>
                <tr>
                    <th width="160"><i class="require">*</i>消息类型：</th>
                    <td class="event_type">
                        <label><input type="radio" name="event_type" value="1" checked />文本</label>
                        <label><input type="radio" name="event_type" value="2" />图片</label>
                        <label><input type="radio" name="event_type" value="3" />声音</label>
                        <label><input type="radio" name="event_type" value="4" />视频</label>
                        <label><input type="radio" name="event_type" value="5" />图文</label>
                    </td>
                </tr>
                <tr>
                    <th>素材ID：</th>
                    <td>
                        <textarea name="event_value" placeholder="消息类型为文本则直接输入回复文字，否则输入素材ID"></textarea>
                    </td>
                </tr>
                <tr>
                    <th>选择素材：</th>
                    <td>
                        <input type="button" value="选择素材" onclick="mediaView();" />
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <input type="button" value="发送" onclick="broadcast();" />
                    </td>
                </tr>
                </tbody>
            </table>

            <table class="search_tab">
                <tr>
                    <th width="120">选择用户组:</th>
                    <td>
                        <select name="group_id">
                            <?php foreach($groupListAll as $v): ?>
                            <option value="<?php echo e($v['id']); ?>"><?php echo e($v['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="button" value="移动用户组" onclick="moveUsers();"></td>
                </tr>
            </table>

            <table class="list_tab">
                <tr>
                    <th class="tc" width="5%"><input type="checkbox" name=""></th>
                    <th class="tc">昵称</th>
                    <th class="tc">性别</th>
                    <th class="tc">所在地</th>
                    <th class="tc">头像</th>
                    <th class="tc">关注时间</th>
                    <th class="tc">备注</th>
                    <th class="tc" width="8%;">操作</th>
                </tr>
                <?php foreach($userData as $v): ?>
                <tr>
                    <td class="tc"><input type="checkbox" class="_openid" value="<?php echo e($v['openid']); ?>"></td>
                    <td class="tc"><?php echo e($v['nickname']); ?></td>
                    <td class="tc"><?php echo $v['sex'] == 1 ? '男' : '女'; ?></td>
                    <td class="tc"><?php echo e($v['country'].' '.$v['province'].' '.$v['city']); ?></td>
                    <td class="tc"><img src="<?php echo e($v['headimgurl']); ?>" width="80" height="80" /></td>
                    <td class="tc"><?php echo e(date('Y-m-d H:i', $v['subscribe_time'])); ?></td>
                    <td class="tc"><?php echo $v['remark'] ? e($v['remark']) : '无备注'; ?></td>
                    <td class="tc"><a href="<?php echo e(url('weixin/user_edit_view/'.$v['openid'])); ?>">编辑</a></td>
                </tr>
                <?php endforeach; ?>
            </table>

            <div class="page_nav"><?php echo $pageShow; ?></div>
        </form>
    </div>

    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/public/we/layer/layer.js')); ?>"></script>
    <script>
        function mediaView(){
            var radio = $('.event_type').find('input[name=event_type]:checked');

            if(!radio.length){
                return false;
            }

            var option = $(radio).val();

            if(option == '1'){
                return false;
            }

            switch(option){
                case '2':
                    var url = '<?php echo e(url('weixin/image')); ?>';
                    break;
                case '3':
                    var url = '<?php echo e(url('weixin/voice')); ?>';
                    break;
                case '4':
                    var url = '<?php echo e(url('weixin/video')); ?>';
                    break;
                case '5':
                    var url = '<?php echo e(url('weixin/news')); ?>';
                    break;
            }

            layer.open({
                type: 2,
                title: '查找素材',
                shadeClose: false,
                shade: 0.6,
                area: ['80%', '80%'],
                maxmin: false,
                content: url
            });
        }

        function broadcast(){
            if(confirm('确定要发送吗？')){
                var orientation = $('input[name=orientation]:checked').val();
                var event_type = $('input[name=event_type]:checked').val();
                var event_value = $('textarea[name=event_value]').val();
                var group_id = $('select[name=group_id]').val();
                var _openid = $('._openid:checked');
                var openid = '';

                if(event_value == ''){
                    alert('请输入内容');
                    return false;
                }

                $(_openid).each(function(k, v){
                    if(k != 0){
                        openid += '@';
                    }
                    openid += $(v).val();
                });

                $.post('<?php echo e(url('weixin/broadcast')); ?>', {'orientation':orientation, 'event_type':event_type, 'event_value':event_value, 'group_id':group_id, 'openid':openid, '_token':'<?php echo e(csrf_token()); ?>'}, function(data){
                    if(data.status == 0){
                        alert(data.msg);
                        window.location.reload();
                    }else{
                        alert(data.msg);
                    }
                });
            }
        }
    </script>
@endsection
