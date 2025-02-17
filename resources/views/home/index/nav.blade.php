@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-nav.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <input type="hidden" value="<?php echo e(url('nav')); ?>" name="share_link" />
    <div class="contaner">
        <div class="nav-head clearfix">
            <!--<p>全部分类</p>-->
            <div class="index-search">
                <a class="to_brand" href="<?php echo e(url('/brand')); ?>">
                    <img src="<?php echo e(url('public/home/img/brand.jpg')); ?>" />
                    <p>Brand</p>
                </a>
                <div>
                    <input type="text" placeholder="Goods Search" name="keyword" autocomplete="off" />
                    <a class="search_btn" href="javascript:void(0);" onclick="searchGoods();">Search</a>
                </div>
                <a class="head_cart" href="<?php echo e(url('cart')); ?>">
                    <img src="<?php echo e(url('public/home/img/edit/cart.png')); ?>" />
                    <p>Cart</p>
                    <em></em>
                </a>
            </div>
            <!--<a href="javascript:history.back(-1);">返回</a>-->
        </div>

        <ul class="nav-menu">
            <?php foreach($categoryData as $k=>$v): ?>
            <li>
                <a href="javascript: void(0);" onclick="navClick(this);" class="list <?php if($k == 0) echo 'v';?>"><?php echo e($v->name); ?></a>
                <?php if($v->sub_cat): ?>
                <dl class="clearfix">
                    <?php foreach($v->sub_cat as $v1): ?>
                    <dd>
                        <a href="<?php echo e('/goods?cat='.$v1->id); ?>">
                            <img src="<?php echo e(url('public/'.$v1->image)); ?>" alt="" />
                            <p><?php echo e(mb_substr($v1->name,0,12)); ?></p>
                        </a>
                    </dd>
                    <?php endforeach; ?>
                </dl>
                <?php endif;?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
@endsection

@section('script')
    <script>
        $(document).keyup(function(event){
            if(event.keyCode == 13){
                searchGoods();
            }
        });

        function navClick(obj) {
            $(obj).parents('.nav-menu').find('.list').removeClass('v');
            $(obj).parents('.nav-menu').find('dl').fadeOut(150);
            $(obj).addClass('v');
            $(obj).parent().find('dl').delay(150).fadeIn(500);
        }
        //商品搜索
        function searchGoods(){
            var keyword = $('input[name=keyword]').val();
            keyword = keyword.trim();

            window.location.href = '<?php echo e(url('/goods')) ?>?keyword='+keyword;
        }
    </script>
@endsection