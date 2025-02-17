<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\BaseController;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class CommonController extends BaseController
{
    public function __construct(Request $request){
        $this->PCPrevent();

        parent::__construct($request);
    }

    public function uploadOne($path){
        $file = Input::file('Filedata');

        if($file -> isValid()){
            $filename = date('YmdHis').mt_rand(1000, 9999).'.'.$file->getClientOriginalExtension();

            $savepath = public_path().'/upload/admin/'.$path;

            $file->move($savepath, $filename);

            $filePath = 'upload/admin/'.$path.'/'.$filename;

            return $filePath;
        }
    }

    public function deleteOne(){
        $oldfile = Input::only('url');
        $result = @unlink(public_path().'/'.$oldfile['url']);//unlink -> php 文件删除

        if($result){
            $data = [
                'status' => 0,
                'msg' => 'Original image has been deleted.',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => 'Original image cannot be deleted.',
            ];
        }

        return $data;
    }
}
