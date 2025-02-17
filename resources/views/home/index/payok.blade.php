@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-mypage.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <input type="hidden" value="{{ url('index') }}" name="share_link" />
    <div class="payok">
        <p>Thanks for buying.</p>
        <img src="<?php echo e(url('public/home/img/edit/img.jpg')); ?>" alt="" />
        <a href="javascript:void(0);" class="add_we" onclick="guide();">Share to get red packet {{ $_SYSTEM['payok_return_money'] }} 元</a>
        <div>
            <a href="<?php echo e(url('/order'));?>">View Order</a>
            <a href="<?php echo e(url('/index'));?>">Finish</a>
        </div>
    </div>
    <img src="<?php echo e(url('/public/'.$_SYSTEM['official_qr'])); ?>" style="width:18rem;height:18rem;top:50%;margin-top:-9rem;" class="weixin-qr" onclick="weixinClose()" alt="" />
    <div class="black-qr" onclick="weixinClose()"></div>
@endsection

@section('script')
    <script src="<?php echo e(asset('/public/home/js/layer/layer.js')); ?>"></script>
    <script src="<?php echo e(asset('/public/event/js/jquery.cookie.js')); ?>"></script>
    <script>
        //微信二维码
        function weixin() {
            $('.weixin-qr').fadeIn(300);
            $('.black-qr').fadeIn(300);
        }
        function weixinClose() {
            $('.weixin-qr').fadeOut(300);
            $('.black-qr').fadeOut(300);
        }

        function guide(){
            var html = '<div class="direction"><img src="<?php echo e(url('/public/event/img/dir.png')); ?>"/></div><div class="guide"><p>Click upper right to share Moments<br/>Get wechat red packet</p></div>';

            layer.open({
                type: 1,
                title: false,
                closeBtn: false,
                shadeClose: true,
                content: html,
                style: 'background:rgba(0,0,0,0);position:fixed;top:0;left:0;right:0;'
            });
        }

        function pay_share(coin){
            var html = '<div class="pay_share"><h2>Has Got </h2><h1>'+coin+'<em>元</em></h1><p> Thanks for sharing ^^</p><a href="javascript:void(0);">Confirm in wechat balance</a></p>';

            layer.open({
                type: 1,
                title: false,
                closeBtn: false,
                shadeClose: true,
                content: html,
                style: 'width:300px;background:rgba(0,0,0,0);'
            });
        }

        wx.ready(function (){
            $(function(){
                wx.onMenuShareAppMessage({
                    title: '<?php echo e($_SYSTEM["sitename"]); ?>',
                    desc: '<?php echo e($_SYSTEM["siteinfo"]); ?>',
                    link: '<?php echo e($_SYSTEM["transfer_share_link"]); ?>',
                    imgUrl: '<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/public/'.$_SYSTEM["sharelogo"]; ?>',
                    success: function(res){
                        alert('모멘트에 공유해주십시요^^\nPlease Share in Moment');
                    }
                });

                wx.onMenuShareTimeline({
                    title: '<?php echo e($_SYSTEM["sitename"]); ?>',
                    link: '<?php echo e($_SYSTEM["transfer_share_link"]); ?>',
                    imgUrl: '<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/public/'.$_SYSTEM["sharelogo"]; ?>',
                    success: function(res){
                        $.cookie('payok-shared', 1);
                        var order_num = '{{ $orderNum }}';

                        $.get('{{ url('ajax_share_return_money') }}?order_num='+order_num, function(data){
                            if (data.status === 0) {
                                pay_share(data.msg);
                            } else {
                                alert(data.msg);
                            }
                        });
                    }
                });
            });
        });

        $.get('<?php echo e(url('ajax_payok')) ?>', function (data) {});

    </script>
@endsection