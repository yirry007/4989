<?php

namespace App\Http\Controllers\Weixin;

use App\Tool\Code\Code;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(){
        $input = Input::only('username', 'password', 'code', '_token');

        if($input['_token']){
            $rules = [
                'username' => 'required',
                'password' => 'required',
                'code' => 'required',
            ];

            $msg = [
                'username.required' => '请输入用户名',
                'password.required' => '请输入密码',
                'code.required' => '请输入验证码',
            ];

            $validator = Validator::make($input, $rules, $msg);

            if($validator -> passes()){
                $codeObject = new Code;
                $code = $codeObject -> get();

                if(strtoupper($input['code']) != $code){
                    return back() -> with([
                        'error' => '验证码错误',
                        'username' => $input['username'],
                    ]);
                }

                $username = $input['username'];
                $weixin = DB::table('weixins') -> where('username', $username) -> first();

                if(!$weixin){
                    return back() -> with([
                        'error' => '用户不存在',
                    ]);
                }else{
                    $password = $input['password'];

                    if($password != Crypt::decrypt($weixin->password)){
                        return back() -> with([
                            'error' => '密码不正确',
                            'username' => $input['username'],
                        ]);
                    }else{
                        session(['weixin' => $weixin]);

                        $admin = DB::table('admin') -> first();
                        session(['admin' => $admin]);
						
						Cache::add('config', $weixin, 63072000);

                        return redirect('/weixin');
                    }
                }
            }else{
                return back() -> withErrors($validator) -> with(['username' => $input['username']]);
            }
        }else{
            if(session('weixin')){
                return redirect('weixin');
            }else{
                return view('we.login');
            }
        }
    }

    public function logout(){
        session(['admin' => null]);
        session(['weixin' => null]);
        return redirect('weixin/login');
    }

    public function password()
    {
        return view('we.password');
    }

    public function passwordSet()
    {
        $input = Input::only('password_o', 'password', 'password_c');

        $rules = [
            'password_o' => 'required',
            'password' => 'required',
            'password_c' => 'required',
        ];

        $msg = [
            'password_o.required' => '请输入原密码',
            'password.required' => '请输入新密码',
            'password_c.required' => '请输入确认密码',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $data = array();

            if(strlen($input['password']) < 6 || strlen($input['password']) > 20){
                $data['error'] = '请输入6-20位的密码';
                return back() -> with($data);
            }

            if($input['password'] != $input['password_c']){
                $data['error'] = '两次密码输入不一致';
                return back() -> with($data);
            }

            $weixin = DB::table('weixins') -> where('id', session('weixin')->id) -> first();

            if($input['password_o'] != Crypt::decrypt($weixin->password)){
                $data['error'] = '原密码不正确';
                return back() -> with($data);
            }

            $newPassword = Crypt::encrypt($input['password']);
            $result = DB::table('weixins') -> update(['password'=>$newPassword]);

            if($result !== false){
                return redirect('weixin/main');
            }else{
                $data['error'] = '服务器异常，请稍候再试';
                return back() -> with($data);
            }

        }else{
            return back() -> withErrors($validator);
        }
    }

    public function code(){
        $code = new Code();
        $code -> make();
    }
}
