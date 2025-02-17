@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-index.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="contaner">
        <?php if($bannerData): ?>
        <div class="index-banner">
            <div class="flexslider">
                <ul class="slides">
                    <?php foreach($bannerData as $v): ?>
                    <li><a href="<?php echo e($v->link); ?>" target="_blank"><img src="<?php echo e(url('public/'.$v->image)); ?>" alt="" /></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <div class="index-wrap">
            <div class="rec">
                <h2>Recommend Goods</h2>
                <ul>
                    <?php foreach($goodsData as $v): ?>
                    <li>
                        <a href="<?php echo e(url('/goods_view/'.$v->id)); ?>">
                            <p>
                                <img src="<?php echo e(url('public/'.($v->image ? $v->image : $_SYSTEM['defaultportrait']))); ?>" alt="" />
                            </p>
                            <h3><?php echo e($v->name); ?></h3>
                            <span>￥ <?php echo e($v->org_price); ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="cate">
                <div class="cate-head">
                    <h2>Recommend Category</h2>
                    <a href="<?php echo e(url('/nav')); ?>">View More</a>
                </div>
                <div class="cate-list">
                    <?php foreach($categoryData as $v): ?>
                    <a href="<?php echo e(url('/goods?cat='.$v->id)); ?>">
                        <img src="<?php echo e(url('public/'.$v->image)); ?>" alt="" />
                        <p><?php echo e($v->name); ?></p>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <ul class="floor">
                <?php foreach($floors as $v): ?>
                <li>
                    <?php if(array_key_exists('ad', $v)): ?>
                    <a href="<?php echo e($v['ad']->link); ?>" class="floor-ad"><img src="<?php echo e(url('public/'.$v['ad']->image)); ?>" alt="" /></a>
                    <?php endif; ?>
                    <div class="floor-content">
                        <div class="floor-head">
                            <h2><?php echo e($v['name']); ?></h2>
                            <a href="<?php echo e(url('/goods?cat='.$v['id'])); ?>">View More</a>
                        </div>
                        <div class="floor-list">
                            <?php foreach($v['goods'] as $v1): ?>
                            <a href="<?php echo e(url('/goods_view/'.$v1->id)); ?>">
                                <p>
                                    <img src="<?php echo e(url('public/'.($v1->image ? $v1->image : $_SYSTEM['defaultportrait']))); ?>" alt="" />
                                </p>
                                <h2>
                                    <span><?php echo e($v1->name); ?></span>
                                    <strong><?php echo e($v1->other_name); ?></strong>
                                    <em>
                                        <i>Brand</i>
                                        <?php echo e(str_replace('@', ' ', $v1->brand_name)); ?>
                                    </em>
                                </h2>
                                <div>
                                    <strong>￥ <?php echo e($v1->org_price); ?></strong>
                                    <br/>
                                    <span>￥ <?php echo e($v1->price); ?></span>
                                    <em>VIP Price</em>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>

            <div class="brands">
                <div class="brands-head">
                    <h2>Recommend Brand</h2>
                    <a href="<?php echo e(url('/brand')); ?>">View More</a>
                </div>
                <div class="brands-list">
                    <?php foreach($brandData as $v): ?>
                    <a href="<?php echo e(url('/brand_view/'.$v->id)); ?>">
                        <img src="<?php echo e(url('public/'.($v->image ? $v->image : $_SYSTEM['defaultportrait']))); ?>" alt="" />
                        <div>
                            <?php $names = explode('@', $v->name); ?>
                            <h2><?php echo array_key_exists(0, $names) ? e($names[0]) : ''; ?></h2>
                            <p><?php echo array_key_exists(1, $names) ? e($names[1]) : ''; ?></p>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <a href="{{ url('nav') }}" class="to-nav">Goods<br/>Search</a>

    <?php if($mainTopup): ?>
    <a href="<?php echo e($mainTopup->link); ?>" class="index_popup"><img src="<?php echo e(url('public/'.$mainTopup->image)) ?>" alt="" /></a>
    <div class="index_popup_bg" onclick="popupClose()"></div>
    <script>
        function popupClose() {
            $('.index_popup').fadeOut(300);
            $('.index_popup_bg').fadeOut(300);
        }
    </script>
    <?php endif; ?>

    <div class="copyright" style="text-align:center;">
        <p style="font-size:13px;color:#202639;">Copyright 2021 © 延边嗨科网络科技有限公司</p>
        <a href="http://www.beian.miit.gov.cn" style="display:block;padding:10px 0;color:#7e7e7e;">延边嗨科网络科技有限公司<br/>{{ $_SYSTEM['icp'] }}</a>
    </div>

    @include('home.index.footer')

@endsection

@section('script')
    <script src="<?php echo e(asset('public/home/js/jquery.flexslider.min.js')); ?>"></script>
    <script>
        //首页轮播
        $(window).load(function(){
            $('.flexslider').flexslider({
                animation: "fade",
                start: function(slider){
                    $('body').removeClass('loading');
                }
            });
        });
    </script>
@endsection