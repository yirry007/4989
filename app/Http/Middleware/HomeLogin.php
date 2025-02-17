<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Overtrue\Wechat\Auth;
use Illuminate\Support\Facades\DB;

class HomeLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!session('mid')){
            $this->login();
        }

        return $next($request);
    }

    public function login()
    {
        $auth = new Auth(Config::get('web.WE_APPID'), Config::get('web.WE_SECRET'));
        $user = $auth->authorize();

        $memberData = DB::table('members') -> where('openid', $user->openid) -> first();
        if(!$memberData){
            $insertData = array();
            $insertData['openid'] = $user->openid;
            $insertData['portrait'] = $user->headimgurl;
            $insertData['nickname'] = $user->nickname;
            $insertData['addtime'] = time();
            $insertData['partner_code'] = session('partner_code');

            $id = DB::table('members') -> insertGetId($insertData);

            session(['mid'=>$id, 'portrait'=>$user->headimgurl, 'nickname'=>$user->nickname]);
        }else{
            DB::table('members') -> where('openid', $user->openid) -> update(['portrait'=>$user->headimgurl, 'nickname'=>$user->nickname]);

            session(['mid'=>$memberData->id, 'portrait'=>$memberData->portrait, 'nickname'=>$memberData->nickname]);
        }
    }
}
