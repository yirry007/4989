@extends('admin.layout')
@section('content')
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> Main <span class="c-gray en">&gt;</span> Email <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="Refresh" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="pd-20">
        <form action="<?php echo e(url('/admin/email_edit')); ?>" method="post" class="form form-horizontal">
            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
            <div class="row cl">
                <label class="form-label col-3">Email Address：</label>
                <div class="formControls col-6">
                    <textarea name="email" cols="" rows="" class="textarea"  placeholder="Email Address" onKeyUp="textarealength(this,1000)"><?php echo e($email->email ? $email->email : ''); ?></textarea>
                    <p class="textarea-numberbar"><em class="textarea-length">0</em>/1000</p>
                </div>
                <div class="col-3"> </div>
            </div>
            <div class="row cl" style="margin: 5px 0 0 0;">
                <label class="form-label col-3"></label>
                <div class="formControls col-6" style="color: #f8658f;">Please use the&symbol to separate multiple email addresses</div>
            </div>
            <div class="row cl">
                <div class="col-offset-5">
                    <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;Submit&nbsp;&nbsp;">
                </div>
            </div>
        </form>
    </div>
    @parent
@endsection