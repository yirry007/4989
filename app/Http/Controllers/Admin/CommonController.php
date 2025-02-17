<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Tool\Page\Page;
use App\Tool\Image\Image;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    public function uploadOne($path, $width=0, $height=0){
        $file = Input::file('Filedata');

        if($file -> isValid()){
            $filename = date('YmdHis').mt_rand(1000, 9999).'.'.$file->getClientOriginalExtension();

            if($path == 'goods' || $path == 'videos' || $path == 'poster'){
                $path .= '/'.date('Y-m');
            }

            $savepath = public_path().'/upload/admin/'.$path;

            $file->move($savepath, $filename);

            if($width && $height){
                $image = new Image($savepath.'/'.$filename);
                $image -> resize($width, $height);
                $thumb_image = 'hk_'.$filename;
                $image -> save($savepath.'/'.$thumb_image,70);
                @unlink($savepath.'/'.$filename);

                $filePath = 'upload/admin/'.$path.'/'.$thumb_image;

                return $filePath;
            }else{
                $filePath = 'upload/admin/'.$path.'/'.$filename;

                return $filePath;
            }
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

    /**
     * Resize the goods logo (600x600, 285*285, 90x90), and upload them.
     *
     * @return url of the image
     */
    public function uploadMany($dir, $fileNum='one'){
        set_time_limit(0);

        if($fileNum == 'one'){
            $file = Input::file('Filedata');
        }elseif($fileNum == 'many'){
            $file = Input::file('file');
        }

        if($file -> isValid()){
            $filename = date('YmdHis').mt_rand(1000, 9999).'.'.$file->getClientOriginalExtension();

            $dir .= '/'.date('Y-m');

            $savepath = public_path().'/upload/admin/good/'.$dir.'/';

            $file->move($savepath, $filename);

            $image = new Image($savepath.'/'.$filename);
            $image -> resize(600, 600);
            $big_logo = 'big_'.$filename;
            $image -> save($savepath.'/'.$big_logo,70);
            $image -> resize(300, 300);
            $sm_logo = 'sm_'.$filename;
            $image -> save($savepath.'/'.$sm_logo,70);

            $fileUnionPath = 'upload/admin/good/'.$dir.'/'.$sm_logo.'@'.'upload/admin/good/'.$dir.'/'.$big_logo.'@'.'upload/admin/good/'.$dir.'/'.$filename;

            return $fileUnionPath;
        }
    }

    public function deleteImage(){
        $file = Input::only('url');
        $imgArr = explode('@', $file['url']);

        $result = 1;

        foreach($imgArr as $v){
            if(!@unlink(public_path().'/'.$v)){
                $result = 0;
                break;
            }
        }

        if($result){
            $data = [
                'status' => 0,
                'msg' => 'Delete image successfully.',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => 'Delete image failed.',
            ];
        }

        return $data;
    }
}
