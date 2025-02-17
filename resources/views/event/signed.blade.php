@extends('event.layout')


@section('head')
    <title>签到红包</title>
@endsection


@section('bg', 'class="white"')


@section('content')
    <div id="wrap">
        <div class="list_tab">
            <a href="javascript:void(0);" class="v">签到红包</a>
        </div>
        <ul class="signed">
            <?php foreach($signed as $v): ?>
            <li>
                <div class="signed_info">
                    <p><?php echo e(date('Y-m-d H:i', $v->sign_time)); ?></p>
                    <div>
                        <strong>+ <?php echo e($v->coin); ?>元</strong>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <a href="javascript:void(0);" sending="0" class="more">继续往下</a>
        <div style="height:60px;"></div>
        <div class="income_btn">
            <p>
                <span>未提现金额</span>
                <strong><?php echo e($member->coin); ?>元</strong>
            </p>
            <a href="https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzU5OTM2MDQ5NA==&scene=126&bizpsid=0#wechat_redirect">立即提现</a>
        </div>
    </div>
@endsection


@section('script')
    <script>
        var page = 0;
        var url = '<?php echo e(url()); ?>';
        $(window).scroll(function(){
            var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
            if($(document).height() <= totalheight){
                var sending = $('.more').attr('sending');
                if (sending === '0') {
                    $('.more').attr('sending', 1);
                    $('.more').html('正在加载...');

                    $.get('<?php echo e(url('get_signed')) ?>?page='+page, function (data) {
                        if (data.length) {
                            var html = '';

                            $(data).each(function (k, v) {
                                html += '\
								<li>\
									<div class="signed_info">\
										<p>'+formatTime(v.sign_time)+'</p>\
										<div>\
											<strong>+ '+v.coin+'元</strong>\
										</div>\
									</div>\
								</li>';
                            });

                            $('.signed').append(html);

                            page++;

                            $('.more').html('继续往下');
                            $('.more').attr('sending', 0);
                        } else {
                            $('.more').html('已经到底了');
                            $('.more').removeAttr('sending');
                        }
                    });
                }
            }
        });
    </script>
@endsection

