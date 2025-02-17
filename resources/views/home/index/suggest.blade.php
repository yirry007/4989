@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-suggest.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <input type="hidden" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/suggest'; ?>" name="share_link" />
    <div class="black"></div>
    <div class="contaner">
        <h2>We are grow up by your complaints and suggestions</h2>
        <h2>4989서시장은 당신의 소중한 의견이 필요합니다</h2>
        <div class="sel_opt">
            <a href="javascript:void(0);" class="active" val="1">Complain</a>
            <a href="javascript:void(0);" val="2">Suggest</a>
        </div>
        <div class="txt_area">
            <textarea name="content" placeholder="At most 500 characters..." onkeyup="countLetter(this);"></textarea>
            <p>
                <span id="letter">0</span>/500
            </p>
            <div class="txt_sub clearfix">
                <a href="javascript:void(0);" id="upload_image"><img src="<?php echo e(url('/public/home/img/upload.png')) ?>" /></a>
            </div>
        </div>
        <div class="memo">
            Get ￥<?php echo e($_SYSTEM['suggest_pay']); ?> per time<br/>
            Make sure in <a href="<?php echo e(url('account')); ?>">'s balance</a>
        </div>
        <a href="javascript:void(0);" class="suggest_btn" onclick="suggest_submit(this);" sending="0">Submit</a>
        <div class="finish">
            <h3>Thanks for you suggestion</h3>
            <h4>보귀한 의견 감사합니다!</h4>
            <p><img src="<?php echo e(url('/public/home/img/edit/icon1.jpg')) ?>" /></p>
            <a href="<?php echo e(url('/index')) ?>" class="finish_btn">Confirm And To Main</a>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function(){
            $('.sel_opt a').click(function(){
                $(this).siblings('a').removeClass('active');
                $(this).addClass('active');
            });
        });

        function countLetter(obj){
            var content = $(obj).val();
            var max = 500;

            $('#letter').html(content.length);

            if(content.length > max){
                $(obj).val(content.slice(0,500));
                $('#letter').html(500);
            }
        }

        var uploaded = 0;
        var max_upload = 5;
        wx.ready(function (){
            var images = {
                localId: []
            };

            $("#upload_image").click(function(){
                var _this =$(this);
                images.localId = [];
                wx.chooseImage({
                    success: function (res) {
                        images.localId = res.localIds;
                        if (images.localId.length == 0) {
                            alert('Select Image');
                            return;
                        }

                        var left = max_upload - uploaded;
                        var length = Math.min(images.localId.length, left);
                        var i = 0;

                        function upload() {
                            if(uploaded >= max_upload){
                                $("#upload_image").unbind('click');
                                return false;
                            }

                            wx.uploadImage({
                                localId: images.localId[i],
                                success: function (res) {
                                    var html = '<a href="javascript:void(0);"><input type="hidden" class="upload_image" value="'+res.serverId+'" /><img src="'+images.localId[i]+'" /></a>';

                                    $('.txt_sub').prepend(html);

                                    i++;
                                    uploaded++;
                                    if(i < length){
                                        upload();
                                    }
                                },
                                fail: function (res) {
                                    alert(JSON.stringify(res));
                                }
                            });
                        }

                        upload();
                    }
                });
            });
        });

        function suggest_submit(obj) {
            var sending = $(obj).attr('sending');

            if(sending === '0'){
                dialogMsgOkno('Confirm to submit', function () {
                    $(obj).attr('sending', '1');

                    var types = $('.sel_opt a.active').attr('val');
                    var content = $('textarea[name=content]').val();
                    var image = '';
                    var upload_images = $('input.upload_image');

                    if(!content.length){
                        dialogMsg('Please input content');
                        $(obj).attr('sending', '0');
                        return false;
                    }
                    if(content.length < 8){
                        dialogMsg('At least 8 characters');
                        $(obj).attr('sending', '0');
                        return false;
                    }

                    $(upload_images).each(function(k, v){
                        if(k != 0){
                            image += '@';
                        }
                        image += $(v).val();
                    });

                    $.post('<?php echo e(url('suggest')); ?>', {'types':types, 'content':content, 'image':image, '_token': '<?php echo e(csrf_token()); ?>'}, function(data){
                        if (data.status == 0) {
                            $('.finish').fadeIn(300);
                            $('.black').fadeIn(300);
                            $.post('<?php echo e(url('send_suggest_msg')); ?>/'+data.suggest_id, {'_token': '<?php echo e(csrf_token()); ?>'}, function(data){})
                        } else if(data.status == 691){
                            window.location.href = '<?php echo e(url('login')); ?>';
                        } else {
                            dialogMsg(data.msg);
                        }

                        $(obj).attr('sending', '0');
                    });
                });
            }
        }
    </script>
@endsection