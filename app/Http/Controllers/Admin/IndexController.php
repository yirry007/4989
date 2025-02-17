<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminLog;
use App\Models\Member;
use App\Models\Order;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class IndexController extends CommonController
{
    public function index(){
        return view('admin.index');
    }

    public function main(){
        $mysqlVersion = DB::select('SELECT VERSION() AS version');

        return view('admin.main', compact('mysqlVersion'));
    }

    public function deleteCache(){
        Cache::flush();
        return array('status'=>0, 'msg'=>'Cache has been cleared');
    }
}
