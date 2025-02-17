@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">菜单事件</a>
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="<?php echo e(url('weixin/menu_event')); ?>" method="post">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <table class="add_tab">
                <tbody>
                <?php foreach($eventData as $k=>$v): ?>
                <tr>
                    <th width="160"><?php echo e($v['menu']); ?>：</th>
                    <td>
                        <p><?php echo e($v['event']); ?></p>
                        <input type="hidden" name="menu[<?php echo e($k); ?>]" value="<?php echo e($v['menu']); ?>" />
                        <input type="hidden" name="event[<?php echo e($k); ?>]" value="<?php echo e($v['event']); ?>" />
                        <input type="text" class="lg" name="event_value[<?php echo e($k); ?>]" value="<?php echo e($v['event_value']); ?>" /><input type="button" value="选择素材" onclick="mediaView(this);" /><br/>
                        <label><input type="radio" name="event_type[<?php echo e($k); ?>]" value="1" <?php echo $v['event_type'] == 1 ? 'checked' : ''; ?> />文本</label>
                        <label><input type="radio" name="event_type[<?php echo e($k); ?>]" value="2" <?php echo $v['event_type'] == 2 ? 'checked' : ''; ?> />图片</label>
                        <label><input type="radio" name="event_type[<?php echo e($k); ?>]" value="3" <?php echo $v['event_type'] == 3 ? 'checked' : ''; ?> />声音</label>
                        <label><input type="radio" name="event_type[<?php echo e($k); ?>]" value="4" <?php echo $v['event_type'] == 4 ? 'checked' : ''; ?> />视频</label>
                        <label><input type="radio" name="event_type[<?php echo e($k); ?>]" value="5" <?php echo $v['event_type'] == 5 ? 'checked' : ''; ?> />图文</label>
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <th></th>
                    <td>
                        <input type="submit" value="提交">
                        <input type="button" class="back" onclick="history.go(-1)" value="返回">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>

    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/we/layer/layer.js')); ?>"></script>
    <script>
        function mediaView(obj){
            var radio = $(obj).parent('td').find('input[type=radio]:checked');

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
    </script>
@endsection
