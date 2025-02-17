@extends('admin.layout')
@section('content')
<div class="pd-20">
  <form action="<?php echo e(url('/admin/goods/'.$field->id)); ?>" method="post" class="form form-horizontal">
  	<input type="hidden" name="_method" value="put" />
  	<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
  	<input type="hidden" name="page" value="<?php echo e(app('request')->input('page')); ?>" />
  	<input type="hidden" name="o_fiveday" value="<?php echo e(app('request')->input('fiveday')); ?>" />
  	<input type="hidden" name="o_category_id" value="<?php echo e(app('request')->input('category_id')); ?>" />
  	<input type="hidden" name="o_brand_id" value="<?php echo e(app('request')->input('brand_id')); ?>" />

      <div id="tab-category" class="HuiTab">
          <div class="tabBar cl"><span>Basic Info</span><span>Gallery</span><span>Description</span></div>
          <div class="tabCon">
              <div class="row cl">
                  <label class="form-label col-3"><span class="c-red">*</span>Name：</label>
                  <div class="formControls col-6">
                      <input type="text" class="input-text" value="<?php echo e($field->name); ?>" placeholder="" name="name" >
                  </div>
                  <div class="col-3">
                      <?php if($errors->first('name')): ?>
                      <span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('name')); ?></span>
                      <?php endif; ?>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Other Name：</label>
                  <div class="formControls col-6">
                      <input type="text" class="input-text" value="<?php echo e($field->other_name); ?>" placeholder="" name="other_name" >
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Video：</label>
                  <div class="formControls col-6">
                      <input type="text" class="input-text" value="<?php echo e($field->video); ?>" placeholder="" id="" name="video" readonly>
                  </div>
                  <div class="formControls col-3">
                      <input id="video_upload" type="file" multiple="true">
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Preview：</label>
                  <div class="formControls col-6">
                      <video src="<?php echo e(url('/public/'.$field->video)); ?>" width="300" controls="controls" id="video_preview">Browser not supported</video>
                      <?php if($field->video): ?>
                      <a href="javascript:void(0);" onclick="deleteVideo();" style="display:block;width:72px;">Del</a>
                      <?php endif; ?>
                  </div>
                  <div class="formControls col-3"></div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Poster：</label>
                  <div class="formControls col-6">
                      <input type="text" class="input-text" value="<?php echo e($field->poster); ?>" placeholder="" id="" name="poster" readonly>
                  </div>
                  <div class="formControls col-3">
                      <input id="poster" type="file" multiple="true">
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Preview：</label>
                  <div class="formControls col-6">
                      <img src="<?php echo e(url('public/'.$field->poster)); ?>" width="240" id="poster_preview" />
                  </div>
                  <div class="col-3"></div>
              </div>
              <div class="row cl category">
                  <label class="form-label col-3 category_title">Category：</label>
                  <div class="formControls col-6">
                    <span class="select-box">
                        <select name="category_id" class="select category_name">
                        <?php foreach($categoryData as $v): ?>
                        <option value="<?php echo e($v->id); ?>" <?php echo $field->category_id == $v->id ? 'selected' : ''; ?>><?php echo e(str_repeat('◇', 4*$v->level).$v->name); ?></option>
                        <?php endforeach; ?>
                        </select>
                    </span>
                  </div>
                  <div class="col-3">
                      <a class="btn btn-success radius category_btn" href="javascript:void(0)" onclick="extendCategory()" title="Category Ext" >Category Ext</a>
                  </div>
              </div>
              <?php foreach($goodsCategory as $v): ?>
              <div class="row cl category_ext">
                  <label class="form-label col-3 category_title">Category Ext：</label>
                  <div class="formControls col-6">
                    <span class="select-box">
                        <select name="goods_category[]" class="select category_name">
                        <?php foreach($categoryData as $v1): ?>
                        <option value="<?php echo e($v1->id); ?>" <?php echo $v->category_id == $v1->id ? 'selected' : ''; ?>><?php echo e(str_repeat('◇', 4*$v1->level).$v1->name); ?></option>
                        <?php endforeach; ?>
                        </select>
                    </span>
                  </div>
                  <div class="col-3">
                      <a class="btn btn-success radius category_btn" href="javascript:void(0)" onclick="delExtendCategory(this)" title="Category Ext" >Del Category</a>
                  </div>
              </div>
              <?php endforeach; ?>
              <div class="row cl">
                  <label class="form-label col-3">Brand：</label>
                  <div class="formControls col-6">
            <span class="select-box">
                <select class="select" size="1" name="brand_id">
                    <option value="0" <?php if($field->brand_id == 0) echo 'selected'; ?>>Please Select Brand</option>
                    <?php foreach($brandData as $v): ?>
                    <option value="<?php echo e($v->id) ?>" <?php if($field->brand_id == $v->id) echo 'selected'; ?>><?php echo e($v->name) ?></option>
                    <?php endforeach; ?>
                </select>
            </span>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3"><span class="c-red">*</span>Image：</label>
                  <div class="formControls col-6">
                      <input type="text" class="input-text" value="<?php echo e($field->image); ?>" placeholder="" id="" name="image" readonly>
                  </div>
                  <div class="formControls col-3">
                      <input id="file_upload" type="file" multiple="true">
                  </div>
              </div>
              <div class="row cl" style="margin: 0;">
                  <label class="form-label col-3"></label>
                  <div class="formControls col-6" style="color: #f8658f;">
                      <span style="color: #999">Best upload image size</span> W : H(1 : 1)
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3"><span class="c-red">*</span>Preview：</label>
                  <div class="formControls col-6">
                      <img src="<?php echo e(url('public/'.$field->image)); ?>" width="240" id="image_preview" />
                  </div>
                  <div class="col-3">
                      <?php if($errors->first('image')): ?>
                      <span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('image')); ?></span>
                      <?php endif; ?>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3"><span class="c-red">*</span> <input type="text" class="input-text" style="width: 60px;" value="<?php echo e($field->price_name); ?>" name="price_name" >：</label>
                  <div class="formControls col-6">
                      <input type="text" class="input-text" value="<?php echo e($field->price); ?>" onkeyup="this.value=this.value.replace(/[^\d.]/g, '');" placeholder="" name="price" >
                  </div>
                  <div class="col-3">
                      <?php if($errors->first('price')): ?>
                      <span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('price')); ?></span>
                      <?php endif; ?>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3"><span class="c-red">*</span> <input type="text" class="input-text" style="width: 60px;" value="<?php echo e($field->org_price_name); ?>" name="org_price_name" >VIP price：</label>
                  <div class="formControls col-6">
                      <input type="text" class="input-text" value="<?php echo e($field->org_price); ?>" onkeyup="this.value=this.value.replace(/[^\d.]/g, '');" placeholder="" name="org_price" >
                  </div>
                  <div class="col-3">
                      <?php if($errors->first('org_price')): ?>
                      <span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('org_price')); ?></span>
                      <?php endif; ?>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3"><span class="c-red">*</span>Weight(kg)：</label>
                  <div class="formControls col-6">
                      <input type="text" class="input-text" value="<?php echo e($field->weight); ?>" onkeyup="this.value=this.value.replace(/[^\d.]/g, '');" placeholder="" name="weight" >
                  </div>
                  <div class="col-3">
                      <?php if($errors->first('weight')): ?>
                      <span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('weight')); ?></span>
                      <?php endif; ?>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Delivery Province：</label>
                  <div class="formControls col-6">
            <span class="select-box">
                <select class="select" size="1" name="express_id">
                    <option value="0" <?php if($field->express_id == 0) echo 'selected'; ?>>National</option>
                    <?php foreach($express as $v): ?>
                    <option value="<?php echo e($v->id) ?>" <?php if($field->express_id == $v->id) echo 'selected'; ?>><?php echo e($v->province_name) ?></option>
                    <?php endforeach; ?>
                </select>
            </span>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3"><span class="c-red">*</span>Stock：</label>
                  <div class="formControls col-6">
                      <input type="text" class="input-text" value="<?php echo e($field->stock); ?>" onkeyup="this.value=this.value.replace(/\D/g, '');" placeholder="" name="stock" >
                  </div>
                  <div class="col-3">
                      <?php if($errors->first('stock')): ?>
                      <span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('stock')); ?></span>
                      <?php endif; ?>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Minimum Buy：</label>
                  <div class="formControls col-6">
                      <input type="text" class="input-text" value="<?php echo e($field->limit_num); ?>" placeholder="default 1" onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" name="limit_num" >
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Sale Volume：</label>
                  <div class="formControls col-6">
                      <input type="text" class="input-text" value="<?php echo e($field->sale); ?>" placeholder="default 0" onkeyup="this.value=this.value.replace(/\D/g, '');" name="sale" >
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Is Enable：</label>
                  <div class="formControls col-6 skin-minimal">
                      <div class="radio-box">
                          <input type="radio" name="is_use" value="1" <?php if($field->is_use == 1) echo 'checked'; ?>>
                          <label>Enable</label>
                      </div>
                      <div class="radio-box">
                          <input type="radio" name="is_use" value="0" <?php if($field->is_use == 0) echo 'checked'; ?>>
                          <label>Disable</label>
                      </div>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Is Rec：</label>
                  <div class="formControls col-6 skin-minimal">
                      <div class="radio-box">
                          <input type="radio" name="is_rec" value="1" <?php if($field->is_rec == 1) echo 'checked'; ?>>
                          <label>Enable</label>
                      </div>
                      <div class="radio-box">
                          <input type="radio" name="is_rec" value="0" <?php if($field->is_rec == 0) echo 'checked'; ?>>
                          <label>Disable</label>
                      </div>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Is Promotion：</label>
                  <div class="formControls col-6 skin-minimal">
                      <div class="radio-box">
                          <input type="radio" name="is_sale" value="1" <?php if($field->is_sale == 1) echo 'checked'; ?>>
                          <label>Enable</label>
                      </div>
                      <div class="radio-box">
                          <input type="radio" name="is_sale" value="0" <?php if($field->is_sale == 0) echo 'checked'; ?>>
                          <label>Disable</label>
                      </div>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Single Buy：</label>
                  <div class="formControls col-6 skin-minimal">
                      <div class="radio-box">
                          <input type="radio" name="is_one" value="1" <?php if($field->is_one == 1) echo 'checked'; ?>>
                          <label>Single</label>
                      </div>
                      <div class="radio-box">
                          <input type="radio" name="is_one" value="0" <?php if($field->is_one == 0) echo 'checked'; ?>>
                          <label>Multi</label>
                      </div>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Is Special：</label>
                  <div class="formControls col-6 skin-minimal">
                      <div class="radio-box">
                          <input type="radio" name="special" value="1" <?php if($field->special == 1) echo 'checked'; ?>>
                          <label>Special</label>
                      </div>
                      <div class="radio-box">
                          <input type="radio" name="special" value="0" <?php if($field->special == 0) echo 'checked'; ?>>
                          <label>Normal</label>
                      </div>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Is Five Day：</label>
                  <div class="formControls col-6 skin-minimal">
                      <div class="radio-box">
                          <input type="radio" name="fiveday" value="1" <?php if($field->fiveday == 1) echo 'checked'; ?>>
                          <label>Five Day</label>
                      </div>
                      <div class="radio-box">
                          <input type="radio" name="fiveday" value="0" <?php if($field->fiveday == 0) echo 'checked'; ?>>
                          <label>Normal</label>
                      </div>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3"><span class="c-red">*</span>Five Day Price：</label>
                  <div class="formControls col-6">
                      <input type="text" class="input-text" value="<?php echo e($field->market_price); ?>" onkeyup="this.value=this.value.replace(/[^\d\.]/g, '');" placeholder="" name="market_price" >
                  </div>
                  <div class="col-3">
                      <?php if($errors->first('market_price')): ?>
                      <span class="Validform_checktip Validform_wrong"><?php echo e($errors->first('market_price')); ?></span>
                      <?php endif; ?>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-3">Sort：</label>
                  <div class="formControls col-6">
                      <input type="text" class="input-text" value="<?php echo e($field->sort); ?>" placeholder="留空则默认为0" onkeyup="this.value=this.value.replace(/\D/g, '');" name="sort" >
                  </div>
              </div>
          </div>
          <div class="tabCon">
              <div class="row cl">
                  <label class="form-label col-2">Gallery：</label>
                  <div class="formControls col-8">
                      <div class="uploader-list-container">
                          <div class="queueList">
                              <div id="dndArea" class="placeholder">
                                  <div id="filePicker-2"></div>
                                  <p>Drag the photos here, up to 300 can be selected at a time</p>
                              </div>
                          </div>
                          <div class="statusBar" style="display:none;">
                              <div class="progress"> <span class="text">0%</span> <span class="percentage"></span> </div>
                              <div class="info"></div>
                              <div class="btns">
                                  <div id="filePicker2"></div>
                                  <div class="uploadBtn">Upload</div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row cl">
                  <label class="form-label col-2">Uploaded：</label>
                  <div class="formControls col-8">
                      <div id="goods_images">
                          <?php foreach($goodImg as $v): ?>
                          <div class="uploaded_img">
                              <input type="hidden" class="good_img" name="goods_images[]" value="<?php echo e($v->sm_img.'@'.$v->big_img.'@'.$v->img); ?>" />
                              <img src="<?php echo e(url('public/'.$v->sm_img)); ?>" width="90" height="90" />
                              <img src="<?php echo e(asset('public/root/lib/uploadify-cancel.png')); ?>" class="delete_btn" onclick="deleteUploadedImg(this);" />
                          </div>
                          <?php endforeach; ?>
                      </div>
                  </div>
              </div>
          </div>
          <div class="tabCon">
              <div class="row cl">
                  <label class="form-label col-2">Description：</label>
                  <div class="formControls col-8">
                      <textarea name="detail" id="detail"><?php echo $field->detail; ?></textarea>
                  </div>
              </div>
          </div>
      </div>

    <div class="row cl">
      <div class="col-8 col-offset-4">
        <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;Submit&nbsp;&nbsp;">
      </div>
    </div>
  </form>
