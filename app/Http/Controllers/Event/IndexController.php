<?php

namespace App\Http\Controllers\Event;

use Illuminate\Http\Request;
use App\Tool\Transfer\Transfer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Overtrue\Wechat\Notice;

use App\Tool\Weshare\Jssdk;

class IndexController extends CommonController
{
    public function signed()
    {
        $perpage = 20;

        $mid = session('mid');

        $signed = DB::table('signs') -> where('member_id', $mid) -> orderBy('id', 'DESC') -> limit($perpage) -> get();

        $member = DB::table('members') -> select('coin') -> where('id', $mid) -> first();

        return view('event.signed', compact('signed', 'member'));
    }

    public function getSigned()
    {
        $mid = session('mid');

        $input = Input::only('page');

        $exists = 20;
        $perpage = 20;

        $offset = $input['page'] * $perpage + $exists;

        $signed = DB::table('signs') -> where('member_id', $mid) -> orderBy('id', 'DESC') -> offset($offset) -> limit($perpage) -> get();

        return $signed;
    }

    public function yesterday()
    {
        /*
        if($this->checkVisitor() == 'redirect'){
            return redirect('/events');
        }elseif($this->checkVisitor() == 'error'){
            echo '<meta charset="UTF-8" /><h2 style="font-size:48px;text-align:center;">请在公众号中打开链接。</h2><img src="'.url('public/home/img/office_qr.jpg').'" style="width:70%;display:block;margin:0 auto;" />';
            die;
        }
        */

        $begin = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
        $end = mktime(0,0,0,date('m'),date('d'),date('Y'))-1;

        $perpage = 20;

        $yesterday = DB::table('signs') -> select(['signs.*', 'members.portrait', 'members.nickname']) -> where('signs.coin', '!=', '0.00') -> whereBetween('signs.sign_time', [$begin, $end]) -> leftJoin('members', 'signs.member_id', '=', 'members.id') -> orderBy('signs.id', 'DESC') -> limit($perpage) -> get();

        $total_signed = DB::table('signs') -> where('signs.coin', '!=', '0.00') -> whereBetween('sign_time', [$begin, $end]) -> count();

        $total_coin = DB::table('signs') -> where('signs.coin', '!=', '0.00') -> whereBetween('sign_time', [$begin, $end]) -> sum('coin');

        return view('event.yesterday', compact('yesterday', 'total_signed', 'total_coin'));
    }

    public function getYesterday()
    {
        $begin = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
        $end = mktime(0,0,0,date('m'),date('d'),date('Y'))-1;

        $input = Input::only('page');

        $exists = 20;
        $perpage = 20;

        $offset = $input['page'] * $perpage + $exists;

        $yesterday = DB::table('signs') -> select(['signs.*', 'members.portrait', 'members.nickname']) -> where('signs.coin', '!=', '0.00') -> whereBetween('signs.sign_time', [$begin, $end]) -> leftJoin('members', 'signs.member_id', '=', 'members.id') -> orderBy('signs.id', 'DESC') -> offset($offset) -> limit($perpage) -> get();

        return $yesterday;
    }

    public function signEnter()
    {
        return view('event.sign_enter');
    }

