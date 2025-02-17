@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-goods.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <input type="hidden" value="<?php echo e(url('goods?cat='.app('request')->input('cat').'&keyword='.app('request')->input('keyword'))); ?>" name="share_link" />
    <div class="contaner">

        <ul class="select clearfix">
            <li>
                <a href="javascript:void(0);" onclick="soltSale(this);" data-sale="<?php echo app('request')->input('sale'); ?>" class="<?php echo app('request')->input('sale'); ?>">Sale Volume</a>
            </li>
            <li>
                <a href="javascript:void(0);" onclick="soltPrice(this);" data-price="<?php echo app('request')->input('price'); ?>" class="<?php echo app('request')->input('price'); ?>">Price<i class="iconfont icon-shangxia"></i></a>
            </li>
        </ul>

        <?php if($adDataList): ?>
        <div class="ad_area_list"><a href="<?php echo e($adDataList->link); ?>"><img src="<?php echo e(url('public/'.$adDataList->image)); ?>" alt="" /></a></div>
        <?php endif; ?>

        <script>
            function soltSale(obj) {
                var attr = $(obj).attr('data-sale');
                var keyword = $('input[name=keyword]').val();

                switch (attr) {
                    case '':
                        window.location.href = '<?php echo e(url('/goods')) ?>?keyword=<?php echo app('request')->input('keyword'); ?>&cat=<?php echo app('request')->input('cat'); ?>&sale=v';
                        break;
                    case 'v':
                        window.location.href = '<?php echo e(url('/goods')) ?>?keyword=<?php echo app('request')->input('keyword'); ?>&cat=<?php echo app('request')->input('cat'); ?>&sale=';
                        break;
                }
            }
            function soltPrice(obj) {
                var attr = $(obj).attr('data-price');
                var keyword = $('input[name=keyword]').val();

                switch (attr) {
                    case '':
                        window.location.href = '<?php echo e(url('/goods')) ?>?keyword=<?php echo app('request')->input('keyword'); ?>&cat=<?php echo app('request')->input('cat'); ?>&price=v';
                        break;
                    case 'v':
                        window.location.href = '<?php echo e(url('/goods')) ?>?keyword=<?php echo app('request')->input('keyword'); ?>&cat=<?php echo app('request')->input('cat'); ?>&price=b';
                        break;
                    case 'b':
                        window.location.href = '<?php echo e(url('/goods')) ?>?keyword=<?php echo app('request')->input('keyword'); ?>&cat=<?php echo app('request')->input('cat'); ?>&price= ';
                        break;
                }
            }
        </script>
        <ul class="lists">
            <?php foreach($goodsData as $v): ?>
            <li>
                <a href="<?php echo e(url('/goods_view/'.$v->id)); ?>" class="goods-info">
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
                <a href="javascript:void(0);" class="list-cart" goods_id="<?php echo e($v->id); ?>" limit_num="<?php echo e($v->limit_num); ?>" stock="<?php echo e($v->stock); ?>" onclick="globalAddCart(this);"></a>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
        <a href="javascript:void(0);" sending="0" class="more-bt">Load More</a>
    </div>

    <a href="{{ url('nav') }}" class="to-nav">Goods<br/>Search</a>

    @include('home.index.footer')
@endsection

@section('script')
    <script>
        $(document).keyup(function(event){
            if(event.keyCode == 13){
                searchGoods();
            }
        });

        //商品搜索
        function searchGoods(){
            var keyword = $('input[name=keyword]').val();
            keyword = keyword.trim();

            window.location.href = '<?php echo e(url('/goods')) ?>?keyword='+keyword;
        }

        //加载更多
        var oWin = $(window);
        var page = 0;
        var url = '<?php echo e(url()); ?>';
        $(window).scroll(function(){
            //屏幕滑动时超出屏幕的视频暂停
            $('video').each(function(k, v){
                if($(v).offset().top < oWin.scrollTop() || $(v).offset().top + $(v).outerHeight() > oWin.scrollTop() + oWin.height()){
                    $(v).trigger('pause');
                }
            });

            var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
            if($(document).height() <= totalheight){
                var sending = $('.more-bt').attr('sending');
                if (sending === '0') {
                    $('.more-bt').attr('sending', 1);
                    $('.more-bt').html('Loading...');

                    var keyword = '<?php echo e(app('request')->input('keyword')); ?>';
                    var cat = '<?php echo e(app('request')->input('cat')); ?>';
                    var sale = '<?php echo e(app('request')->input('sale')); ?>';
                    var price = '<?php echo e(app('request')->input('price')); ?>';

                    $.get('<?php echo e(url('ajax_get_goods')) ?>?page=' + page + '&sale=' + sale + '&cat=' + cat + '&price=' + price + '&keyword=' + keyword, function (data) {
                        if (data.length) {
                            var html = '';

                            $(data).each(function (k, v) {
                                var headimg = '';

                                if(v.image && v.image !== ''){
                                    headimg = url+'/public/'+v.image;
                                }else{
                                    headimg = url+'/public/<?php echo e($_SYSTEM['defaultportrait']); ?>';
                                }

                                var expressimg = '<?php echo e(url('public/home/img/edit/express.png')); ?>';
                                var cartimg = '<?php echo e(url('public/home/img/edit/cart.png')); ?>';
                                var brand_name = v.brand_name.replace('@', ' ');
                                var add_to_cart = '';
                                if (!v.special) {
                                    add_to_cart = '<a href="javascript:void(0);" class="list-cart" goods_id="'+v.id+'" limit_num="'+v.limit_num+'" stock="'+v.stock+'" onclick="globalAddCart(this);"></a>';
                                }

                                html += '\
                                <li>\
                                    <a href="'+url+'/goods_view/'+v.id+'" class="goods-info">\
                                        <p>\
                                            <img src="'+headimg+'" alt="" />\
                                        </p>\
                                        <h2>\
                                            <strong>'+v.name+'</strong>\
                                            <span>'+v.other_name+'</span>\
                                            <em>\
                                                <i>Brand</i>\
                                                '+brand_name+'\
                                            </em>\
                                        </h2>\
                                        <div>\
                                            <strong>￥ '+v.org_price+'</strong>\
                                            <br/>\
                                            <span>￥ '+v.price+'</span>\
                                            <em>VIP Price</em>\
                                        </div>\
                                    </a>\
                                    '+add_to_cart+'\
                                </li>';
                            });

                            $('.lists').append(html);

                            page++;

                            $('.more-bt').html('Load More');
                            $('.more-bt').attr('sending', 0);
                        } else {
                            $('.more-bt').html('No Data');
                            $('.more-bt').removeAttr('sending');
                        }
                    });
                }
            }
        });
    </script>
@endsection