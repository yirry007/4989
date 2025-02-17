@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-brand.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <input type="hidden" value="<?php echo url('brand'); ?>" name="share_link" />
    <div class="contaner">
        <div class="nav-head clearfix">
            <!--<p>全部分类</p>-->
            <div class="index-search">
                <a class="to_brand" href="<?php echo e(url('/brand')); ?>">
                    <img src="<?php echo e(url('public/home/img/brand.jpg')); ?>" />
                    <p>Brand</p>
                </a>
                <div>
                    <input type="text" placeholder="Search Goods" name="keyword" autocomplete="off" />
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

        <section class="brand-wrap">
            <div class="brand-cat">
                <a href="<?php echo e(url('/brand')) ?>" <?php if(app('request')->input('category') == '' && app('request')->input('keyword') == '') echo 'class="active"'; ?>>Total</a>
                <?php foreach($categoryData as $v): ?>
                <a href="<?php echo e(url('/brand?category='.$v->id)) ?>" <?php if(app('request')->input('category') == $v->id) echo 'class="active"'; ?>><?php echo e($v->name); ?></a>
                <?php endforeach; ?>
            </div>
            <div class="brand-info clearfix">
                <?php foreach($brandData as $v): ?>
                <a href="<?php echo e(url('/brand_view/'.$v->id)); ?>">
                    <img src="<?php echo e(url('public/'.($v->image ? $v->image : $_SYSTEM['defaultportrait']))); ?>" alt="" />
                    <?php $names = explode('@', $v->name); ?>
                    <p><?php echo array_key_exists(0, $names) ? e($names[0]) : ''; ?><br/><em><?php echo array_key_exists(1, $names) ? e($names[1]) : ''; ?></em></p>
                    <span>Goods : <?php echo e($v->goods_count); ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

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

            window.location.href = '<?php echo e(url('/goods')) ?>?keyword='+keyword;
        }
	</script>
@endsection