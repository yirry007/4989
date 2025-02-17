@extends('home.layout')

@section('head')
    <link href="<?php echo e(asset('public/home/css/style-suggest.css')); ?>" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <input type="hidden" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/suggest'; ?>" name="share_link" />
    <div class="black"></div>
    <div class="contaner">
        <h2>Please input address and phone number</h2>
        <h2>당첨품 받으실 주소를 입력해 주십시오.</h2>
        <div class="sel_opt">
            <a href="javascript:void(0);" class="active" val="1">Info.</a>
        </div>
        <div class="txt_area">
            <textarea name="content" placeholder="At most 500 characters..." onkeyup="countLetter(this);"></textarea>
            <p>
                <span id="letter">0</span>/500
            </p>
            <div class="txt_sub clearfix">
                <a href="javascript:void(0);" onclick="$('#Filedata').trigger('click');">
                    <img id="image_preview" src="<?php echo e(url('/public/home/img/upload.png')) ?>" />
                </a>
                <span>Upload a screenshot of the like page</span>
                <input type="hidden" name="image" value="" />
                <input type="file" name="Filedata" id="Filedata" accept="image/*" onchange="praiseImageUpload(this);" />
            </div>
        </div>
        <div class="memo">Please check if the address and contact information provided are incorrect. Once submitted, they cannot be modified</div>
        <a href="javascript:void(0);" class="praise_btn" onclick="suggest_submit(this);" sending="0">Submit</a>
        <a href="<?php echo e(url('index')); ?>" class="suggest_btn">Single Buy（￥38 On Event）</a>
        <div class="finish">
            <h3>Thanks for buying</h3>
            <p><img src="<?php echo e(url('/public/home/img/edit/icon1.jpg')) ?>" /></p>
            <a href="<?php echo e(url('/index')) ?>" class="finish_btn">Confirm And To Home</a>
        </div>
    </div>
@endsection

@section('script')
    <script src="<?php echo e(asset('/public/home/js/ajaxfileupload.js')); ?>"></script>
    <script>
        $(function(){
            $('.sel_opt a').click(function(){
                $(this).siblings('a').removeClass('active');
                $(this).addClass('active');
            });
        });

        function praiseImageUpload(obj){
            var value = $(obj).val();

            if(!value.match(/.jpg|.jpeg|.gif|.png|.bmp/i)){
                dialogMsg('File format error');
                return false;
            }

            $.ajaxFileUpload({
                url:'<?php echo e(url('upload_one/praise')); ?>',
                secureuri:false,
                dataType:'JSON',
                fileElementId:'Filedata',
                data:{'name':'Filedata'},        //其它参数
                success:function(data){
                    var image = $('input[name=image]').val();

                    $.post('<?php echo e(url('/delete_one')); ?>', {'_token':'<?php echo e(csrf_token()); ?>','url':image}, function(_data){
                        console.log(_data.msg);
                    });

                    $('input[name=image]').val(data);

                    $('#image_preview').attr('src', '/public/'+data);
                },
                error:function(data,status,_exception){
                    console.log(_exception);
                }
            });
        }

        function countLetter(obj){
            var content = $(obj).val();
            var max = 500;

            $('#letter').html(content.length);

            if(content.length > max){
                $(obj).val(content.slice(0,500));
                $('#letter').html(500);
            }
        }

        function suggest_submit(obj) {
            var sending = $(obj).attr('sending');

            if(sending === '0'){
                dialogMsgOkno('Confirm to submit', function () {
                    $(obj).attr('sending', '1');

                    var content = $('textarea[name=content]').val();
                    var image = $('input[name=image]').val();

                    if(!content.length){
                        dialogMsg('Please input address and phone number');
                        $(obj).attr('sending', '0');
                        return false;
                    }
                    /*
                    if(!image.length){
                        dialogMsg('请上传点赞截图图片');
                        $(obj).attr('sending', '0');
                        return false;
                    }
                    */

                    $.post('<?php echo e(url('praise')); ?>', {'content':content, 'image':image, '_token': '<?php echo e(csrf_token()); ?>'}, function(data){
                        if (data.status == 0) {
                            $('.finish').fadeIn(300);
                            $('.black').fadeIn(300);
                            $.post('<?php echo e(url('send_praise_msg')); ?>/'+data.praise_id, {'_token': '<?php echo e(csrf_token()); ?>'}, function(data){});
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