    public function signView()
    {
        /*
        if($this->checkVisitor() == 'redirect'){
            return redirect('/sign');
        }elseif($this->checkVisitor() == 'error'){
            echo '<meta charset="UTF-8" /><h2 style="font-size:48px;text-align:center;">请在公众号中打开链接。</h2><img src="'.url('public/home/img/office_qr.jpg').'" style="width:70%;display:block;margin:0 auto;" />';
            die;
        }
        */

        //$signBanner = DB::table('banners') -> where('end_time','>',time()) -> where('position','sign') -> where('is_use',1) -> orderByRaw('RAND()') -> first();

        $mid = session('mid');
        $todayStart = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $todayEnd = mktime(23,59,59,date('m'),date('d'),date('Y'));

        $sign = DB::table('signs') -> where('member_id', $mid) -> whereBetween('sign_time', [$todayStart, $todayEnd]) -> first();

        $signStart = explode('~', $this->SYSTEM['sign_time'])[0];
        $signEnd = explode('~', $this->SYSTEM['sign_time'])[1];

        $signTime = substr_replace($this->SYSTEM['sign_time'], ':', 7, 0);
        $signTime = substr_replace($signTime, ':', 2, 0);

        $ads = DB::table('ads')->orderBy('sort', 'DESC')->orderBy('id', 'DESC')->get();

        $goodsData = DB::table('goods')->select('goods.id','goods.name','goods.other_name','goods.image','goods.price','goods.org_price','goods.weight','goods.limit_num','goods.stock', 'goods.special','brands.id as brand_id','brands.name as brand_name','brands.image as brand_image') -> leftJoin('brands','goods.brand_id','=','brands.id')->where(['goods.is_use'=>'1','goods.is_rec'=>'1','goods.fiveday'=>'0'])->where('goods.stock', '>', '0')->orderBy('goods.sort','DESC')->orderBy('goods.id','DESC')->get();

        return view('event.sign', compact('sign', 'signStart', 'signEnd', 'signTime', 'ads', 'goodsData'));
    }

