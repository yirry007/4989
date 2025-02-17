@extends('home.layout')

@section('title', '-Transaction')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-mypage.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="contaner">

        <?php if(!empty($cashFlow)): ?>
        <ul class="account">
            <?php foreach($cashFlow as $v): ?>
            <li class="clearfix">
                <div class="account_info">
                    <h3><?php
                        switch($v->types){
                            case 1:echo 'Shopping';break;
                            case 2:echo 'Top up';break;
                            case 3:echo 'Suggest';break;
                            case 4:echo 'Bonus';break;
                        }
                    ?></h3>
                    <p><?php echo e(date('Y-m-d H:i:s', $v->addtime)); ?></p>
                </div>
                <div class="account_money <?php echo $v->types == '1' ? '' : 'v'; ?>"><?php echo $v->types == '1' ? '-' : '+'; ?> <?php echo e($v->money); ?></div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <div class="account_no">
            <p>No Data...</p>
        </div>
        <?php endif; ?>

        <div style="height:50px;"></div>

        <a href="javascript:void(0);" sending="0" class="more-bt">Load More</a>

        <div class="account_footer">
            <p>Balance <?php echo e($memberData->money); ?></p>
            <a href="<?php echo e(url('topup')); ?>">Top up</a>
        </div>
    </div>
@endsection

@section('script')
    <script>
        //加载更多
        var page = 1;
        $(window).scroll(function(){
            var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
            if($(document).height() <= totalheight){
                var sending = $('.more-bt').attr('sending');
                if (sending === '0') {
                    $('.more-bt').attr('sending', 1);
                    $('.more-bt').html('Loading');

                    $.get('<?php echo e(url('get_cash_flow')) ?>?page='+page, function (data) {
                        if (data.length) {
                            var html = '';

                            $(data).each(function (k, v) {

                                var types = '';
                                if(v.types == '1'){
                                    types = 'Shopping';
                                }else if(v.types == '2'){
                                    types = 'Top up';
                                }else if(v.types == '3'){
                                    types = 'Suggest';
                                }else{
                                    types = 'Bonus';
                                }
                                var active = v.types == '1' ? '' : 'v';
                                var symbol = v.types == '1' ? '-' : '+';

                                html += '\
                            <li class="clearfix">\
                                <div class="account_info">\
                                    <h3>'+types+'</h3>\
                                    <p>'+timestampToTime(v.addtime)+'</p>\
                                </div>\
                                <div class="account_money '+active+'">'+symbol+' '+v.money+'</div>\
                            </li>';
                            });

                            $('.account').append(html);

                            page++;

                            $('.more-bt').html('Load More');
                            $('.more-bt').attr('sending', '0');
                        } else {
                            $('.more-bt').html('No Data');
                            $('.more-bt').removeAttr('sending');
                        }
                    });
                }
            }
        });

        function timestampToTime(timestamp) {
            var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
            var Y = date.getFullYear();
            var M = fillZero(date.getMonth()+1);
            var D = fillZero(date.getDate());
            var h = fillZero(date.getHours());
            var i = fillZero(date.getMinutes());
            var s = fillZero(date.getSeconds());
            return Y + '-' + M + '-' + D + ' ' + h + ':' + i + ':' + s;
        }

        function fillZero(num){
            return num < 10 ? '0'+num : num;
        }
    </script>
@endsection