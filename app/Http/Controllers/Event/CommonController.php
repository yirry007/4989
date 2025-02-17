<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\BaseController;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class CommonController extends BaseController
{
    private $key;
    protected $SYSTEM = array();

    public function __construct(Request $request){
        $this->PCPrevent();

        parent::__construct($request);

        $this->key = 'xishichang4989';
    }

    protected function checkVisitor()
    {
        $check = session('visitor');
        $input = Input::only('visitor');
        $key = md5($this->key);

        if(!$input['visitor']){
            if($check != $key){
                return 'error';
            }
            return 'success';
        }else{
            if($input['visitor'] != $key){
                return 'error';
            }else{
                session(['visitor'=>$input['visitor']]);
                return 'redirect';
            }
        }
    }
}
