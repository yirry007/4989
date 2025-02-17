<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class SystemController extends CommonController
{
    public function system(){
        $systemData1 = DB::table('systems') -> where('groups', 1) ->  get();
        $systemData2 = DB::table('systems') -> where('groups', 2) -> get();

        return view('admin.system.system', compact('systemData1', 'systemData2'));
    }

    public function update(){
        $input = Input::except('_token');

        foreach($input as $k=>$v){
            if ($k == 'fiveday_on' || $k == 'fiveday_begin') {
                $v = strtotime($v.' 00:00:00');
            }

            DB::table('systems') -> where('sys_key', $k) -> update(['sys_value'=>$v]);
        }
        return redirect('/admin/system');
    }

    public function env(){
        return view('admin.system.env');
    }

    public function refresh(){
        $input = Input::except('_token');
        $path = base_path().'\config\web.php';
        $config = '<?php return '.var_export($input, true).';';
        file_put_contents($path, $config);

        return redirect('/admin/env');
    }
}