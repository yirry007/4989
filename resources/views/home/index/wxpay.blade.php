@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-buy.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="contaner">
        <div class="view-tit"><p>Wechat Pay</p>Confirm Amount</div>
        <div class="view-con">
            <div class="clearfix">
                <p>Amount</p>
                <p>￥<?php echo e($payData->all_price + $payData->express_price); ?></p>
            </div>
            <div class="clearfix">
                <p>Serial</p>
                <p><?php echo e($payData->order_num); ?></p>
            </div>
            <div class="clearfix">
                <p>Payee</p>
                <p>Wechat Pay</p>
            </div>
        </div>
        <div class="view-bt"><a href="javascript:void(0);" onclick="callpay();">Payment</a><a href="<?php echo e(url('/buy')); ?>">Cancel</a></div>
    </div>
@endsection


@section('footer')
    @parent
@endsection


@section('script')
    <script>
        //调用微信JS api 支付
        function jsApiCall() {
            var _jsApiParam = '<?php echo $jsApiParameters; ?>';
            var jsApiParam = JSON.parse(_jsApiParam);

            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                jsApiParam,//这个位置必须为 json 格式（即对象，例：{'a':'1', 'b':'2'}），不能为字符串
                function(res){
                    WeixinJSBridge.log(res.err_msg);
                    //alert(res.err_code+res.err_desc+res.err_msg);

                    var result = res.err_msg.split(':')[1].trim();
                    if(result === 'ok'){
                        window.location.href = '<?php echo e(url('payok')) ?>';
                    }else{
                        dialogMsg('Failed');
                    }
                }
            );
        }

        function callpay() {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
    </script>
@endsection