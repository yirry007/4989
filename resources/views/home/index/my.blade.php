@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-mypage.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="contaner">
        <div class="my-top celarfix">
            <div class="user-img"><img src="<?php echo e($memberData->portrait); ?>" alt="" /></div>
            <div class="fl">
                <div class="user-name"><?php echo e($memberData->nickname); ?></div>
                <div class="user-money">Balance : <?php echo e($memberData->money); ?><span>(Shopping VIP Price)</span></div>
                <div class="user-btn">
                    <a href="<?php echo e(url('account')); ?>" class="account_btn">Transaction</a>
                    <a href="<?php echo e(url('topup')); ?>" class="topup_btn">Top up</a>
                </div>
            </div>
        </div>

        <div class="my-order">
            <ul class="clearfix">
                <li>
                    <a href="<?php echo e(url('/order?select=1')); ?>">
                        <img src="<?php echo e(url('public/home/img/edit/icon9.jpg')); ?>" alt="" />
                        <p>Unpaid<br /><span>(<?php echo e($orderCount1); ?>)</span></p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(url('/order?select=2')); ?>">
                        <img src="<?php echo e(url('public/home/img/edit/icon10.jpg')); ?>" alt="" />
                        <p>Paid<br /><span>(<?php echo e($orderCount2); ?>)</span></p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(url('/order?select=3')); ?>">
                        <img src="<?php echo e(url('public/home/img/edit/icon11.jpg')); ?>" alt="" />
                        <p>Shipped<br /><span>(<?php echo e($orderCount3); ?>)</span></p>
                    </a>
                </li>
                <li>
                    <a href="<?php echo e(url('/order?select=4')); ?>">
                        <img src="<?php echo e(url('public/home/img/edit/icon12.jpg')); ?>" alt="" />
                        <p>Finish<br /><span>(<?php echo e($orderCount4); ?>)</span></p>
                    </a>
                </li>
            </ul>
        </div>

        <a href="<?php echo e(url('/suggest')); ?>" class="my-btn"><i class="iconfont icon-yanzhengma"></i><span>Suggest</span></a>

        <a href="<?php echo e(url('/address')); ?>" class="my-btn"><i class="iconfont icon-dizhi"></i><span>Address</span></a>

        <a href="<?php echo e(url('/favor')); ?>" class="my-btn"><i class="iconfont icon-shoucang"></i><span>Favorite</span></a>

        <div class="mypage_ad">
            <?php foreach($mypageAd as $v): ?>
            <a href="<?php echo e($v->link); ?>"><img src="<?php echo e(url('public/'.$v->image)); ?>" /></a>
            <?php endforeach; ?>
        </div>

        <div style="height:80px;"></div>

    </div>

    <div class="my_foot">
        <a href="<?php echo e(url('/')); ?>">Main</a>
        <a href="<?php echo e(url('cart')); ?>">Cart（<?php echo e($cartNum); ?>）</a>
    </div>

@endsection

@section('script')
    <script>

    </script>
@endsection