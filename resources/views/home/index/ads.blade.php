@extends('home.layout')

@section('title', ' - 옛 추억의 그맛')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-ad.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <input type="hidden" value="<?php echo e(url('/ads')); ?>" name="share_link" />
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
        <ul class="ads_list">
            <?php foreach($adsData as $v): ?>
            <li>
                <a href="<?php echo e($v->url) ?>"><img src="<?php echo e(url('public/'.$v->image)); ?>" /></a>
            </li>
            <?php endforeach; ?>
        </ul>
        <a href="javascript:void(0);" sending="0" class="more-bt">Load More</a>
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

        wx.ready(function () {
            $(function(){

                var share_link = $('input[name=share_link]').val();
                if(!share_link)share_link = '<?php echo 'http://'.$_SERVER['HTTP_HOST']; ?>';

                wx.onMenuShareAppMessage({
                    title: '<?php echo e($_SYSTEM["sitename"].' - Taste Of Memories'); ?>',
                    desc: '<?php echo e($_SYSTEM["siteinfo"]); ?>',
                    link: share_link,
                    imgUrl: '<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/public/'.$_SYSTEM["sharelogo"]; ?>'
                });

                wx.onMenuShareTimeline({
                    title: '<?php echo e($_SYSTEM["sitename"].' - Taste Of Memories'); ?>',
                    link: share_link,
                    imgUrl: '<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/public/'.$_SYSTEM["sharelogo"]; ?>'
                });
            });
        });

        //加载更多
        var page = 0;
        $(window).scroll(function(){
            var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
            if($(document).height() <= totalheight){
                var sending = $('.more-bt').attr('sending');
                if (sending === '0') {
                    $('.more-bt').attr('sending', '1');
                    $('.more-bt').html('Loading...');

                    $.get('<?php echo e(url('ajax_get_ads')) ?>?page=' + page, function (data) {
                        if (data.length) {
                            var html = '';

                            $(data).each(function (k, v) {
                                html += '\
                                <li>\
                                    <a href="'+v.url+'"><img src="/public/'+v.image+'" /></a>\
                                </li>';
                            });

                            $('.ads_list').append(html);

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