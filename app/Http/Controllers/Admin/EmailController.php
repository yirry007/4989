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

class EmailController extends CommonController
{
    public function index()
    {
        $email = DB::table('email') -> first();

        return view('admin.email.index', compact('email'));
    }

    public function edit()
    {
        $input = Input::only('_token','email');

        DB::table('email') -> update(['email'=>$input['email']]);

        return redirect('admin/email');
    }
}