    public function sign()
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = '请登录';
            return $data;
        }

        $todayStart = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $todayEnd = mktime(23,59,59,date('m'),date('d'),date('Y'));

        $sign = DB::table('signs') -> where('member_id', $mid) -> whereBetween('sign_time', [$todayStart, $todayEnd]) -> count();

        if($sign){
            $data['status'] = 1;
            $data['msg'] = '今日已签到';
            return $data;
        }

        $insert = array();

        $insert['member_id'] = $mid;
        $insert['sign_time'] = time();
        $insert['coin'] = '0.00';

        $time = date('Hi');
        $signStart = explode('~', $this->SYSTEM['sign_time'])[0];
        $signEnd = explode('~', $this->SYSTEM['sign_time'])[1];

        if($time >= $signStart && $time <= $signEnd){
            $coinMin = explode('~', $this->SYSTEM['sign_coin'])[0];
            $coinMax = explode('~', $this->SYSTEM['sign_coin'])[1];

            $insert['coin'] = randomFloat($coinMin, $coinMax);
        }

        $result = DB::table('signs') -> insert($insert);

        if($result){
            $data['coin'] = '<strong>无奖金</strong>';
            $data['_coin'] = '￥0.00';

            if($insert['coin'] != '0.00'){
                DB::table('members') -> where('id', $mid) -> increment('coin', $insert['coin']);
                $data['coin'] = $insert['coin'];
                $data['_coin'] = '￥'.$insert['coin'];

                //发送模板消息
                $notice = new Notice(Config::get('web.WE_APPID'), Config::get('web.WE_SECRET'));
                $templateId = 'U_Ndv5kPJHij874i-7w0HNf5UtZTFzcnFt4WIx6LiEc';//通过addTemplate方法获取
                //$url = url('transfer');
                $member = DB::table('members') -> select(['openid', 'coin']) -> where('id', $mid) -> first();
                $url = url('signed');

                $datas = array(
                    "first"    => array('签到成功~', '#333333'),
                    "keyword1"   => array(date('Y-m-d H:i', $insert['sign_time']), "#333333"),
                    "keyword2"   => array($insert['coin'], "#333333"),
                    "keyword3"   => array($member->coin, "#333333"),
                    "remark"   => array("每日坚持签到可获得更多金币，可直接提现到微信零钱噢~", "#333333"),
                );

                $notice->uses($templateId)->withUrl($url)->andData($datas)->andReceiver($member->openid)->send();
            }

            $data['status'] = 0;
            $data['msg'] = '签到成功';
        }else{
            $data['status'] = 2;
            $data['msg'] = '操作错误，请稍后再试';
        }

        return $data;
    }

    public function transferView()
    {
        if($this->checkVisitor() == 'redirect'){
            return redirect('/transfer');
        }elseif($this->checkVisitor() == 'error'){
            echo '<meta charset="UTF-8" /><h2 style="font-size:48px;text-align:center;">请在公众号中打开链接。</h2><img src="'.url('public/home/img/office_qr.jpg').'" style="width:70%;display:block;margin:0 auto;" />';
            die;
        }

        $mid = session('mid');

        $member = DB::table('members') -> select('coin') -> where('id', $mid) -> first();

        return view('event.transfer', compact('member'));
    }

    public function transfer(Request $request)
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = '请登录';
            return $data;
        }

        $transferMin = $this->SYSTEM['transfer_min'];
        $transferMax = $this->SYSTEM['transfer_max'];

        $member = DB::table('members') -> select('openid', 'coin') -> where('id', $mid) -> first();

        if($member->coin < $transferMin){
            $data['status'] = 1;
            $data['msg'] = '最小提现金额不能少于 '.$transferMin.' 元';
            return $data;
        }

        $coin = $member->coin < $transferMax ? $member->coin : $transferMax;
        $ip = $request->getClientIp();

        $transfer = new Transfer(Config::get('web.PUBLIC_APPID'), Config::get('web.MCHID'), Config::get('web.KEY'), $ip);

        $desc = '4989西市场提现';

        $res = $transfer->sendMoney($coin, $member->openid, $desc);

        if($res['return_code'] != 'SUCCESS'){
            $data['status'] = 2;
            $data['msg'] = '提现失败，请稍候再试';
            return $data;
        }

        if($res['result_code'] != 'SUCCESS'){
            $data['status'] = 3;
            $data['msg'] = $res['err_code_des'];
            return $data;
        }

        DB::table('members') -> where('id', $mid) -> decrement('coin', $coin);

        $insert = array();
        $insert['sn'] = $res['partner_trade_no'];
        $insert['member_id'] = $mid;
        $insert['coin'] = $coin;
        $insert['addtime'] = time();

        DB::table('withdraws') -> insert($insert);

        $data['status'] = 0;
        $data['msg'] = '提现成功';

        return $data;
    }

    public function userUpload($path){
        $_file = Input::file();
        $file = array_shift($_file);

        if($file -> isValid()){
            $filename = date('YmdHis').mt_rand(1000, 9999).'.'.$file->getClientOriginalExtension();

            $savepath = public_path().'/upload/user/'.$path;

            $file->move($savepath, $filename);

            $filePath = 'upload/user/'.$path.'/'.$filename;

            return $filePath;
        }
    }

    public function userDelete(){
        $oldfile = Input::only('url');
        $result = @unlink(public_path().'/'.$oldfile['url']);

        if($result){
            $data = [
                'status' => 0,
                'msg' => '原图片已删除',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '原图片删除失败',
            ];
        }

        return $data;
    }

    public function fiveday()
    {
        if (is_fiveday()) {
            $goodsData = DB::table('goods') -> where(['is_use'=>'1','fiveday'=>'1']) -> orderBy('stock', 'DESC') -> orderBy('sort','DESC') -> orderBy('id','DESC') -> get();

            return view('event.fiveday', compact('goodsData'));
        } else {
            $time = time();
            $bg = DB::table('banners') -> where('end_time','>',$time) -> where('position','fiveday_off') -> where('is_use','1') -> orderBy('sort', 'DESC') -> orderBy('id', 'DESC') -> first();

            return view('event.fiveday_off', compact('bg'));
        }
    }

    public function article($id)
    {
        $data = DB::table('articles')->find($id);

        return view('event.article', compact('data'));
    }

    public function articleContent($id)
    {
        $data = DB::table('articles')->select(['content'])->find($id);

        return response()->json(['content'=>$data->content]);
    }
}
