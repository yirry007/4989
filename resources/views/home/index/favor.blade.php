@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-mypage.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="contaner">

        <ul class="favor-select clearfix">
            <li><a href="<?php echo e(url('/favor')); ?>" class="v">Favorite Goods</a></li>
            <li><a href="<?php echo e(url('/favor_brand')); ?>">Favorites Brand</a></li>
        </ul>

        <ul class="lists">
            <?php foreach($favorData as $v): ?>
            <li>
                <a href="<?php echo e(url('/goods_view/'.$v->goods_id)); ?>" class="goods-info">
                    <p>
                        <img src="<?php echo e(url('public/'.($v->image ? $v->image : $_SYSTEM['defaultportrait']))); ?>" alt="" />
                    </p>
                    <h2>
                        <strong><?php echo e($v->name); ?></strong>
                        <span><?php echo e($v->other_name); ?></span>
                        <em>
                            <i>Brand</i>
                            <?php echo e(str_replace('@', ' ', $v->brand_name)); ?>
                        </em>
                    </h2>
                    <div>
                        <strong>￥ <?php echo e($v->org_price); ?></strong>
                        <br/>
                        <span>￥ <?php echo e($v->price); ?></span>
                        <em>VIP Price</em>
                    </div>
                </a>
                <?php if(!$v->special): ?>
                <a href="javascript:void(0);" class="list-cart" goods_id="<?php echo e($v->goods_id); ?>" limit_num="<?php echo e($v->limit_num); ?>" stock="<?php echo e($v->stock); ?>" onclick="globalAddCart(this);"></a>
                <?php endif; ?>
                <a href="javascript:void(0);" onclick="ajaxFavorDelete(this)" sending="0" favor_id="<?php echo e($v->id); ?>" class="del_btn">Del</a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
@endsection

@section('script')
    <script>
        //屏幕滑动时超出屏幕的视频暂停
        var oWin = $(window);
        $(window).scroll(function(){
            $('video').each(function(k, v){
                if($(v).offset().top < oWin.scrollTop() || $(v).offset().top + $(v).outerHeight() > oWin.scrollTop() + oWin.height()){
                    $(v).trigger('pause');
                }
            });
        });

        function ajaxFavorDelete(obj) {
            var sending = $(obj).attr('sending');

            if(sending === '0') {
                $(obj).attr('sending', '1');

                dialogMsgOkno('Are you sure to delete it?', function () {
                    var favor_id = $(obj).attr('favor_id');

                    $.post('<?php echo e(url('ajax_favor_delete')); ?>', {'id': favor_id,'_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                        if (data.status == 0) {
                            $(obj).parents('.goods-list li').remove();
                            dialogMsg(data.msg);
                        } else if(data.status == 691){
                            window.location.href = '<?php echo e(url('login')); ?>';
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