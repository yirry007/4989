@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-mypage.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="contaner">

        <ul class="favor-select clearfix">
            <li><a href="<?php echo e(url('/favor')); ?>">Favorite Goods</a></li>
            <li><a href="<?php echo e(url('/favor_brand')); ?>" class="v">Favorites Brand</a></li>
        </ul>

        <div class="index-brand">
            <ul>
                <?php foreach($favorData as $v): ?>
                <li>
                    <a href="<?php echo e(url('/brand_view/'.$v->brand_id)); ?>">
                        <img src="<?php echo e(url('public/'.($v->image ? $v->image : $_SYSTEM['defaultportrait']))); ?>" alt="" />
                        <?php $names = explode('@', $v->name); ?>
                        <p><?php echo array_key_exists(0, $names) ? e($names[0]) : ''; ?><br/><em><?php echo array_key_exists(1, $names) ? e($names[1]) : ''; ?></em></p>
                        <span>Goods : <?php echo e($v->goods_count); ?></span>
                    </a>
                    <a href="javascript:void(0);" class="delete" onclick="ajaxFavorDelete(this);" sending="0" favor_id="<?php echo e($v->id); ?>">Remove</a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
@endsection

@section('script')
    <script>
        function ajaxFavorDelete(obj) {
            var sending = $(obj).attr('sending');

            if(sending === '0') {
                $(obj).attr('sending', '1');

                dialogMsgOkno('Are you sure to delete it?', function () {
                    var favor_id = $(obj).attr('favor_id');

                    $.post('<?php echo e(url('ajax_favor_delete')); ?>', {'id': favor_id,'_token': '<?php echo e(csrf_token()); ?>'}, function (data) {
                        if (data.status == 0) {
                            $(obj).parents('.index-brand li').remove();
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