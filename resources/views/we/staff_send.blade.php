@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">消息发送</a>
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="<?php echo e(url('weixin/staff_send')); ?>" method="post">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="160"><i class="require">*</i>提示：</th>
                    <td style="color:#ff0000;"><i class="require">*</i>用户接入客服后才能发送消息</td>
                </tr>
                <tr>
                    <th width="160"><i class="require">*</i>消息类型：</th>
                    <td class="event_type">
                        <label><input type="radio" name="event_type" value="1" />文本</label>
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
                    <th>用户openid：</th>
                    <td>
                        <input type="text" class="lg" name="openid" />
                    </td>
                </tr>
                <tr>
                    <th>选择用户：</th>
                    <td>
                        <input type="button" value="选择用户" onclick="userView();" />
                    </td>
                </tr>
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

        function userView(){
            var url = '<?php echo e(url('weixin/user')); ?>';

            layer.open({
                type: 2,
                title: '选择用户',
                shadeClose: false,
                shade: 0.6,
                area: ['80%', '80%'],
                maxmin: false,
                content: url
            });
        }
    </script>
@endsection
