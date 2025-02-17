<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<!-- 控制浏览器缓存 -->
		<meta http-equiv="Cache-Control" content="no-store" />
		<!-- 优先使用 IE 最新版本和 Chrome -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>4989西市场提现</title>
		<link rel="stylesheet" href="">
		<style>
			* {
				margin: 0;
				padding: 0;
				box-sizing: border-box;
			}
			
			html,
			body {
				height: 100%;
				overflow: hidden;
			}
			
			.clearfix:after {
				content: "\200B";
				display: block;
				height: 0;
				clear: both;
			}
			
			.clearfix {
				*zoom: 1;
			}
			
			
			/*IE/7/6*/
			
			.shuru div::-webkit-scrollbar {
				width: 0;
				height: 0;
				-webkit-transition: 1s;
			}
			
			.shuru div::-webkit-scrollbar-thumb {
				background-color: #a7afb4;
				background-clip: padding-box;
				min-height: 28px;
			}
			
			.shuru div::-webkit-scrollbar-thumb:hover {
				background-color: #525252;
				background-clip: padding-box;
				min-height: 28px;
			}
			
			.shuru div::-webkit-scrollbar-track-piece {
				background-color: #ccd0d2;
			}
			
			.wrap {
				position: relative;
				margin: auto;
				max-width: 640px;
				min-width: 320px;
				width: 100%;
				height: 100%;
				background: #F0EFF5;
				overflow: hidden;
			}
			
			.layer-content {
				position: absolute;
				left: 50%;
				bottom: -200px;
				width: 100%;
				max-width: 640px;
				height: auto;
				z-index: 12;
				-webkit-transform: translateX(-50%);
				transform: translateX(-50%);
			}
			
			/* 输入表单 */
			
			.edit_cash {
				display: block;
				margin-top: 15px;
				padding: 15px;
				margin: 0 auto;
				width: 90%;
				border: 1px solid #CFCFCF;
				border-radius: 10px;
				background-color: #fff;
				position:relative;
			}

			.edit_cash em {
				position:absolute;
				top:17px;
				right:15px;
				font-style:normal;
				font-size:12px;
				color:#8D8D8F;
			}
			
			.edit_cash p {
				font-size: 14px;
				color: #8D8D8F;
			}
			
			.shuru {
				position: relative;
				margin-bottom: 10px;
			}
			
			.shuru div {
				border: none;
				width: 100%;
				height: 50px;
				font-size: 25px;
				line-height: 50px;
				border-bottom: 1px solid #CFCFCF;
				text-indent: 30px;
				outline: none;
				white-space: pre;
				overflow-x: scroll;
			}
			
			.shuru span {
				position: absolute;
				top: 5px;
				font-size: 25px;
			}
			
			.submit {
				display: block;
				margin: 20px auto 0;
				width: 90%;
				height: 40px;
				font-size: 16px;
				color: #fff;
				background: #80D983;
				border: 1px solid #47D14C;
				border-radius: 3px;
			}
			
			
			/* 键盘 */
			
			.form_edit {
				width: 100%;
				background: #D1D4DD;
			}
			
			.form_edit> div {
				margin-bottom: 2px;
				margin-right: 0.5%;
				float: left;
				width: 33%;
				height: 45px;
				text-align: center;
				color: #333;
				line-height: 45px;
				font-size: 18px;
				font-weight: 600;
				background-color: #fff;
				border-radius: 5px;
			}
			
			.form_edit> div:nth-child(3n) {
				margin-right: 0;
			}
			
			.form_edit> div:last-child {
				background-color: #DEE1E9;
			}
			
			.title {
				margin:10px auto;
			}
		</style>
		<script src="http://libs.baidu.com/jquery/1.8.3/jquery.min.js"></script>
		<script src="<?php echo e(asset('/public/event/js/jquery.cookie.js')); ?>"></script>
		<script src="<?php echo e(asset('/public/event/js/layer/layer.js')); ?>"></script>
		<script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
	</head>
	<body>
		<div class="wrap">
			<div class="edit_cash title">4989西市场提现<em>余额：<?php echo e($member->coin); ?> 元</em></div>
			<form action="" class="edit_cash">
				<input type="hidden" name="coin" value="<?php echo e($member->coin); ?>" />
				<p>提现金额（最少<?php echo e($_SYSTEM['transfer_min']) ?>元，最多<?php echo e($_SYSTEM['transfer_max']) ?>元）</p>
				<div class="shuru">
					<span>&yen; <?php echo $member->coin < $_SYSTEM['transfer_max'] ? $member->coin : $_SYSTEM['transfer_max']; ?></span>
					<div id="div"></div>
				</div>
				<p>微信零钱提现</p>
			</form>
			<input type="button" value="提现" class="submit" sending="0" onclick="transfer(this);" />
		</div>
		<script>
			wx.config({
				debug: false,
				appId: '<?php echo e($signPackage["appId"]);?>',
				timestamp: parseInt('<?php echo e($signPackage["timestamp"]);?>'),
				nonceStr: '<?php echo e($signPackage["nonceStr"]);?>',
				signature: '<?php echo e($signPackage["signature"]);?>',
				jsApiList: [
					'onMenuShareTimeline',
					'onMenuShareAppMessage'
				]
			});

			wx.ready(function (){
				$(function(){
					wx.onMenuShareAppMessage({
						title: '<?php echo e($_SYSTEM["sitename"]); ?>',
						desc: '<?php echo e($_SYSTEM["siteinfo"]); ?>',
						link: '<?php echo e($_SYSTEM["transfer_share_link"]); ?>',
						imgUrl: '<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/public/'.$_SYSTEM["sharelogo"]; ?>',
						success: function(res){
							alert('모멘트에 공유해주십시요^^\n请分享到【微信朋友圈】，谢谢！');
						}
					});

					wx.onMenuShareTimeline({
						title: '<?php echo e($_SYSTEM["sitename"]); ?>',
						link: '<?php echo e($_SYSTEM["transfer_share_link"]); ?>',
						imgUrl: '<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/public/'.$_SYSTEM["sharelogo"]; ?>',
						success: function(res){
							$.cookie('transfer_shared', 1);
						}
					});
				});
			});

			function sharing(){
				var share_img = '<?php echo e(url('public/'.$_SYSTEM['transfer_share'])) ?>';
				layer.open({
					type: 1,
					content: '<div class="share"><img src="'+share_img+'" style="width:100%;" alt="" /></div>',
					shadeClose: true,
					style: 'position:fixed;top:50px;left:10%;width:80%;height:200px;padding:10px 0;border:none;background:none;'
				});
			}

			function transfer(obj){
				var shared = $.cookie('transfer_shared') || 0;

				if (!shared) {
					sharing();
					return false;
				}

				layer.open({
					content: '确定要提现吗？'
					,btn: ['确定', '取消']
					,skin: 'footer'
					,yes: function(index){
						var loading = layer.open({
							type: 2,
							shadeClose: false,
							time: 15
						});

						var sending = $(obj).attr('sending');

						if(sending === '1'){
							return false;
						}

						$(obj).attr('sending', '1')

						var coin = parseFloat($('input[name=coin]').val());
						var transfer_min = parseFloat('<?php echo e($_SYSTEM['transfer_min']) ?>');
						var transfer_max = parseFloat('<?php echo e($_SYSTEM['transfer_max']) ?>');

						if(coin < transfer_min){
							layer.open({content:'最小提现金额不能少于 '+transfer_min+' 元',skin:'msg',time:5});
							$(obj).attr('sending', '0');
							layer.close(loading);
							return false;
						}

						$.post('<?php echo e(url('transfer')); ?>', {'_token':'<?php echo e(csrf_token()); ?>'}, function(data){
							if(data.status == 0){
								layer.closeAll();
								$(obj).attr('sending', '0');
								$.removeCookie('transfer_shared');
								alert(data.msg);
								WeixinJSBridge.call('closeWindow');
							}else if(data.status == 691){
								window.location.href = '<?php echo e(url('login')); ?>';
							}
						});
					}
					,no: function(){
						layer.closeAll();
					}
				});
			}
		</script>
	</body>
</html>
