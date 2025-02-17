@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">设置行业</a>
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="<?php echo e(url('weixin/set_industry')); ?>" method="post">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="160"><i class="require">*</i>行业选择：</th>
                    <td>
                        <select name="industry_1" onchange="setChild(this);">
                            <option value="0">==请选择==</option>
                            <?php foreach($industry as $k=>$v): ?>
                            <option value="<?php echo e($k); ?>" child="<?php echo e(json_encode($v)); ?>"><?php echo e($k); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="industry_2">
                            <option value="0">==请选择==</option>
                        </select>
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

    <script>
        function setChild(obj){
            var _opt = $(obj).find('option:selected').attr('child');
            var opt = JSON.parse(_opt);

            var html = '<option value="0">==请选择==</option>';

            $.each(opt, function(k, v){
                html += '<option value="'+v+'">'+v+'</option>';
            });

            $('select[name=industry_2]').html(html);
        }
    </script>
@endsection
