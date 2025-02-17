@extends('we.layout')


@section('content')
	<style>
		.icon_box { display:flex;justify-content:space-around;flex-wrap:wrap; }
		.icon_box li { width:100px;height:100px;border:1px solid #ddd;text-align:center; }
		.icon_box i { font-size:30px;margin:20px 0 6px 0; }
	</style>
	<ul class="icon_box">
		<?php
			foreach($icon as $v):
			$v = ltrim($v, '.');
			$v = rtrim($v, ':');
		?>
		<li>
			<i class="fa fa-fw <?php echo e($v); ?>"></i><br/>
			<span><?php echo e($v); ?></span>
		</li>
		<?php endforeach; ?>
	</ul>
@endsection