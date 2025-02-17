<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;

use Overtrue\Wechat\Auth;
use App\Tool\Weshare\Jssdk;

class BaseController extends Controller
{	
    protected $SYSTEM = array();

    public function __construct(Request $request){
        //先把会员来源的合作者code保存到session里，然后登录时把这个session写入到数据库
        if(!session('partner_code')){
            $input = Input::only('partner_code');
            if($input['partner_code']){
                session(['partner_code'=>$input['partner_code']]);
            }else{
                $partner = DB::table('partners') -> select('code') -> orderBy('id', 'ASC') -> first();
                session(['partner_code'=>$partner->code]);
            }
        }

        $memberData = DB::table('members') -> where('id', '50') -> first();
        session(['mid'=>$memberData->id, 'portrait'=>$memberData->portrait, 'nickname'=>$memberData->nickname]);
        
        $system = Cache::get('system');
        if(!$system){
            $system = DB::table('systems') -> select(['sys_key', 'sys_value']) -> get();
            Cache::add('system', $system, 1);
        }

        $_SYSTEM = array();
        foreach($system as $v){
            $_SYSTEM[$v->sys_key] = $v->sys_value;
        }
        $this -> SYSTEM = $_SYSTEM;

        //微信分享
//        $jssdk = new Jssdk(Config::get('web.WE_APPID'), Config::get('web.WE_SECRET'));
//        $signPackage = $jssdk->GetSignPackage();

        $signPackage = 0;

        View::share(array(
            '_SYSTEM' => $_SYSTEM,
            'signPackage' => $signPackage,
        ));
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

        return back();
    }

    protected function PCPrevent()
    {
//        if(!isWeixin() && !in_array('we_notify', explode('/', Request::path())) && !in_array('topup_notify', explode('/', Request::path()))){
//            echo '<meta charset="UTF-8" /><style>*{margin:0;padding:0;}body{background:#f0f0f0;}img{display:block;margin:0 auto;}</style><img src="'.url('public/home/img/pc_view.jpg').'" />';
//            die;
//        }
    }
}