</div>
<!-- 图片上传css+js -->
<link href="<?php echo e(asset('public/root/lib/uploadify/uploadify.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(asset('public/root/lib/icheck/icheck.css')); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="<?php echo e(asset('public/root/lib/uploadify/jquery.uploadify.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/icheck/jquery.icheck.min.js')); ?>"></script>
<!-- 编辑器js -->
<script type="text/javascript" charset="utf-8" src="<?php echo e(asset('public/root/lib/ueditor/ueditor.config.js')); ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo e(asset('public/root/lib/ueditor/ueditor.all.min.js')); ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo e(asset('public/root/lib/ueditor/lang/zh-cn/zh-cn.js')); ?>"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo e(asset('public/root/lib/uploadify/jquery.uploadify.min.js')); ?>"></script>
<!-- 多图片上传js -->
<link href="<?php echo e(asset('public/root/lib/webuploader/0.1.5/webuploader.css')); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/webuploader/0.1.5/webuploader.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('public/root/lib/ajaxupload/ajaxupload.3.5.js')); ?>"></script>
<script>
    //头部分类
    $(function(){
        $.Huitab("#tab-category .tabBar span","#tab-category .tabCon","current","click","1");
    });

    //单选框样式
    $(function() {
        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });
    });

    function extendCategory () {
        let category_html = $('.category').html();
        let html = '<div class="row cl category_ext">'+category_html+'</div>';

        let category_ext_exists = $('.category_ext').size();

        if (category_ext_exists) {
            $('.category_ext').last().after(html);
        } else {
            $('.category').after(html);
        }

        $('.category_ext').each(function(k, v){
            $(v).find('.category_title').html('Category Ext：');
            $(v).find('.category_name').attr('name', 'goods_category[]');
            $(v).find('.category_btn').attr('onclick', 'delExtendCategory(this)').html('Del Cate');
        });
    }

    function delExtendCategory (obj) {
        $(obj).parents('.category_ext').remove();
    }

    //视频上传
    $('#video_upload').uploadify({
        'buttonText' : 'BROWSE...',
        'formData'     : {
            'timestamp' : '<?php echo time(); ?>',
            '_token'     : '<?php echo e(csrf_token()); ?>'
        },
        'swf'      : "<?php echo e(asset('/public/root/lib/uploadify/uploadify.swf')) ?>",
        'uploader' : "<?php echo e(url('/admin/upload_one/videos')) ?>",
        'onUploadSuccess' : function(file, data, response){

            var oldVideo = $('input[name=video]').val();
            if(oldVideo){
                $.post('<?php echo e(url('/admin/delete_one')); ?>', {'_token':'<?php echo e(csrf_token()); ?>','url':oldVideo}, function(data){
                    if(data.status == '0'){
                        layer.msg(data.msg, {icon:1, time:2000});
                    }else{
                        layer.msg(data.msg, {icon:2, time:2000});
                    }
                });
            }

            $('input[name=video]').val(data);
            $('#video_preview').attr('src','/public/'+data);
        }
    });

    //删除已上传视频
    function deleteVideo(){
        layer.confirm('Are you sure to delete it?', {
            btn : ['Yes', 'No']
        }, function(){
            var url = $('input[name=video]').val();
            $.post('<?php echo e(url('/admin/delete_one')); ?>', {'_token':'<?php echo e(csrf_token()); ?>','url':url}, function(data){
                if(data.status == '0'){
                    layer.msg(data.msg, {icon:1, time:2000}, function(){
                        $('input[name=video]').val('');
                        $('#video_preview').attr('src','');
                    });
                }else{
                    layer.msg(data.msg, {icon:2, time:2000});
                }
            });
        }, function(){
            layer.closeAll();
        });
    }

    //视频封面上传
    $('#poster').uploadify({
        'buttonText' : 'BROWSE...',
        'formData'     : {
            'timestamp' : '<?php echo time(); ?>',
            '_token'     : '<?php echo e(csrf_token()); ?>'
        },
        'swf'      : "<?php echo e(asset('/public/root/lib/uploadify/uploadify.swf')) ?>",
        'uploader' : "<?php echo e(url('/admin/upload_one/poster')) ?>",
        'onUploadSuccess' : function(file, data, response){

            var oldPoster = $('input[name=poster]').val();
            if(oldPoster){
                $.post('<?php echo e(url('/admin/delete_one')); ?>', {'_token':'<?php echo e(csrf_token()); ?>','url':oldPoster}, function(data){
                    if(data.status == '0'){
                        layer.msg(data.msg, {icon:1, time:2000});
                    }else{
                        layer.msg(data.msg, {icon:2, time:2000});
                    }
                });
            }

            $('input[name=poster]').val(data);
            $('#poster_preview').attr('src','/public/'+data);
        }
    });

    //图片上传
    $('#file_upload').uploadify({
        'buttonText' : 'BROWSE...',
        'formData'     : {
            'timestamp' : '<?php echo time(); ?>',
            '_token'     : '<?php echo e(csrf_token()); ?>'
        },
        'swf'      : "<?php echo e(asset('/public/root/lib/uploadify/uploadify.swf')) ?>",
        'uploader' : "<?php echo e(url('/admin/upload_one/goods')) ?>",
        'onUploadSuccess' : function(file, data, response){

            var oldImg = $('input[name=image]').val();
            if(oldImg){
                $.post('<?php echo e(url('/admin/delete_one')); ?>', {'_token':'<?php echo e(csrf_token()); ?>','url':oldImg}, function(data){
                    if(data.status == '0'){
                        layer.msg(data.msg, {icon:1, time:2000});
                    }else{
                        layer.msg(data.msg, {icon:2, time:2000});
                    }
                });
            }

            $('input[name=image]').val(data);
            $('#image_preview').attr('src','/public/'+data);
        }
    });

    //编辑器
    UE.getEditor('detail', {
        "initialFrameWidth" : "100%",   // 宽
        "initialFrameHeight" : 300,      // 高
        "maximumWords" : 10000            // 最大可以输入的字符数量
    });

    //多图片上传
    (function( $ ){
        // 当domReady的时候开始初始化
        $(function() {
            var $wrap = $('.uploader-list-container'),

                // 图片容器
                $queue = $( '<ul class="filelist"></ul>' )
                    .appendTo( $wrap.find( '.queueList' ) ),

                // 状态栏，包括进度和控制按钮
                $statusBar = $wrap.find( '.statusBar' ),

                // 文件总体选择信息。
                $info = $statusBar.find( '.info' ),

                // 上传按钮
                $upload = $wrap.find( '.uploadBtn' ),

                // 没选择文件之前的内容。
                $placeHolder = $wrap.find( '.placeholder' ),

                $progress = $statusBar.find( '.progress' ).hide(),

                // 添加的文件数量
                fileCount = 0,

                // 添加的文件总大小
                fileSize = 0,

                // 优化retina, 在retina下这个值是2
                ratio = window.devicePixelRatio || 1,

                // 缩略图大小
                thumbnailWidth = 110 * ratio,
                thumbnailHeight = 110 * ratio,

                // 可能有pedding, ready, uploading, confirm, done.
                state = 'pedding',

                // 所有文件的进度信息，key为file id
                percentages = {},
                // 判断浏览器是否支持图片的base64
                isSupportBase64 = ( function() {
                    var data = new Image();
                    var support = true;
                    data.onload = data.onerror = function() {
                        if( this.width != 1 || this.height != 1 ) {
                            support = false;
                        }
                    }
                    data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
                    return support;
                } )(),

                // 检测是否已经安装flash，检测flash的版本
                flashVersion = ( function() {
                    var version;

                    try {
                        version = navigator.plugins[ 'Shockwave Flash' ];
                        version = version.description;
                    } catch ( ex ) {
                        try {
                            version = new ActiveXObject('ShockwaveFlash.ShockwaveFlash')
                                .GetVariable('$version');
                        } catch ( ex2 ) {
                            version = '0.0';
                        }
                    }
                    version = version.match( /\d+/g );
                    return parseFloat( version[ 0 ] + '.' + version[ 1 ], 10 );
                } )(),

                supportTransition = (function(){
                    var s = document.createElement('p').style,
                        r = 'transition' in s ||
                            'WebkitTransition' in s ||
                            'MozTransition' in s ||
                            'msTransition' in s ||
                            'OTransition' in s;
                    s = null;
                    return r;
                })(),

                // WebUploader实例
                uploader;

            if ( !WebUploader.Uploader.support('flash') && WebUploader.browser.ie ) {

                // flash 安装了但是版本过低。
                if (flashVersion) {
                    (function(container) {
                        window['expressinstallcallback'] = function( state ) {
                            switch(state) {
                                case 'Download.Cancelled':
                                    layer.msg('Download canceled', {icon:2, time:2000});
                                    break;

                                case 'Download.Failed':
                                    layer.msg('Install failed', {icon:2, time:2000});
                                    break;

                                default:
                                    layer.msg('Install successfully', {icon:2, time:2000});
                                    break;
                            }
                            delete window['expressinstallcallback'];
                        };

                        var swf = 'expressInstall.swf';
                        // insert flash object
                        var html = '<object type="application/' +
                            'x-shockwave-flash" data="' +  swf + '" ';

                        if (WebUploader.browser.ie) {
                            html += 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ';
                        }

                        html += 'width="100%" height="100%" style="outline:0">'  +
                            '<param name="movie" value="' + swf + '" />' +
                            '<param name="wmode" value="transparent" />' +
                            '<param name="allowscriptaccess" value="always" />' +
                            '</object>';

                        container.html(html);

                    })($wrap);

                    // 压根就没有安装。
                } else {
                    $wrap.html('<a href="http://www.adobe.com/go/getflashplayer" target="_blank" border="0"><img alt="get flash player" src="http://www.adobe.com/macromedia/style_guide/images/160x41_Get_Flash_Player.jpg" /></a>');
                }

                return;
            } else if (!WebUploader.Uploader.support()) {
                layer.msg('Web Uploader Unsupported browser', {icon:2, time:2000});
                return;
            }

            // 实例化
            uploader = WebUploader.create({
                pick: {
                    id: '#filePicker-2',
                    label: 'Upload Image'
                },
                formData: {
                    uid: 123,
                    _token: '<?php echo e(csrf_token()); ?>',
                },
                dnd: '#dndArea',
                paste: '#uploader',
                swf: '<?php echo e(asset('public/root/lib/webuploader/0.1.5/Uploader.swf')); ?>',
                chunked: false,
                chunkSize: 512 * 1024,
                server: '<?php echo e(url('/admin/upload_many/image/many')); ?>',
                // runtimeOrder: 'flash',

                // accept: {
                //     title: 'Images',
                //     extensions: 'gif,jpg,jpeg,bmp,png',
                //     mimeTypes: 'image/*'
                // },

                // 禁掉全局的拖拽功能。这样不会出现图片拖进页面的时候，把图片打开。
                disableGlobalDnd: true,
                fileNumLimit: 300,
                fileSizeLimit: 200 * 1024 * 1024,    // 200 M
                fileSingleSizeLimit: 50 * 1024 * 1024    // 50 M
            });

            // 拖拽时不接受 js, txt 文件。
            uploader.on( 'dndAccept', function( items ) {
                var denied = false,
                    len = items.length,
                    i = 0,
                    // 修改js类型
                    unAllowed = 'text/plain;application/javascript ';

                for ( ; i < len; i++ ) {
                    // 如果在列表里面
                    if ( ~unAllowed.indexOf( items[ i ].type ) ) {
                        denied = true;
                        break;
                    }
                }

                return !denied;
            });

            uploader.on('dialogOpen', function() {
                console.log('here');
            });

            uploader.on( 'uploadSuccess', function( file, res ) {
                if(res){
                    var imgUnionPath = res._raw;
                    var preview = '/public/'+imgUnionPath.split('@')[0];
                    var html = '\
					<div class="uploaded_img">\
                        <input type="hidden" class="good_img" name="goods_images[]" value="'+imgUnionPath+'" />\
                        <img src="'+preview+'" width="90" height="90" />\
                        <img src="<?php echo e(asset('public/root/lib/uploadify-cancel.png')); ?>" class="delete_btn" onclick="deleteUploadedImg(this);" />\
					</div>';
                    $('#goods_images').append(html);
                }
            });

            // 添加“添加文件”的按钮，
            uploader.addButton({
                id: '#filePicker2',
                label: 'Continue to Add'
            });

            uploader.on('ready', function() {
                window.uploader = uploader;
            });

            // 当有文件添加进来时执行，负责view的创建
            function addFile( file ) {
                var $li = $( '<li id="' + file.id + '">' +
                        '<p class="title">' + file.name + '</p>' +
                        '<p class="imgWrap"></p>'+
                        '<p class="progress"><span></span></p>' +
                        '</li>' ),

                    $btns = $('<div class="file-panel">' +
                        '<span class="cancel">Del</span>' +
                        '<span class="rotateRight">Rotate Right</span>' +
                        '<span class="rotateLeft">Rotate Left</span></div>').appendTo( $li ),
                    $prgress = $li.find('p.progress span'),
                    $wrap = $li.find( 'p.imgWrap' ),
                    $info = $('<p class="error"></p>'),

                    showError = function( code ) {
                        switch( code ) {
                            case 'exceed_size':
                                text = 'File size exceeds';
                                break;

                            case 'interrupt':
                                text = 'Upload paused';
                                break;

                            default:
                                text = 'Upload failed';
                                break;
                        }

                        $info.text( text ).appendTo( $li );
                    };

                if ( file.getStatus() === 'invalid' ) {
                    showError( file.statusText );
                } else {
                    // @todo lazyload
                    $wrap.text( 'Previewing' );
                    uploader.makeThumb( file, function( error, src ) {
                        var img;

                        if ( error ) {
                            $wrap.text( 'Cannot Preview' );
                            return;
                        }

                        if( isSupportBase64 ) {
                            img = $('<img src="'+src+'">');
                            $wrap.empty().append( img );
                        } else {
                            $wrap.text("Preview error");
                        }
                    }, thumbnailWidth, thumbnailHeight );

                    percentages[ file.id ] = [ file.size, 0 ];
                    file.rotation = 0;
                }

                file.on('statuschange', function( cur, prev ) {
                    if ( prev === 'progress' ) {
                        $prgress.hide().width(0);
                    } else if ( prev === 'queued' ) {
                        $li.off( 'mouseenter mouseleave' );
                        $btns.remove();
                    }

                    // 成功
                    if ( cur === 'error' || cur === 'invalid' ) {
                        console.log( file.statusText );
                        showError( file.statusText );
                        percentages[ file.id ][ 1 ] = 1;
                    } else if ( cur === 'interrupt' ) {
                        showError( 'interrupt' );
                    } else if ( cur === 'queued' ) {
                        percentages[ file.id ][ 1 ] = 0;
                    } else if ( cur === 'progress' ) {
                        $info.remove();
                        $prgress.css('display', 'block');
                    } else if ( cur === 'complete' ) {
                        $li.append( '<span class="success"></span>' );
                    }

                    $li.removeClass( 'state-' + prev ).addClass( 'state-' + cur );
                });

                $li.on( 'mouseenter', function() {
                    $btns.stop().animate({height: 30});
                });

                $li.on( 'mouseleave', function() {
                    $btns.stop().animate({height: 0});
                });

                $btns.on( 'click', 'span', function() {
                    var index = $(this).index(),
                        deg;

                    switch ( index ) {
                        case 0:
                            uploader.removeFile( file );
                            return;

                        case 1:
                            file.rotation += 90;
                            break;

                        case 2:
                            file.rotation -= 90;
                            break;
                    }

                    if ( supportTransition ) {
                        deg = 'rotate(' + file.rotation + 'deg)';
                        $wrap.css({
                            '-webkit-transform': deg,
                            '-mos-transform': deg,
                            '-o-transform': deg,
                            'transform': deg
                        });
                    } else {
                        $wrap.css( 'filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ (~~((file.rotation/90)%4 + 4)%4) +')');
                    }


                });

                $li.appendTo( $queue );
            }

            // 负责view的销毁
            function removeFile( file ) {
                var $li = $('#'+file.id);

                delete percentages[ file.id ];
                updateTotalProgress();
                $li.off().find('.file-panel').off().end().remove();
            }

            function updateTotalProgress() {
                var loaded = 0,
                    total = 0,
                    spans = $progress.children(),
                    percent;

                $.each( percentages, function( k, v ) {
                    total += v[ 0 ];
                    loaded += v[ 0 ] * v[ 1 ];
                } );

                percent = total ? loaded / total : 0;


                spans.eq( 0 ).text( Math.round( percent * 100 ) + '%' );
                spans.eq( 1 ).css( 'width', Math.round( percent * 100 ) + '%' );
                updateStatus();
            }

            function updateStatus() {
                var text = '', stats;

                if ( state === 'ready' ) {
                    text = 'Selected' + fileCount + '，Total' +
                        WebUploader.formatSize( fileSize ) + '。';
                } else if ( state === 'confirm' ) {
                    stats = uploader.getStats();
                    if ( stats.uploadFailNum ) {
                        text = 'Success' + stats.successNum+ ' '+
                            stats.uploadFailNum + 'Failed，<a class="retry" href="#">Re-upload</a>Failed<a class="ignore" href="#">Skip</a>'
                    }

                } else {
                    stats = uploader.getStats();
                    text = 'Total' + fileCount + ' ' +
                        WebUploader.formatSize( fileSize )  +
                        '，Success' + stats.successNum + ' ';

                    if ( stats.uploadFailNum ) {
                        text += '，failed' + stats.uploadFailNum;
                    }
                }

                $info.html( text );
            }

            function setState( val ) {
                var file, stats;

                if ( val === state ) {
                    return;
                }

                $upload.removeClass( 'state-' + state );
                $upload.addClass( 'state-' + val );
                state = val;

                switch ( state ) {
                    case 'pedding':
                        $placeHolder.removeClass( 'element-invisible' );
                        $queue.hide();
                        $statusBar.addClass( 'element-invisible' );
                        uploader.refresh();
                        break;

                    case 'ready':
                        $placeHolder.addClass( 'element-invisible' );
                        $( '#filePicker2' ).removeClass( 'element-invisible');
                        $queue.show();
                        $statusBar.removeClass('element-invisible');
                        uploader.refresh();
                        break;

                    case 'uploading':
                        $( '#filePicker2' ).addClass( 'element-invisible' );
                        $progress.show();
                        $upload.text( 'Upload Paused' );
                        break;

                    case 'paused':
                        $progress.show();
                        $upload.text( '继续上传' );
                        break;

                    case 'confirm':
                        $progress.hide();
                        $( '#filePicker2' ).removeClass( 'element-invisible' );
                        $upload.text( 'Continue to upload' );

                        stats = uploader.getStats();
                        if ( stats.successNum && !stats.uploadFailNum ) {
                            setState( 'finish' );
                            return;
                        }
                        break;
                    case 'finish':
                        stats = uploader.getStats();
                        if ( stats.successNum ) {
                            layer.msg('Upload successfully', {icon:1, time:2000});
                        } else {
                            // 没有成功的图片，重设
                            state = 'done';
                            //location.reload();
                        }
                        break;
                }

                updateStatus();
            }

            uploader.onUploadProgress = function( file, percentage ) {
                var $li = $('#'+file.id),
                    $percent = $li.find('.progress span');

                $percent.css( 'width', percentage * 100 + '%' );
                percentages[ file.id ][ 1 ] = percentage;
                updateTotalProgress();
            };

            uploader.onFileQueued = function( file ) {
                fileCount++;
                fileSize += file.size;

                if ( fileCount === 1 ) {
                    $placeHolder.addClass( 'element-invisible' );
                    $statusBar.show();
                }

                addFile( file );
                setState( 'ready' );
                updateTotalProgress();
            };

            uploader.onFileDequeued = function( file ) {
                fileCount--;
                fileSize -= file.size;

                if ( !fileCount ) {
                    setState( 'pedding' );
                }

                removeFile( file );
                updateTotalProgress();

            };

            uploader.on( 'all', function( type ) {
                var stats;
                switch( type ) {
                    case 'uploadFinished':
                        setState( 'confirm' );
                        break;

                    case 'startUpload':
                        setState( 'uploading' );
                        break;

                    case 'stopUpload':
                        setState( 'paused' );
                        break;

                }
            });

            uploader.onError = function( code ) {
                layer.msg('Eroor: ' + code, {icon:2, time:2000});
            };

            $upload.on('click', function() {
                if ( $(this).hasClass( 'disabled' ) ) {
                    return false;
                }

                if ( state === 'ready' ) {
                    uploader.upload();
                } else if ( state === 'paused' ) {
                    uploader.upload();
                } else if ( state === 'uploading' ) {
                    uploader.stop();
                }
            });

            $info.on( 'click', '.retry', function() {
                uploader.retry();
            } );

            $info.on( 'click', '.ignore', function() {
                layer.msg('todo', {icon:2, time:2000});
            } );

            $upload.addClass( 'state-' + state );
            updateTotalProgress();
        });

    })( jQuery );

    //删除图片
    function deleteUploadedImg(obj){
        layer.confirm('Are you sure to delete it?', {
            btn : ['Yes', 'No']
        }, function(){
            var url = $(obj).parent().find('.good_img').val();
            $.post('<?php echo e(url('/admin/delete_image')); ?>', {'_token':'<?php echo e(csrf_token()); ?>','url':url}, function(data){
                if(data.status == '0'){
                    layer.msg(data.msg, {icon:1, time:2000});
                }else{
                    layer.msg(data.msg, {icon:2, time:2000});
                }
                $(obj).parent().remove();
            });
        }, function(){
            layer.closeAll();
        });
    }

    //修改路径
    $(window).load(function(){
        setTimeout(function(){
            $('#tab-category .tabBar span').removeClass('current');
            $('#tab-category .tabBar span').eq(0).addClass('current');
            $('#tab-category .tabCon').hide();
            $('#tab-category .tabCon').eq(0).show();
        }, 500);
    });
</script>
@parent
@endsection