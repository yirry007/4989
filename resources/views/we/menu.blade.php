@extends('we.layout')


@section('content')
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="<?php echo e(url('weixin')) ?>">首页</a> &raquo; <a href="javascript:void(0);">自定义菜单</a>
    </div>
    <!--面包屑导航 结束-->

    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="<?php echo e(url('weixin/menu_edit')); ?>" method="post">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <table class="add_tab">
                <tbody>


                <?php $hasKey = array_key_exists(0, $menuData) ? true : false; ?>
                <tr class="tr_num_0">
                    <th width="160">菜单一：</th>
                    <td>
                        <input type="text" name="name[0]" value="<?php echo $hasKey && $menuData[0]['name'] ? $menuData[0]['name'] : ''; ?>" placeholder="菜单名称" />

                        <label><input type="radio" name="type[0]" value="click" <?php echo $hasKey && array_key_exists('type', $menuData[0]) && $menuData[0]['type'] == 'click' ? 'checked' : ''; ?> />CLICK</label>
                        <label><input type="radio" name="type[0]" value="view" <?php echo $hasKey && array_key_exists('type', $menuData[0]) && $menuData[0]['type'] == 'view' ? 'checked' : ''; ?> />VIEW</label>

                        <input type="text" name="event[0]" value="<?php if($hasKey && array_key_exists('url', $menuData[0]))echo $menuData[0]['url'];elseif($hasKey && array_key_exists('key', $menuData[0]))echo $menuData[0]['key'];else echo '';  ?>" placeholder="KEY 或者 URL" />

                        <input type="button" value="添加子菜单" index="0" onclick="addChild(this);" />
                    </td>
                </tr>
                <?php if($hasKey): ?>
                <?php foreach($menuData[0]['sub_button'] as $k=>$v): ?>
                <tr class="child_tr_0">
                    <th width="160"></th>
                    <td>
                        <span>子菜单：</span>
                        <input type="text" name="sub_name[0][]" value="<?php echo e($v['name']); ?>" />
                        <label><input type="radio" name="sub_type[0][<?php echo e($k); ?>]" value="click" <?php echo $v['type'] == 'click' ? 'checked' : ''; ?> />CLICK</label>
                        <label><input type="radio" name="sub_type[0][<?php echo e($k); ?>]" value="view" <?php echo $v['type'] == 'view' ? 'checked' : ''; ?> />VIEW</label>
                        <input type="text" name="sub_event[0][]" value="<?php if(array_key_exists('url', $v))echo $v['url'];elseif(array_key_exists('key', $v))echo $v['key'];else echo '';  ?>" placeholder="KEY 或者 URL" />
                        <input type="button" value="删除子菜单" onclick="delChild(this);" />
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>


                <?php $hasKey = array_key_exists(1, $menuData) ? true : false; ?>
                <tr class="tr_num_1">
                    <th width="160">菜单二：</th>
                    <td>
                        <input type="text" name="name[1]" value="<?php echo $hasKey && $menuData[1]['name'] ? $menuData[1]['name'] : ''; ?>" placeholder="菜单名称" />

                        <label><input type="radio" name="type[1]" value="click" <?php echo $hasKey && array_key_exists('type', $menuData[1]) && $menuData[1]['type'] == 'click' ? 'checked' : ''; ?> />CLICK</label>
                        <label><input type="radio" name="type[1]" value="view" <?php echo $hasKey && array_key_exists('type', $menuData[1]) && $menuData[1]['type'] == 'view' ? 'checked' : ''; ?> />VIEW</label>

                        <input type="text" name="event[1]" value="<?php if($hasKey && array_key_exists('url', $menuData[1]))echo $menuData[1]['url'];elseif($hasKey && array_key_exists('key', $menuData[1]))echo $menuData[1]['key'];else echo '';  ?>" placeholder="KEY 或者 URL" />

                        <input type="button" value="添加子菜单" index="1" onclick="addChild(this);" />
                    </td>
                </tr>
                <?php if($hasKey): ?>
                <?php foreach($menuData[1]['sub_button'] as $k=>$v): ?>
                <tr class="child_tr_1">
                    <th width="160"></th>
                    <td>
                        <span>子菜单：</span>
                        <input type="text" name="sub_name[1][]" value="<?php echo e($v['name']); ?>" />
                        <label><input type="radio" name="sub_type[1][<?php echo e($k); ?>]" value="click" <?php echo $v['type'] == 'click' ? 'checked' : ''; ?> />CLICK</label>
                        <label><input type="radio" name="sub_type[1][<?php echo e($k); ?>]" value="view" <?php echo $v['type'] == 'view' ? 'checked' : ''; ?> />VIEW</label>
                        <input type="text" name="sub_event[1][]" value="<?php if(array_key_exists('url', $v))echo $v['url'];elseif(array_key_exists('key', $v))echo $v['key'];else echo '';  ?>" placeholder="KEY 或者 URL" />
                        <input type="button" value="删除子菜单" onclick="delChild(this);" />
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>


                <?php $hasKey = array_key_exists(2, $menuData) ? true : false; ?>
                <tr class="tr_num_2">
                    <th width="160">菜单三：</th>
                    <td>
                        <input type="text" name="name[2]" value="<?php echo $hasKey && $menuData[2]['name'] ? $menuData[2]['name'] : ''; ?>" placeholder="菜单名称" />

                        <label><input type="radio" name="type[2]" value="click" <?php echo $hasKey && array_key_exists('type', $menuData[2]) && $menuData[2]['type'] == 'click' ? 'checked' : ''; ?> />CLICK</label>
                        <label><input type="radio" name="type[2]" value="view" <?php echo $hasKey && array_key_exists('type', $menuData[2]) && $menuData[2]['type'] == 'view' ? 'checked' : ''; ?> />VIEW</label>

                        <input type="text" name="event[2]" value="<?php if($hasKey && array_key_exists('url', $menuData[2]))echo $menuData[2]['url'];elseif($hasKey && array_key_exists('key', $menuData[2]))echo $menuData[2]['key'];else echo '';  ?>" placeholder="KEY 或者 URL" />

                        <input type="button" value="添加子菜单" index="2" onclick="addChild(this);" />
                    </td>
                </tr>
                <?php if($hasKey): ?>
                <?php foreach($menuData[2]['sub_button'] as $k=>$v): ?>
                <tr class="child_tr_2">
                    <th width="160"></th>
                    <td>
                        <span>子菜单：</span>
                        <input type="text" name="sub_name[2][]" value="<?php echo e($v['name']); ?>" />
                        <label><input type="radio" name="sub_type[2][<?php echo e($k); ?>]" value="click" <?php echo $v['type'] == 'click' ? 'checked' : ''; ?> />CLICK</label>
                        <label><input type="radio" name="sub_type[2][<?php echo e($k); ?>]" value="view" <?php echo $v['type'] == 'view' ? 'checked' : ''; ?> />VIEW</label>
                        <input type="text" name="sub_event[2][]" value="<?php if(array_key_exists('url', $v))echo $v['url'];elseif(array_key_exists('key', $v))echo $v['key'];else echo '';  ?>" placeholder="KEY 或者 URL" />
                        <input type="button" value="删除子菜单" onclick="delChild(this);" />
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>


                <tr class="tr_num_3">
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
        function addChild(obj){
            var index = parseInt($(obj).attr('index'));
            var childTr = $('.child_tr_'+index);
            var currentIndex = childTr.length;

            if(currentIndex >= 5){
                return false;
            }

            var html = '\
            <tr class="child_tr_'+index+'">\
                <th width="160"></th>\
                <td>\
                    <span>子菜单：</span>\
                    <input type="text" name="sub_name['+index+'][]" />\
                    <label><input type="radio" name="sub_type['+index+']['+currentIndex+']" value="click">CLICK</label>\
                    <label><input type="radio" name="sub_type['+index+']['+currentIndex+']" value="view">VIEW</label>\
                    <input type="text" name="sub_event['+index+'][]" placeholder="KEY 或者 URL" />\
                    <input type="button" value="删除子菜单" onclick="delChild(this);" />\
                </td>\
            </tr>';

            var next_index = index + 1;

            $('.tr_num_'+next_index).before(html);
        }

        function delChild(obj){
            $(obj).parents('tr').remove();
        }
    </script>
@endsection
