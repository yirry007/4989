<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Role;
use App\Tool\Page\Page;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ExpressController extends CommonController
{
    public function index()
    {
        $express = DB::table('expresses') -> get();

        return view('admin.express.index', compact('express'));
    }

    public function edit()
    {
        $input = Input::only('_token','default_price','base_weight','pre_weight_price','days','is_local');

        foreach($input['default_price'] as $k=>$v){
            if(!$v == ''){DB::table('expresses') -> where('province',$k) -> update(['default_price'=>$v]);}
        }
        foreach($input['base_weight'] as $k=>$v){
            if(!$v == ''){DB::table('expresses') -> where('province',$k) -> update(['base_weight'=>$v]);}
        }
        foreach($input['pre_weight_price'] as $k=>$v){
            if(!$v == ''){DB::table('expresses') -> where('province',$k) -> update(['pre_weight_price'=>$v]);}
        }
		foreach($input['days'] as $k=>$v){
            if(!$v){
				$v = '0';
			}
			DB::table('expresses') -> where('province',$k) -> update(['days'=>$v]);
        }
		foreach($input['is_local'] as $k=>$v){
            if(!$v == ''){DB::table('expresses') -> where('province',$k) -> update(['is_local'=>$v]);}
        }

        return redirect('admin/express');
    }
}
