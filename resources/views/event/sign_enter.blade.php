@extends('event.layout')


@section('head')
    <title>签到红包</title>
@endsection


@section('bg', 'class="white"')


@section('content')
    <div class="sign_enter">
        <a href="<?php echo e(url('sign')); ?>"><img src="<?php echo e(url('public/event/img/sign_enter.png')); ?>" /></a>
    </div>
@endsection


@section('script')
    <script>

    </script>
@endsection

