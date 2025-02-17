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
use Excel;

class OrdersController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = Input::only('is_pay','is_send','nickname','name','phone','order_num');

        $perpage = 20;//每页显示记录数------`

        $dataCount = DB::table('orders') -> leftJoin('members', 'orders.member_id', '=', 'members.id') -> where(function($query) use($search){
            if($search['nickname']) {
                $query->where('members.nickname', 'like', '%' . $search['nickname'] . '%');
            }
            if($search['name']) {
                $query->where('orders.name', 'like', '%' . $search['name'] . '%');
            }
            if($search['phone']) {
                $query->where('orders.phone', 'like', '%' . $search['phone'] . '%');
            }
            if($search['order_num']) {
                $query->where('orders.order_num', 'like', '%' . $search['order_num'] . '%');
            }
            if($search['is_pay'] || $search['is_pay'] == '0') {
                $query->where('orders.is_pay', $search['is_pay']);
            }
            if($search['is_send'] || $search['is_send'] == '0'){
                $query -> where('orders.is_send', $search['is_send']);
            }
        }) -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $orderData = DB::table('orders') -> select(['orders.*', 'members.nickname']) -> where(function($query) use($search){
            if($search['nickname']) {
                $query->where('members.nickname', 'like', '%' . $search['nickname'] . '%');
            }
            if($search['name']) {
                $query->where('orders.name', 'like', '%' . $search['name'] . '%');
            }
            if($search['phone']) {
                $query->where('orders.phone', 'like', '%' . $search['phone'] . '%');
            }
            if($search['order_num']) {
                $query->where('orders.order_num', 'like', '%' . $search['order_num'] . '%');
            }
            if($search['is_pay'] || $search['is_pay'] == '0') {
                $query->where('orders.is_pay', $search['is_pay']);
            }
            if($search['is_send'] || $search['is_send'] == '0'){
                $query -> where('orders.is_send', $search['is_send']);
            }
        }) -> leftJoin('members', 'orders.member_id', '=', 'members.id') -> offset($page->getOffset()) -> orderBy('orders.id','DESC') -> limit($perpage) -> get();
