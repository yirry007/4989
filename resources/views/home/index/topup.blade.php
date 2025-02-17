@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-mypage.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="contaner">

        <div class="topup_head clearfix">
            <h4>
                <span><?php echo e($memberData->nickname); ?></span>
                <img src="<?php echo e($memberData->portrait); ?>" />
            </h4>
            <p>Balance：￥<?php echo e($memberData->money); ?></p>
        </div>

        <ul class="topup_body">
            <?php foreach($topupData as $v): ?>
            <li onclick="sel_topup(this);" money="<?php echo e($v->money); ?>" topup_id="<?php echo e($v->id); ?>">
                <h3><?php echo e($v->money); ?></h3>
                <p class="cn">Enjoy VIP price after recharging</p>
                <p class="ko">충전후 회원가격으로 구매하실수 있습니다.</p>
            </li>
            <?php endforeach; ?>
            <li class="li-last">
                <p class="more">More discount is opening</p>
            </li>
        </ul>

        <div style="height:64px;"></div>

        <div class="topup_foot clearfix">
            <p>
                <span>Payment：</span>
                <strong>￥<em id="topup_money">-</em></strong>
            </p>
            <a href="javascript:void(0);" onclick="topup(this);" sending="0">Top up</a>
        </div>

    </div>

@endsection

@section('script')
    <script>
        function sel_topup(obj){
            $(obj).siblings('li').removeClass('active');
            $(obj).addClass('active');
            var money = $(obj).attr('money');
            $('#topup_money').html(money);
        }

        function topup(obj){
            var sending = $(obj).attr('sending');

            if(sending === '0'){
                dialogMsgOkno('Confirm to Top up', function () {
                    $(obj).attr('sending', '1');
                    var topup_id = $('.topup_body li.active').attr('topup_id');

                    if(!topup_id){
                        dialogMsg('Please Select Amount');
                        $(obj).attr('sending', '0');
                        return false;
                    }

                    $.post('<?php echo e(url('topup')); ?>', {'topup_id':topup_id, '_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                        if (data.status == 0) {
                            window.location.href = '<?php echo e(url('wx_topup')) ?>?topup_sn='+data.topup_sn;
                        } else if(data.status == 691){
                            window.location.href = '<?php echo e(url('login')); ?>';
                        } else {
                            dialogMsg(data.msg);
                        }

                        $(obj).attr('sending', '0');
                    });
                });
            }
        }
    </script>
@endsection