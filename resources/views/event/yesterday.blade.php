@extends('event.layout')


@section('head')
    <title>昨日领取记录</title>
@endsection


@section('bg', 'class="white"')


@section('content')
    <div id="wrap">
        <div class="yesterday_signed">
            <p><?php echo e(date('Y.m.d', time()-86400)); ?></p>
            <p>领取人数 <?php echo e($total_signed); ?>人</p>
            <p>领取红包 <?php echo $total_coin ? e(number_format($total_coin, 2, '.', '')) : '0.00'; ?>元</p>
        </div>
        <ul class="yesterday">
            <?php foreach($yesterday as $v): ?>
            <li>
                <img src="<?php echo e($v->portrait); ?>" />
                <div class="yesterday_info">
                    <h3>
                        <em><?php echo e($v->nickname); ?></em>
                    </h3>
                    <div>
                        <strong>+ <?php echo e($v->coin); ?>元</strong>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <a href="javascript:void(0);" sending="0" class="more">继续往下</a>
        <div style="height:60px;"></div>
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

                    $.get('<?php echo e(url('get_yesterday')) ?>?page='+page, function (data) {
                        if (data.length) {
                            var html = '';

                            $(data).each(function (k, v) {
                                html += '\
								<li>\
									<img src="'+v.portrait+'" />\
									<div class="yesterday_info">\
										<h3>\
											<em>'+v.nickname+'</em>\
										</h3>\
										<div>\
											<strong>+ '+v.coin+'元</strong>\
										</div>\
									</div>\
								</li>';
                            });

                            $('.yesterday').append(html);

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
