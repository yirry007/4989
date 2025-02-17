@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="#">首页</a> &raquo; <a href="#">商品管理</a> &raquo; 添加商品
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="#" method="post">
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="160"><i class="require">*</i>分类：</th>
                    <td>
                        <select name="">
                            <option value="">==请选择==</option>
                            <option value="19">精品界面</option>
                            <option value="20">推荐界面</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>标题：</th>
                    <td>
                        <input type="text" class="lg" name="">
                        <p>标题可以写30个字</p>
                    </td>
                </tr>
                <tr>
                    <th>作者：</th>
                    <td>
                        <input type="text" name="">
                        <span><i class="fa fa-exclamation-circle yellow"></i>这里是默认长度</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>价格：</th>
                    <td>
                        <input type="text" class="sm" name="">元
                        <span><i class="fa fa-exclamation-circle yellow"></i>这里是短文本长度</span>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i>缩略图：</th>
                    <td><input type="file" name=""></td>
                </tr>
                <tr>
                    <th>单选框：</th>
                    <td>
                        <label><input type="radio" name="">单选按钮一</label>
                        <label><input type="radio" name="">单选按钮二</label>
                    </td>
                </tr>
                <tr>
                    <th>复选框：</th>
                    <td>
                        <label><input type="checkbox" name="">复选框一</label>
                        <label><input type="checkbox" name="">复选框二</label>
                    </td>
                </tr>
                <tr>
                    <th>描述：</th>
                    <td>
                        <textarea name="discription"></textarea>
                    </td>
                </tr>
                <tr>
                    <th>详细内容：</th>
                    <td>
                        <textarea class="lg" name="content"></textarea>
                        <p>标题可以写30个字</p>
                    </td>
                </tr>
                <tr>
                    <th>图文消息：</th>
                    <td>
                        <textarea id="news" name="news"></textarea>
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
    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/we/ueditor/ueditor.config.js')); ?>"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/we/ueditor/ueditor.all.min.js')); ?>"> </script>
    <script type="text/javascript" charset="utf-8" src="<?php echo e(asset('/we/ueditor/lang/zh-cn/zh-cn.js')); ?>"></script>
    <script>
        UE.getEditor('news', {
            "initialFrameWidth" : "100%",   // 宽
            "initialFrameHeight" : 400,      // 高
            "maximumWords" : 65535            // 最大可以输入的字符数量
        });
    </script>
@endsection
