<?php

namespace App\Http\Controllers\Admin;

use App\Tool\Code\Code;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class LoginController extends CommonController
{
    public function login(){
        $input = Input::only('username', 'password', 'code', '_token');
        
        if($input['_token']){
            $rules = [
                'username' => 'required',//requires => 必填
                'password' => 'required',
                'code' => 'required',
            ];

            $msg = [
                'username.required' => 'Please input username',
                'password.required' => 'Please input password',
                'code.required' => 'Please input code',
            ];

            $validator = Validator::make($input, $rules, $msg);

            if($validator -> passes()){
                $codeObject = new Code();
                $code = $codeObject -> get();//获取已被储存的验证码

                if(strtoupper($input['code']) != $code){
                    return back() -> with([
                        'error' => 'Code error',
                        'username' => $input['username'],
                    ]);
                }

                $username = $input['username'];
                //$admin = Admin::where('username', $username) -> first();
                $admin = DB::table('admins') -> where('username', $username) -> first();

                if(!$admin){
                    return back() -> with([
                        'error' => 'User not exist',
                    ]);
                }else{
                    $password = $input['password'];

                    if($password != Crypt::decrypt($admin->password)){
                        return back() -> with([
                            'error' => 'password error',
                            'username' => $input['username'],
                        ]);
                    }else{
                        session(['admin' => $admin]);

                        $weixin = DB::table('weixins') -> first();
                        session(['weixin' => $weixin]);
						
						Cache::add('config', $weixin, 525600);

                        return redirect('/admin');
                    }
                }
            }else{
                return back() -> withErrors($validator) -> with(['username' => $input['username']]);
            }
        }else{
            return view('admin.login');
            /*
            if(session('admin')){
                return redirect('admin');
            }else{
                return view('admin.login');
            }
            */
        }
    }

    public function logout(){
        session(['admin' => null]);
        session(['weixin' => null]);
        return redirect('admin/login');
    }

    public function code(){
        $code = new Code;
        $code -> make();
    }
}
