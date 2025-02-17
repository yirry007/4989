@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-mypage.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="contaner">
        <h4 class="exp_name"><?php echo e($expressName); ?></h4>
        <ul class="express">
            <?php foreach($expressData as $v): ?>
            <li>
                <p><?php echo e($v['time']); ?></p>
                <div><?php echo e($v['context']); ?></div>
            </li>
            <?php endforeach; ?>
        </ul>

    </div>
@endsection

@section('script')

@endsection