//        $orderData = DB::table('orders') -> select(['orders.*','address.name','address.phone','address.address']) -> leftJoin('address','orders.address_id','=','address.id') -> offset($page->getOffset()) -> orderBy('orders.id','DESC') -> limit($perpage) -> get();
//dd($orderData);

        return view('admin.orders.index', compact('orderData', 'dataCount', 'pageShow'));
    }

    public function orderGoods($id)
    {
        $goodsData = DB::table('order_goods') -> orderBy('order_goods.id','DESC') -> where('order_id', $id) -> get();
        $count = count($goodsData);

        foreach($goodsData as $v){
            $v->brand_name = '';
            $brandId = DB::table('goods') -> select('brand_id') -> where('id',$v->goods_id) -> first();
            if($brandId->brand_id){
                $brandName = DB::table('brands') -> select('name') -> where('id',$brandId->brand_id) -> first();
                $v->brand_name = $brandName->name;
            }

        }

        return view('admin.orders.order_goods', compact('goodsData','count'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::only('order_num','express_name','express_num','all_price','addtime','is_pay','is_send','name','phone','address');
        /*
         * Models -> creste      Input::except('_token');
         * DB -> insert    Input::only('title','image','background_img')
         * */

        $validator = Validator::make($input);

        if($validator -> passes()){
//            $input['password'] = Crypt::encrypt($input['password']);
//  添加时间(不需要就隐藏)          $input['addtime'] = time();

            $result = DB::table('orders') -> insert($input);//$input 数据插入到数据库
            /*
             * Models -> creste(可能)
             * DB -> insert
             * */
            if($result){
                return redirect('/admin/orders');
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
        //$field = Admin::find($id);
        $field = DB::table('orders') -> where('id', $id) -> first();
//dd($field);
        if(!$field){
            return redirect('/admin/orders');
        }

        return view('admin.orders.edit', compact('field'));
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

        $orderData = DB::table('orders') -> select('member_id','is_send') -> where('id',$id) -> first();

        $result = DB::table('orders') -> where('id', $id) -> update($input);

        if($orderData->is_send == 0 && $input['is_send'] == 1){
            $memberData = DB::table('members') -> select('openid') -> where('id',$orderData->member_id) -> first();
            $time = time();
            if($memberData->openid){
                $this->Message($memberData->openid,$input['order_num'],$time,$input['express'],$input['express_num'],$input['address']);
            }
        }

        if($result !== false){
            return redirect('/admin/orders?page='.$page);
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
//        if($id == 1){
//            $data = ['status' => 2,'msg' => '不能删除超级管理员'];
//            return $data;
//        }

//        $field = Ad::find($id);
//        @unlink(public_path().'/'.$field->img);
//        $result = Ad::destroy($id);

        //$result = Admin::destroy($id);
        $result = DB::table('orders') -> where('id', $id) -> delete();

        if($result !== false){
            $data = [
                'status' => 0,
                'msg' => 'Delete data successfully',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => 'Delete data failed',
            ];
        }

        return $data;
    }

    public function saleGoods()
    {
        $search = Input::only('order','name');

        $order = 'DESC';
        if($search['order']){
            $order = $search['order'];
        }

        $perpage = 20;

        $dataCount = DB::table('order_goods') -> leftJoin('orders', 'order_goods.order_id', '=', 'orders.id') -> where('orders.is_pay', '1') -> where(function($query) use($search){
            if($search['name']){
                $query->where('order_goods.name', 'like', '%' . $search['name'] . '%');
            }
        }) -> groupBy('order_goods.goods_id') -> count();

        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();

        $saleGoods = DB::table('order_goods') -> select(DB::raw('la_order_goods.*, SUM(la_order_goods.goods_num) as sale_num')) -> leftJoin('orders', 'order_goods.order_id', '=', 'orders.id') -> where('orders.is_pay', '1') -> where(function($query) use($search){
            if($search['name']){
                $query->where('order_goods.name', 'like', '%' . $search['name'] . '%');
            }
        }) -> groupBy('order_goods.goods_id') -> orderBy('sale_num', $order) -> offset($page->getOffset()) -> limit($perpage) -> get();

        return view('admin.orders.sale_goods', compact('saleGoods', 'pageShow'));
    }

    //消息推送
    public function Message($memberOpenid,$orderNum,$time,$expressesName,$expressesNum,$address)
    {
        $notice = new Notice(Config::get('web.WE_APPID'), Config::get('web.WE_SECRET'));
        $userId = $memberOpenid;
        $templateId = '0jIcdeAIO5U6WxDEhTkZepndmtWR9EIRlPLEewqma08';//通过addTemplate方法获取
        $url = url('order');

        $data = array(
            "first"    => array("您购买的订单已经发货啦，正快马加鞭向您飞奔而去。", '#333333'),
            "keyword1"   => array($orderNum, "#333333"),
            "keyword2"   => array(date('Y-m-d H:i', $time), "#333333"),
            "keyword3"   => array($expressesName, "#333333"),
            "keyword4"   => array($expressesNum, "#333333"),
            "keyword5"   => array($address, "#333333"),
            "remark"   => array("请保持收件手机畅通！", "#333333"),
        );

        $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
    }

    public function benefit()
    {
        $partners = DB::table('partners') -> select('id', 'name') -> get();

        $search = Input::only('partner_id','addtime_from','addtime_to');

        $orderData = DB::table('orders') -> select(['orders.*', 'members.nickname', 'partners.name as partner_name', 'partners.rate']) -> where('orders.is_pay', '1') -> where(function($query) use($search){
            if($search['partner_id'] && $search['partner_id'] != '-1') {
                $query->where('partners.id', $search['partner_id']);
            }

            if($search['addtime_from']){
                $query -> where('orders.addtime', '>=', strtotime($search['addtime_from'].' 00:00:00'));
            }else{
                $time_from = mktime(0,0,0,date('m'),date('d'),date('Y')) - 86400*7;
                $query -> where('orders.addtime', '>=', $time_from);
            }

            if($search['addtime_to']){
                $query -> where('orders.addtime', '<=', strtotime($search['addtime_to'].' 23:59:59'));
            }else{
                $query -> where('orders.addtime', '<=', time());
            }
        }) -> leftJoin('members', 'orders.member_id', '=', 'members.id') -> leftJoin('partners', 'members.partner_code', '=', 'partners.code') -> orderBy('orders.id','DESC') -> get();

        return view('admin.orders.benefit', compact('orderData', 'partners'));
    }

    public function partnerExcel()
    {
        $search = Input::only('partner_id','addtime_from','addtime_to');

        $orderData = DB::table('orders') -> select(['orders.id', 'partners.name as partner_name', 'orders.order_num', 'orders.name', 'orders.phone', 'orders.address', 'orders.all_price', 'partners.rate', 'orders.addtime']) -> where('orders.is_pay', '1') -> where(function($query) use($search){
            if($search['partner_id'] && $search['partner_id'] != '-1') {
                $query->where('partners.id', $search['partner_id']);
            }

            if($search['addtime_from']){
                $query -> where('orders.addtime', '>=', strtotime($search['addtime_from'].' 00:00:00'));
            }else{
                $time_from = mktime(0,0,0,date('m'),date('d'),date('Y')) - 86400*7;
                $query -> where('orders.addtime', '>=', $time_from);
            }

            if($search['addtime_to']){
                $query -> where('orders.addtime', '<=', strtotime($search['addtime_to'].' 23:59:59'));
            }else{
                $query -> where('orders.addtime', '<=', time());
            }
        }) -> leftJoin('members', 'orders.member_id', '=', 'members.id') -> leftJoin('partners', 'members.partner_code', '=', 'partners.code') -> orderBy('orders.id','DESC') -> get();

        $cellData = array(['ID', '合作者名称', '订单号', '收货人', '联系方式', '详细地址', '支付金额', '收益率', '收益额', '下单时间']);

        foreach($orderData as $k=>$v){
            $_data = (array)$v;
            $_data['addtime'] = date('Y-m-d H:i:s', $_data['addtime']);
            $subTotal = array('sub_total'=>number_format($_data['all_price'] * $_data['rate'] / 100, 2, '.', ''));

            array_splice($_data, -1, 0, $subTotal);

            $cellData[] = $_data;
        }

        $partner = DB::table('partners') -> select('name') -> where('id', $search['partner_id']) -> first();
        $partnerName = $partner ? $partner->name : '合并';

        $printStart = $search['addtime_from'] ?: date('Y-m-d', mktime(0,0,0,date('m'),date('d'),date('Y')) - 86400*7);
        $printEnd = $search['addtime_to'] ?: date('Y-m-d');

        $filename = $partnerName.'-'.date('YmdHis').'('.$printStart.'_'.$printEnd.')';

        Excel::create($filename,function($excel) use ($cellData, $partnerName){
            $excel->sheet($partnerName, function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xlsx');

        echo '<script>window.close();</script>';
    }

    public function areas()
    {
        $orderAddress = DB::table('orders') -> select(['address', 'all_price']) -> get();
        $areas = array();
        $orderNum = 0;
        $orderAmount = 0;

        foreach($orderAddress as $v){
            if(!strstr($v->address, ':')){
                continue;
            }

            $area = trim(explode(':', $v->address)[0]);

            if(!array_key_exists($area, $areas)){
                $areas[$area]['order_num'] = 1;
                $areas[$area]['amount'] = $v->all_price;
            }else{
                $areas[$area]['order_num'] ++;
                $areas[$area]['amount'] += $v->all_price;
            }

            $orderNum++;
            $orderAmount += $v->all_price;
        }

        array_multisort(array_column($areas,'order_num'),SORT_DESC, $areas);

        return view('admin.orders.areas', compact('areas', 'orderNum', 'orderAmount'));
    }

    public function orderView($sn)
    {
        $order = DB::table('orders')->where('order_num', $sn)->first();
        $member = DB::table('members')->where('id', $order->member_id)->first();
        $_orderGoods = DB::table('order_goods')->select(['order_goods.*', 'brands.name as brand_name'])->leftJoin('goods', 'order_goods.goods_id', '=', 'goods.id')->leftJoin('brands', 'goods.brand_id', '=', 'brands.id')->where('order_goods.order_id', $order->id)->get();

        $orderGoods = array();

        foreach ($_orderGoods as $v) {
            $orderGoods[$v->brand_name][] = $v;
        }

        return view('admin.orders.order_view', compact('order', 'orderGoods', 'member'));
    }

    public function orderPrintView()
    {
        $search = Input::only('time_from','time_to');

        $search['time_from'] = $search['time_from'] ?: date('Y-m-d');
        $search['time_to'] = $search['time_to'] ?: date('Y-m-d');

        $datas = DB::table('order_goods')->select(['orders.addtime', 'brands.name as brand_name', 'order_goods.name', 'order_goods.goods_num', 'orders.name as consignee', 'orders.address'])->leftJoin('orders', 'order_goods.order_id', '=', 'orders.id')->leftJoin('goods', 'order_goods.goods_id', '=', 'goods.id')->leftJoin('brands', 'goods.brand_id', '=', 'brands.id')->where('orders.addtime', '>=', strtotime($search['time_from'].' 00:00:00'))->where('orders.addtime', '<=', strtotime($search['time_to'].' 23:59:59'))->where('orders.is_pay', '1')->orderBy('order_goods.id', 'DESC')->groupBy('order_goods.id')->get();

        return view('admin.orders.order_print', compact('datas', 'search'));
    }

    public function orderPrint()
    {
        $search = Input::only('time_from','time_to');

        $search['time_from'] = $search['time_from'] ?: date('Y-m-d');
        $search['time_to'] = $search['time_to'] ?: date('Y-m-d');

        $datas = DB::table('order_goods')->select(['orders.addtime', 'brands.name as brand_name', 'order_goods.name', 'order_goods.goods_num', 'orders.name as consignee', 'orders.address'])->leftJoin('orders', 'order_goods.order_id', '=', 'orders.id')->leftJoin('goods', 'order_goods.goods_id', '=', 'goods.id')->leftJoin('brands', 'goods.brand_id', '=', 'brands.id')->where('orders.addtime', '>=', strtotime($search['time_from'].' 00:00:00'))->where('orders.addtime', '<=', strtotime($search['time_to'].' 23:59:59'))->where('orders.is_pay', '1')->orderBy('order_goods.id', 'DESC')->groupBy('order_goods.id')->get();

        $filename = '4989订单'.$search['time_from'].'~'.$search['time_to'];

        $cellData = [
            ['下单时间','商家','商品名称','数量','收货人','收货人地址'],
        ];

        foreach ($datas as $v) {
            $_data = array();
            $_data[] = date('Y-m-d H:i', $v->addtime);
            $_data[] = $v->brand_name;
            $_data[] = $v->name;
            $_data[] = $v->goods_num;
            $_data[] = $v->consignee;
            $_data[] = $v->address;
            $cellData[] = $_data;
        }

        Excel::create($filename, function($excel) use ($cellData){
            $excel->sheet('4989 Orders', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xlsx');

        exit('<script>window.close();</script>');
    }
}
