@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-mypage.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="contaner">
        <div class="address-list">
        <?php foreach ($addressData as $v): ?>
        <div id="id<?php echo e($v->id); ?>" class="address-check <?php echo $v->is_default == '1'? 'v': ''; ?> clearfix">
            <a href="<?php echo e(url('/address_edit/'.$v->id.'?from='.app('request')->input('from'))); ?>" class="left"><i class="iconfont icon-qianbipencil82"></i></a>
            <div class="cont" onclick="ajaxSelectAddress(this);" sending="0" data-check="<?php echo e($v->is_default); ?>" address_id="<?php echo e($v->id); ?>">
                <div class="middle">
                <p><em class="name"><?php echo e($v->name); ?></em> <em class="phone"><?php echo e($v->phone); ?></em></p>
                <p><em class="address"><?php echo e($v->province_name); ?><?php echo e($v->province_name == '延吉' ? '' : '省'); ?> <?php echo e($v->address); ?></em></p>
                </div>
            </div>
            <a href="javascript:void(0);" class="right" onclick="ajaxDeleteAddress(this);" sending="0"><i class="iconfont icon-icon"></i></a>
        </div>
        <?php endforeach; ?>
        </div>
        <div style="height: 1rem;"></div>
        <div class="address-add">
            <p>Add Address</p>
            <ul>
                <li><span>Receiver :</span><input type="text" placeholder="Receiver" name="name" /></li>
                <li><span>Phone :</span><input type="text" placeholder="Phone" name="phone" /></li>
                <li><span>Province :</span><select name="province">
                        <option value="null">Please Select Province</option>
                        <?php foreach($expressData as $v): ?>
                        <option value="<?php echo e($v->province.'@'.$v->province_name); ?>"><?php echo e($v->province_name); ?></option>
                        <?php endforeach; ?>
                    </select></li>
                <li><span>City/Area :</span><input type="text" placeholder="City/Area" name="area" /></li>
                <li><span>Address :</span><input type="text" placeholder="Address" name="address" /></li>
            </ul>
            <div class="checkbox check" onclick="check(this);" data-check="1"><u></u><div>Set Default</div></div>
            <a href="javascript:void(0);" onclick="ajaxAddAddress(this);" sending="0">Add Address</a>
        </div>
    </div>
@endsection

@section('script')
    <script>
        //是否默认
        function check(obj) {
            if ($(obj).attr('data-check') == 0) {
                $(obj).addClass('check');
                $(obj).attr('data-check',1);
            } else {
                $(obj).removeClass('check');
                $(obj).attr('data-check',0);
            }
        }
        //选择默认地址
        function ajaxSelectAddress(obj) {
            var sending = $(obj).attr('sending');

            if(sending === '0') {
                $(obj).attr('sending', '1');

                var address_id = $(obj).attr('address_id');

                $.post('<?php echo e(url('ajax_select_address')); ?>', {'id': address_id,'_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                    if (data.status == 0) {
                        $('.address-check').removeClass('v');
                        $(obj).parent('.address-check').addClass('v');

                        var from = '<?php echo e(app('request')->input('from')); ?>';
                        if (from == 'buy') {
                            window.location.href = '<?php echo e(url('buy')) ?>';
                        }
                    }else if(data.status == 691){
                        window.location.href = '<?php echo e(url('login')); ?>';
                    } else {
                        dialogMsg(data.msg);
                    }
                });
                $(obj).attr('sending', '0');
            }
        }
        //添加新地址
        function ajaxAddAddress(obj) {
            var sending = $(obj).attr('sending');

            if(sending === '0') {
                $(obj).attr('sending', '1');

                var name = $('input[name=name]').val();
                var phone = $('input[name=phone]').val();
                var province = $('select[name=province]').val();
                var area = $('input[name=area]').val();
                var address = $('input[name=address]').val();
                var is_default = $('.address-add .checkbox').attr('data-check');

                if (!name) {
                    dialogMsg('Please input receiver');
                    $(obj).attr('sending', '0');
                    return false;
                }
                if (!phone) {
                    dialogMsg('Please input phone number');
                    $(obj).attr('sending', '0');
                    return false;
                }
                if (phone.length != 11) {
                    dialogMsg('Please input 11 digit mobile phone number');
                    $(obj).attr('sending', '0');
                    return false;
                }
                if (province == 'null') {
                    dialogMsg('Please select province');
                    $(obj).attr('sending', '0');
                    return false;
                }
                if (!area) {
                    dialogMsg('Please input city/area');
                    $(obj).attr('sending', '0');
                    return false;
                }
                if (!address) {
                    dialogMsg('Please input address');
                    $(obj).attr('sending', '0');
                    return false;
                }
                address = area+' - '+address;

                $.post('<?php echo e(url('ajax_add_address')); ?>', {'name': name,'phone': phone,'province': province,'address': address,'is_default': is_default,'_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                    if (data.status == '0') {
                        var checked = is_default == '1' ? 'v' : '';
                        if (checked == 'v') {
                            $('.address-check').removeClass('v');
                            $('.address-check').find('.cont').attr('data-check', 0);
                        }

                        var html = '';
                        html += '\
                        <div class="address-check ' + checked + ' clearfix">\
                            <a href="<?php echo e(url('/address_edit')); ?>/'+data.id+'?from=<?php echo e(app('request')->input('from')); ?>" class="left"><i class="iconfont icon-qianbipencil82"></i></a>\
                            <div class="cont" onclick="ajaxSelectAddress(this);" sending="0" data-check="' + is_default + '" address_id="' + data.id + '">\
                                <div class="middle">\
                                    <p><em class="name">' + name + '</em> <em class="phone">' + phone + '</em></p>\
                                    <p><em class="address">' + address + '</em></p>\
                                </div>\
                            </div>\
                            <a href="javascript:void(0);" class="right" onclick="ajaxDeleteAddress(this);" sending="0"><i class="iconfont icon-icon"></i></a>\
                        </div>';
                        $('.address-list').append(html);

                        $('input[name=name]').val('');
                        $('input[name=phone]').val('');
                        $('input[name=address]').val('');

                        dialogMsg(data.msg);
                    } else if(data.status == 691){
                        window.location.href = '<?php echo e(url('login')); ?>';
                    } else {
                        dialogMsg(data.msg);
                    }
                });

                $(obj).attr('sending', '0');
            }
        }
        //删除地址
        function ajaxDeleteAddress(obj) {
            var sending = $(obj).attr('sending');

            if(sending === '0') {
                $(obj).attr('sending', '1');

                dialogMsgOkno('Are you sure to delete it?', function () {
                    var address_id = $(obj).prev('.cont').attr('address_id');

                    $.post('<?php echo e(url('ajax_delete_address')); ?>', {'id': address_id,'_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                        if (data.status == 0) {
                            if(data.id){
                                $(obj).parents('.address-check').remove();
                                $('#id'+ data.id).addClass('v');
                            }else if(data.status == 691){
                                window.location.href = '<?php echo e(url('login')); ?>';
                            }else {
                                $(obj).parents('.address-check').remove();
                            }
                        } else {
                            dialogMsg(data.msg);
                        }
                    });
                });

                $(obj).attr('sending', '0');
            }
        }
    </script>
@endsection