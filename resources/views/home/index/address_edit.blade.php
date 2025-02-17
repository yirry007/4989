@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-mypage.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="contaner">
        <div class="address-add">
            <p>Edit Address</p>
            <ul address_id="<?php echo e($addressData->id); ?>">
                <li><span>Receiver :</span><input type="text" name="name" value="<?php echo e($addressData->name); ?>" /></li>
                <li><span>Phone :</span><input type="text" name="phone" value="<?php echo e($addressData->phone); ?>" /></li>
                <li><span>Province :</span><select name="province">
                        <option value="<?php echo e($addressData->province.'@'.$addressData->province_name); ?>"><?php echo e($addressData->province_name); ?></option>
                        <?php foreach($expressData as $v): ?>
                        <option value="<?php echo e($v->province.'@'.$v->province_name); ?>"><?php echo e($v->province_name); ?></option>
                        <?php endforeach; ?>
                    </select></li>
                <?php
                $date = explode(' - ',$addressData->address);
                $area = '';
                $address = '';
                if(count($date) == 1){
                    $address = $date[0];
                }else{
                    $area = $date[0];
                    $address = $date[1];
                }
                ?>
                <li><span>City/Area :</span><input type="text" name="area" value="<?php echo e($area); ?>" /></li>
                <li><span>Address :</span><input type="text" name="address" value="<?php echo e($address); ?>" /></li>
            </ul>
            <a href="javascript: void(0);" onclick="ajaxEditAddress(this);" sending="0">Edit Address</a>
        </div>
    </div>
@endsection

@section('script')
    <script>
        //修改地址
        function ajaxEditAddress(obj) {
            var sending = $(obj).attr('sending');

            if(sending === '0') {
                $(obj).attr('sending', '1');

                var address_id = $('.address-add').find('ul').attr('address_id');
                var name = $('input[name=name]').val();
                var phone = $('input[name=phone]').val();
                var province = $('select[name=province]').val();
                var area = $('input[name=area]').val();
                var address = $('input[name=address]').val();

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
                if (!area) {
                    dialogMsg('Please input province');
                    $(obj).attr('sending', '0');
                    return false;
                }
                if (!address) {
                    dialogMsg('Please input address');
                    $(obj).attr('sending', '0');
                    return false;
                }
                address = area+' - '+address;

                $.post('<?php echo e(url('ajax_edit_address')); ?>', {'id': address_id,'name': name,'phone': phone,'province': province,'address': address,'_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                    if (data.status == '0') {
                        var from = '<?php echo e(app('request')->input('from')); ?>';
                        window.location.href = '<?php echo e(url('address?from=')) ?>' + from;
                    } else if(data.status == 691){
                        window.location.href = '<?php echo e(url('login')); ?>';
                    } else {
                        dialogMsg(data.msg);
                    }
                });

                $(obj).attr('sending', '0');
            }
        }
    </script>
@endsection