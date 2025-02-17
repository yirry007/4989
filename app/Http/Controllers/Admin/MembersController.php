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
use Illuminate\Support\Facades\Config;
use Overtrue\Wechat\Notice;

class MembersController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $input = Input::only('money','amount');
        $search = Input::only('group_id', 'name');

        $order = 'id DESC';
        if($input['money'] == 1) $order = 'la_members.money DESC';
        if($input['money'] == 2) $order = 'la_members.money ASC';
        if($input['amount'] == 1) $order = 'la_members.amount DESC';
        if($input['amount'] == 2) $order = 'la_members.amount ASC';

        $perpage = 20;//每页显示记录数------`

        $dataCount = DB::table('members') -> where(function($query) use($search){
            if($search['group_id'] && $search['group_id'] != '-1'){
                $query -> where('group_id', $search['group_id']);
            }
            if($search['name']){
                $query -> where('nickname', 'like', '%'.$search['name'].'%');
            }
        }) -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $memberData = DB::table('members') -> select(['members.*', 'member_groups.name', 'partners.name as partner_name']) -> where(function($query) use($search){
            if($search['group_id'] && $search['group_id'] != '-1'){
                $query -> where('group_id', $search['group_id']);
            }
            if($search['name']){
                $query -> where('members.nickname', 'like', '%'.$search['name'].'%');
            }
        }) -> leftJoin('member_groups', 'members.group_id', '=', 'member_groups.id') -> leftJoin('partners', 'members.partner_code', '=', 'partners.code') -> offset($page->getOffset()) -> orderByRaw($order) -> orderBy('members.id','DESC') -> limit($perpage) -> get();

        $groupData = DB::table('member_groups') -> get();


        return view('admin.members.index', compact('memberData', 'dataCount', 'pageShow', 'groupData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.members.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::only('openid','portrait','nickname','money','amount','addtime');
        /*
         * Models -> creste      Input::except('_token');
         * DB -> insert    Input::only('title','image','background_img')
         * */

        $validator = Validator::make($input);

        if($validator -> passes()){
//            $input['password'] = Crypt::encrypt($input['password']);
//  添加时间(不需要就隐藏)          $input['addtime'] = time();

            $result = DB::table('members') -> insert($input);//$input 数据插入到数据库
            /*
             * Models -> creste(可能)
             * DB -> insert
             * */
            if($result){
                return redirect('/admin/members');
            }else{
                return back() -> with(['error'=>'Add data failed']);
            }
        }else{
            return back() -> withErrors($validator) -> with(['input'=>$input]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $field = DB::table('members')->where('id', $id)->first();

        if(!$field){
            return redirect('/admin/members');
        }

        return view('admin.members.edit', compact('field'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $input = Input::except('_token', '_method');

        $page = $input['page'];
        unset($input['page']);

        $result = DB::table('members') -> where('id', $id) -> update($input);

        if($result !== false){
            return redirect('/admin/members?page='.$page);
        }else{
            return back() -> with(['error'=>'Update data failed']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = DB::table('members') -> where('id', $id) -> delete();

        if($result !== false){
            $data = [
                'status' => 0,
                'msg' => 'Delete data success',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => 'Delete data failed',
            ];
        }

        return $data;
    }

    public function setGroup()
    {
        $data = array();

        $input = Input::only('group_id', 'id');

        $result = DB::table('members') -> where(['id'=>$input['id']]) -> update(['group_id'=>$input['group_id']]);

        if($result !== false){
            $data['status'] = 0;
            $data['msg'] = 'Set group success';
        }else{
            $data['status'] = 1;
            $data['msg'] = 'Set group failed';
        }

        return $data;
    }

    public function topups()
    {
        $search = Input::only('is_pay');

        $perpage = 20;//每页显示记录数------`

        $dataCount = DB::table('topups') -> where(function($query) use($search){
            if($search['is_pay'] && $search['is_pay'] != '-1'){
                $query -> where('is_pay', $search['is_pay']);
            }
        }) -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $topupData = DB::table('topups') -> select(['topups.*', 'members.nickname']) -> where(function($query) use($search){
            if($search['is_pay'] && $search['is_pay'] != '-1'){
                $query -> where('is_pay', $search['is_pay']);
            }
        }) -> leftJoin('members', 'topups.member_id', '=', 'members.id') -> offset($page->getOffset()) -> orderBy('topups.id','DESC') -> limit($perpage) -> get();

        return view('admin.members.topups', compact('topupData', 'dataCount', 'pageShow'));
    }

    public function withdraw()
    {
        $perpage = 20;

        $dataCount = DB::table('withdraws') -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $withdrawData = DB::table('withdraws') -> select(['withdraws.*', 'members.nickname']) -> leftJoin('members', 'withdraws.member_id', '=', 'members.id') -> offset($page->getOffset()) -> orderBy('withdraws.id','DESC') -> limit($perpage) -> get();

        return view('admin.members.withdraw', compact('withdrawData', 'dataCount', 'pageShow'));
    }

    public function sign()
    {
        $perpage = 20;

        $dataCount = DB::table('signs') -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $signData = DB::table('signs') -> select(['signs.*', 'members.nickname']) -> leftJoin('members', 'signs.member_id', '=', 'members.id') -> offset($page->getOffset()) -> orderBy('signs.id','DESC') -> limit($perpage) -> get();

        return view('admin.members.sign', compact('signData', 'dataCount', 'pageShow'));
    }

    public function suggest()
    {
        $perpage = 20;//每页显示记录数------`

        $dataCount = DB::table('wishes') -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $wishData = DB::table('wishes') -> select(['wishes.*', 'members.nickname']) -> leftJoin('members', 'wishes.member_id', '=', 'members.id') -> orderBy('wishes.id','DESC') -> offset($page->getOffset()) -> limit($perpage) -> get();

        $system = DB::table('systems') -> select('sys_value') -> where('sys_key', 'suggest_pay') -> first();

        return view('admin.members.suggest', compact('wishData', 'dataCount', 'pageShow', 'system'));
    }

    public function suggestConfirm($id)
    {
        $field = DB::table('wishes') -> select('member_id') -> where('id', $id) -> first();
        $result = DB::table('wishes') -> where('id', $id) -> update(['is_confirm'=>'1']);

        if($result !== false){
            $system = DB::table('systems') -> select('sys_value') -> where('sys_key', 'suggest_pay') -> first();
            DB::table('members') -> where('id', $field->member_id) -> increment('money', $system->sys_value);
            $this->cashFlow('3', $field->member_id, $system->sys_value);

            $member = DB::table('members') -> where('id', $field->member_id) -> first();
            $title = '4989 West Market Complaint & Suggestion Feedback Notice';
            $this->Message($member->openid, $member->nickname, number_format($system->sys_value, 2, '.', '').' 元', date('Y-m-d H:i:s'), $title);

            $data = [
                'status' => 0,
                'msg' => 'Confirm success',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => 'Confirm fail',
            ];
        }

        return $data;
    }

    public function suggestDel($id)
    {
        $field = DB::table('wishes') -> select('image') -> where('id', $id) -> first();
        $images = $field->image;

        $result = DB::table('wishes') -> where('id', $id) -> delete();

        if($result !== false){
            if($images){
                foreach(explode('@', $images) as $v){
                    @unlink(public_path().'/'.$v);
                }
            }

            $data = [
                'status' => 0,
                'msg' => 'Delete successfully',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => 'Delete failed',
            ];
        }

        return $data;
    }

    public function bonusView($id)
    {
        $bonusData = DB::table('cash_flows') -> where(['member_id'=>$id, 'types'=>'4']) -> orderBy('id', 'DESC') -> get();

        return view('admin.members.bonus', compact('bonusData'));
    }

    public function bonus()
    {
        $data = array();

        $input = Input::only('id', 'money');

        $res = $this->cashFlow(4, $input['id'], $input['money']);

        if($res){
            DB::table('members') -> where('id', $input['id']) -> increment('money', $input['money']);

            $member = DB::table('members') -> where('id', $input['id']) -> first();
            $title = '4989 West Market Cash Reward!';
            $remark = 'You have successfully received the membership cash reward gifted by 4989 West Market!';
            $this->Message($member->openid, $member->nickname, number_format($input['money'], 2, '.', '').' 元', date('Y-m-d H:i:s'), $title, $remark);

            $data['status'] = 0;
            $data['msg'] = 'Red envelope successfully gifted';
        }else{
            $data['status'] = 1;
            $data['msg'] = 'Receive failed';
        }

        return $data;
    }

    public function bonusList()
    {
        $perpage = 20;

        $dataCount = DB::table('cash_flows') -> where('types', '4') -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $bonusData = DB::table('cash_flows') -> select(['cash_flows.id', 'cash_flows.money', 'cash_flows.addtime', 'members.portrait', 'members.nickname', 'members.money as balance']) -> where('cash_flows.types', '4') -> leftJoin('members', 'cash_flows.member_id', '=', 'members.id') -> offset($page->getOffset()) -> limit($perpage) -> orderBy('cash_flows.id','DESC') -> get();

        return view('admin.members.bonus_list', compact('bonusData', 'dataCount', 'pageShow'));
    }

    public function bonusDel($id)
    {
        $data = array();

        $field = DB::table('cash_flows') -> where('id', $id) -> first();

        $result = DB::table('cash_flows') -> where('id', $id) -> delete();

        if($result !== false){
            DB::table('members') -> where('id', $field->member_id) -> decrement('money', $field->money);

            $data['status'] = 0;
            $data['msg'] = 'Delete successfully';
        }else{
            $data['status'] = 1;
            $data['msg'] = 'Delete failed';
        }

        return $data;
    }

    private function cashFlow($type, $member_id, $money)
    {
        $cashFlow = array();
        $cashFlow['types'] = $type;
        $cashFlow['member_id'] = $member_id;
        $cashFlow['money'] = $money;
        $cashFlow['addtime'] = time();
        $result = DB::table('cash_flows') -> insert($cashFlow);

        return $result;
    }

    //消息推送
    private function Message($memberOpenid,$nickname,$money,$time,$title,$remark='')
    {
        $notice = new Notice(Config::get('web.WE_APPID'), Config::get('web.WE_SECRET'));
        $userId = $memberOpenid;
        $templateId = 'ptanRIDFTne-5uteDUHE2qbEcDdiTLSm2SmvRNDxAWA';//通过addTemplate方法获取
        $url = url('account');

        $data = array(
            "first"    => array($title, '#333333'),
            "keyword1"   => array($nickname, "#333333"),
            "keyword2"   => array($money, "#333333"),
            "keyword3"   => array($money, "#333333"),
            "keyword4"   => array($time, "#333333"),
            "remark"   => array($remark, "#333333"),
        );

        $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
    }
}
