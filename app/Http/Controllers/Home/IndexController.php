<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Models\Category;

use App\Tool\Transfer\Transfer;
use App\Tool\Wepay\lib\WxPayApi;
use App\Tool\Wepay\lib\WxPayConfig;
use App\Tool\Wepay\lib\WxPayUnifiedOrder;
use App\Tool\Wepay\lib\WxPayNotify;
use App\Tool\Wepay\pay\NativePay;
use App\Tool\Wepay\pay\WxPayNativePay;
use App\Tool\Wepay\pay\WxPayJsPay;

use App\Tool\Page\Page;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Overtrue\Wechat\Notice;

use App\Tool\Weshare\Jssdk;

class IndexController extends CommonController
{
    public function index()
    {
        $time = time();

        $bannerData = DB::table('banners')->where('end_time','>',$time)->where('position','main')->where('is_use',1)->orderBy('sort', 'DESC')->orderBy('id','DESC')->get();

        $goodsData = DB::table('goods')->select('id', 'image', 'name', 'org_price')->where(['is_use'=>'1','is_rec'=>'1','fiveday'=>'0'])->where('stock', '>', '0')->orderBy('sort','DESC')->orderBy('id','DESC')->get();

        $categoryData = DB::table('categories')->where(['is_rec'=>'1'])->orderBy('sort','DESC')->get();

        //获取首页分类楼层信息
        $floors = array();
        $floorsGoods = 4;

        //每个楼层头部广告
        $indexMidBnrs = DB::table('banners')->where('end_time','>',$time)->where('position','main_mid')->where('is_use', '1')->orderBy('sort', 'DESC')->orderBy('id', 'DESC')->get();

        foreach ($categoryData as $k=>$v) {
            $floors[$k]['id'] = $v->id;
            $floors[$k]['name'] = $v->name;

            if (array_key_exists($k, $indexMidBnrs)) {
                $floors[$k]['ad'] = $indexMidBnrs[$k];
            }

            $cates = array();
            $cates[] = $v->id;

            if (!$v->parent_id) {
                $childs = DB::table('categories')->where('parent_id', $v->id)->get();

                foreach ($childs as $v1) {
                    $cates[] = $v1->id;
                }
            }

            $floors[$k]['goods'] = DB::table('goods')->select(['goods.id', 'goods.image', 'goods.name', 'goods.other_name', 'goods.org_price', 'goods.price', 'goods.special', 'brands.name as brand_name'])->whereIn('goods.category_id', $cates)->leftJoin('brands', 'goods.brand_id', '=', 'brands.id')->where(['goods.is_use'=>'1','goods.fiveday'=>'0'])->where('goods.stock', '>', '0')->orderBy('goods.sort','DESC')->orderBy('goods.id','DESC')->limit($floorsGoods)->get();
        }

        $brandData = DB::table('brands') -> where(['is_rec'=>'1']) -> orderBy('sort','DESC') -> orderBy('id','DESC') -> get();

        $mainTopup = DB::table('banners') -> where('end_time','>',$time) -> where('position','main_popup') -> where('is_use','1') -> first();

        return view('home.index.index', compact('bannerData', 'goodsData', 'categoryData', 'floors', 'brandData', 'mainTopup'));
    }

    public function getHeaderInfo()
    {
        $info = array();

        $info['portrait'] = session('portrait') ?: url('public/home/img/logo.png');
        //$info['carts'] = DB::table('carts') -> where('member_id', session('mid')) -> count();

        return $info;
    }

    public function brand()
    {
        $input = Input::only('category','keyword');

        if($input['category']){
            $selectId = DB::table('categories') -> select('id') -> where('parent_id',$input['category']) -> get();
            $input['category'] = [];
            foreach($selectId as $v){
                $input['category'][] = $v->id;
            }
        }

        $categoryData = DB::table('categories') -> where(['parent_id'=>'0']) -> orderBy('sort','DESC') -> limit(8) -> get();

        $brandData = DB::table('brands') -> select(DB::raw('la_brands.*,la_goods.category_id, COUNT(la_goods.id) as goods_count')) -> leftJoin('goods','brands.id','=','goods.brand_id') -> where(function($query) use($input){
            if($input['keyword']){
                $query -> where('brands.name', 'like', '%'.$input['keyword'].'%');
            }
            if($input['category']){
                $query -> whereIn('goods.category_id', $input['category']);
            }
        }) -> where('brands.is_use','1') -> orderBy('brands.sort','DESC') -> orderBy('brands.id','DESC') -> groupBy('brands.id') -> get();

        return view('home.index.brand', compact('categoryData','brandData'));
    }

    public function brandView($id)
    {
        $brandData = DB::table('brands') -> where(['id'=>$id]) -> first();

        $mid = session('mid');
        $isFavor = '';
        if($mid){
            $favorData = DB::table('favors')->where(['brand_id'=>$id, 'member_id' => $mid])->count();
            if($favorData){
                $isFavor = 'v';
            }
        }

        $perpage = 10;

        $brandGoodsData = DB::table('goods') -> select('goods.id','goods.name','goods.other_name','goods.image','goods.price','goods.org_price','goods.weight','goods.limit_num','goods.stock', 'goods.special','brands.id as brand_id','brands.name as brand_name','brands.image as brand_image') -> leftJoin('brands','goods.brand_id','=','brands.id') -> where(['goods.is_use'=>'1','goods.fiveday'=>'0', 'goods.brand_id'=>$brandData->id]) -> where('goods.stock', '>', '0') -> orderBy('goods.sort','DESC') -> orderBy('goods.id','DESC') -> limit($perpage) -> get();

        $goodsCount = DB::table('goods') -> where(['is_use'=>'1','fiveday'=>'0', 'brand_id'=>$brandData->id]) -> where('stock', '>', '0') -> count();

        $koreabrand = array();
        if($brandData->is_korea){
            $koreabrand = DB::table('brands') -> where('is_korea', '1') -> get();
        }

        return view('home.index.brand_view', compact('brandData', 'isFavor','brandGoodsData', 'koreabrand', 'goodsCount'));
    }

    public function ajaxGetBrandGoods()
    {
        $input = Input::only('id','page');

        $perpage = 10;

        $offset = $input['page'] * $perpage;

        $goodsData = DB::table('goods') -> select('goods.id','goods.name','goods.other_name','goods.image','goods.price','goods.org_price','goods.weight','goods.limit_num','goods.stock', 'goods.special','brands.id as brand_id','brands.name as brand_name','brands.image as brand_image') -> leftJoin('brands','goods.brand_id','=','brands.id') -> where(['goods.is_use'=>'1','goods.fiveday'=>'0', 'goods.brand_id'=>$input['id']]) -> where('goods.stock', '>', '0') -> orderBy('goods.sort','DESC') -> orderBy('goods.id','DESC') -> offset($offset) -> limit($perpage) -> get();

        return $goodsData;
    }

    public function nav()
    {
        $categoryData = DB::table('categories') -> where(['parent_id'=>'0']) -> orderBy('sort','DESC') -> get();

        $sub_cat = DB::table('categories') -> where('parent_id','!=','0') -> orderBy('sort','DESC') -> get();

        foreach ($categoryData as $v){
            $v->sub_cat = [];
            foreach ($sub_cat as $v1) {
                if ($v1->parent_id == $v->id) {
                    $v->sub_cat[] = $v1;
                };
            }
        }

        return view('home.index.nav', compact('categoryData'));
    }

    public function goods()
    {
        $input = Input::only('keyword','cat','sale','price');

        $order = 'la_goods.sort DESC';
        if($input['sale']) $order = 'la_goods.sale DESC';
        if($input['price']) $order = 'la_goods.price ASC';
        if($input['price'] == 'b') $order = 'la_goods.price DESC';

        $catFilter = array();
        $goodsFilter = array();
        if($input['cat']){
            $catFilter[] = $input['cat'];
            $categoryData = DB::table('categories') -> where('id', $input['cat']) -> first();

            if(!$categoryData->parent_id){
                $childCategory = DB::table('categories') -> select('id') -> where('parent_id',$categoryData->id) -> get();

                foreach($childCategory as $v){
                    $catFilter[] = $v->id;
                }
            }

            $goodsCategory = DB::table('goods_categories') -> where('category_id', $input['cat']) -> get();
            foreach($goodsCategory as $v){
                $goodsFilter[] = $v->goods_id;
            }
        }

        $perpage = 10;

        $goodsData = DB::table('goods') -> select('goods.id','goods.name','goods.other_name','goods.image','goods.price','goods.org_price','goods.weight','goods.limit_num','goods.stock', 'goods.special','brands.id as brand_id','brands.name as brand_name','brands.image as brand_image') -> leftJoin('brands','goods.brand_id','=','brands.id') -> where(['goods.is_use'=>'1','fiveday'=>'0']) -> where('goods.stock', '>', '0') -> where(function($query) use($input, $catFilter, $goodsFilter){
            if(!empty($catFilter)) {
                $query->orWhereIn('goods.category_id', $catFilter);
            }
            if($input['keyword']) {
                $query->orWhere('goods.name', 'like', '%' . $input['keyword'] . '%') -> orWhere('goods.other_name', 'like', '%' . $input['keyword'] . '%');
            }
            if(!empty($goodsFilter)){
                $query->orWhereIn('goods.id', $goodsFilter);
            }
        }) -> orderByRaw($order) -> orderBy('goods.id','DESC') -> limit($perpage) -> get();

        $time = time();

        $adDataList = DB::table('banners') -> where('end_time','>',$time) -> where('position','list') -> where('is_use',1) -> orderBy('sort', 'DESC') -> orderBy('id', 'DESC') -> first();

        return view('home.index.goods', compact('goodsData','adDataList'));
    }
    //加载更多
    public function ajaxGetGoods()
    {
        $input = Input::only('page','keyword','cat','cat_02','sale','price');

        $limit = 10;
        $exists = 10;
        $originGoods = $exists + ($limit*$input['page']);

        $order = 'la_goods.sort DESC';
        if($input['sale']) $order = 'la_goods.sale DESC';
        if($input['price']) $order = 'la_goods.price ASC';
        if($input['price'] == 'b') $order = 'la_goods.price DESC';

        $catFilter = array();
        $goodsFilter = array();
        if($input['cat']){
            $catFilter[] = $input['cat'];
            $categoryData = DB::table('categories') -> where('id', $input['cat']) -> first();

            if(!$categoryData->parent_id){
                $childCategory = DB::table('categories') -> select('id') -> where('parent_id',$categoryData->id) -> get();

                foreach($childCategory as $v){
                    $catFilter[] = $v->id;
                }
            }

            $goodsCategory = DB::table('goods_categories') -> where('category_id', $input['cat']) -> get();
            foreach($goodsCategory as $v){
                $goodsFilter[] = $v->goods_id;
            }
        }

        $goodsData = DB::table('goods') -> select('goods.id','goods.name','goods.other_name','goods.image','goods.price','goods.org_price','goods.weight','goods.limit_num','goods.stock', 'goods.special','brands.id as brand_id','brands.name as brand_name','brands.image as brand_image') -> leftJoin('brands','goods.brand_id','=','brands.id') -> where(['goods.is_use'=>'1','fiveday'=>'0']) -> where('goods.stock', '>', '0') -> where(function($query) use($input, $catFilter, $goodsFilter){
            if(!empty($catFilter)) {
                $query->orWhereIn('goods.category_id', $catFilter);
            }
            if($input['keyword']) {
                $query->orWhere('goods.name', 'like', '%' . $input['keyword'] . '%') -> orWhere('goods.other_name', 'like', '%' . $input['keyword'] . '%');
            }
            if(!empty($goodsFilter)){
                $query->orWhereIn('goods.id', $goodsFilter);
            }
        }) -> orderByRaw($order) -> orderBy('goods.id','DESC') -> offset($originGoods) -> limit($limit) -> get();

        return $goodsData;
    }

    public function goodsView($id)
    {
        $goodsData = DB::table('goods') -> select('goods.*','brands.id as brands_id','brands.name as brand_name','brands.image as brand_image','expresses.province_name') -> leftJoin('brands','goods.brand_id','=','brands.id') -> leftJoin('expresses', 'goods.express_id', '=', 'expresses.id') -> where(['goods.is_use'=>'1', 'goods.id'=>$id]) -> where('goods.stock', '>', '0') -> first();

        if(!$goodsData){
            return redirect('/');
        }

        $fiveday = is_fiveday();

        if (!$fiveday && $goodsData->fiveday) {
            return redirect('fiveday');
        }

        $mid = session('mid');
        $isFavor = '';
        if($mid){
            $favorData = DB::table('favors')->where(['goods_id'=>$id, 'member_id' => $mid])->count();
            if($favorData){
                $isFavor = 'v';
            }
        }

        $goodsImage = DB::table('goods_images') -> where('goods_id',$id) -> get();

        $addCartBehavior = session('add_cart_behavior');
        session(['add_cart_behavior'=>null]);

        // 随机获取同分类商品
        $categoryId = $goodsData->category_id;

        $categoryData = DB::table('categories') -> where('id', $categoryId) -> first();
        $categories = array();
        $categories[] = $categoryData->id;

        if($categoryData->parent_id){//当前分类为子分类（二级分类）
            $parentCategory = DB::table('categories') -> where('id', $categoryData->parent_id) -> first();
            $categories[] = $parentCategory->id;
        }else{//当前分类为顶级分类
            $childCategory = DB::table('categories') -> where('parent_id', $categoryData->id) -> get();
            foreach($childCategory as $v){
                $categories[] = $v->id;
            }
        }

        $goodsCate = DB::table('goods_categories') -> where('category_id', $categoryId) -> get();//扩展分类中的商品id
        $goods = DB::table('goods') -> select('id') -> whereIn('category_id', $categories) -> where('stock', '>', '0') -> get();//当前分类中的其他商品
        $goodsIds = array();
        foreach($goodsCate as $v){
            $goodsIds[] = $v->goods_id;
        }
        foreach($goods as $v){
            if($v->id == $id){
                continue;
            }
            $goodsIds[] = $v->id;
        }
        $relGoods = array();
        if(!empty($goodsIds)){
            $goodsIds = array_unique($goodsIds);
            shuffle($goodsIds);
            $goodsIds = array_slice($goodsIds, 0, 10);

            $relGoods = DB::table('goods')->select('goods.id','goods.name','goods.other_name','goods.image','goods.price','goods.org_price','goods.weight','goods.limit_num','goods.stock', 'goods.special','brands.id as brand_id','brands.name as brand_name','brands.image as brand_image') -> leftJoin('brands','goods.brand_id','=','brands.id') ->where(['goods.is_use'=>'1', 'goods.fiveday'=>'0'])->whereIn('goods.id', $goodsIds)->where('goods.stock', '>', '0')->get();
        }

        return view('home.index.goods_view', compact('goodsData','goodsImage', 'isFavor', 'addCartBehavior', 'relGoods', 'fiveday'));
    }

    public function cart()
    {
        $mid = session('mid');

        $_cartData = DB::table('carts') -> select(['carts.*','goods.brand_id','goods.id as goods_id','goods.name as goods_name','goods.image as goods_image','goods.price_name','goods.org_price_name','goods.price as goods_price','goods.org_price','goods.weight','goods.express_id','goods.limit_num','brands.name','expresses.province_name']) -> leftJoin('goods','goods.id','=','carts.goods_id') -> leftJoin('brands','brands.id','=','goods.brand_id') -> leftJoin('expresses', 'goods.express_id', '=', 'expresses.id') ->  where(['carts.member_id'=>$mid]) -> orderBy('carts.id','DESC') -> get();

        $cartData = [];
        $allPrice = 0;
        $org_allPrice = 0;
        foreach($_cartData as $k=>$v){
            if(!$v->goods_id){
                unset($_cartData[$k]);
                continue;
            }
            $cartData[$v->name][] = $v;
            $allPrice += $v->goods_price*$v->goods_num;
            $org_allPrice += $v->org_price*$v->goods_num;
        }

        return view('home.index.cart', compact('cartData','allPrice','org_allPrice'));
    }

    public function buy()
    {
        $order = session('order');
        $mid = session('mid');

        $addressData = DB::table('address') -> where(['member_id'=>$mid,'is_default'=>'1']) -> first();

        switch($order['type']){
            case 'cart':
                $_buyData = DB::table('carts') -> select(['carts.*','goods.brand_id','goods.id as goods_id','goods.name as goods_name','goods.image as goods_image','goods.price_name','goods.org_price_name','goods.price as goods_price','goods.org_price','goods.weight as goods_weight','goods.express_id','brands.name','expresses.province','expresses.province_name']) -> leftJoin('goods','goods.id','=','carts.goods_id') -> leftJoin('brands','brands.id','=','goods.brand_id') -> leftJoin('expresses', 'goods.express_id', '=', 'expresses.id') -> orderBy('carts.id','DESC') -> where(['member_id'=>$mid]) -> whereIn('carts.id', $order['cart_id']) -> get();
                $buyData = [];
                $allPrice = 0;
                $org_allPrice = 0;
                foreach($_buyData as $v){
                    $buyData[$v->name][] = $v;
                    $allPrice += $v->goods_price*$v->goods_num;
                    $org_allPrice += $v->org_price*$v->goods_num;
                }
                break;
            case 'index':
                $_buyData = DB::table('goods') -> select(['goods.id as goods_id','goods.brand_id', 'goods.name as goods_name', 'goods.image as goods_image','goods.price_name','goods.org_price_name', 'goods.price as goods_price','goods.org_price','goods.weight as goods_weight','goods.express_id', 'brands.name','expresses.province','expresses.province_name']) -> leftJoin('brands','brands.id','=','goods.brand_id') -> leftJoin('expresses', 'goods.express_id', '=', 'expresses.id') -> where('goods.id', $order['goods']['goods_id']) -> get();
                $_buyData[0] -> member_id = $mid;
                $_buyData[0] -> goods_num = $order['goods']['goods_num'];
                $buyData = [];
                $allPrice = 0;
                $org_allPrice = 0;
                foreach($_buyData as $v){
                    $buyData[$v->name][] = $v;
                    $allPrice += $v->goods_price*$v->goods_num;
                    $org_allPrice += $v->org_price*$v->goods_num;
                }
                break;
        }

        $goods_weight = [];
        foreach($buyData as $k=>$v){
            $goods_weight[$k] = 0;//设置某商家的重量为0
            foreach($v as $k1=>$v1){
                $goods_weight[$k] += $v1->goods_weight * $v1->goods_num;
            }
        }

        $express_price = 0;
		$express = null;

        if($addressData){
			$express = DB::table('expresses') -> where('province',$addressData->province) -> first();
            foreach($goods_weight as $v){
                if(!$v)continue;
                if($express->base_weight >= $v){//不超过首重，就按首重价格收取运费
                    $express_price += $express->default_price;
                }else{//超过首重，则 首重+单位续重*续重
                    $express_price += $express->default_price + (ceil($v - $express->base_weight) * $express->pre_weight_price);
                }
            }
        }

        session(['buyData'=>$buyData]);
        session(['express_price'=>$express_price]);

        $memberData = DB::table('members') -> select(['money']) -> where('id',$mid) -> first();

        return view('home.index.buy',compact('addressData','buyData','allPrice','org_allPrice','express_price', 'express', 'memberData'));
    }

    public function wxpay()
    {
        $input = Input::only('order_num');

        $order_num = $input['order_num'];

        $orderNum = Crypt::decrypt($order_num);

        $mid = session('mid');

        $payData = DB::table('orders') -> select(['order_num','all_price','express_price']) -> where(['member_id'=>$mid,'order_num'=>$orderNum]) -> first();

        if(!$payData){
            return redirect('/');
        }

        if(isWeixin()){
            //①、获取用户openid
            $tools = new WxPayJsPay();
            $openId = $tools->GetOpenid();

            if(!$openId){
                return redirect('/myorder');
            }

            //②、统一下单
            $input = new WxPayUnifiedOrder();
            $input->SetBody($this->SYSTEM['sitename']);
            $input->SetAttach($this->SYSTEM['sitename']);
            $input->SetOut_trade_no($payData->order_num);
            $input->SetTotal_fee((string)((int)(($payData->all_price + $payData->express_price)*100)));//1代表1分
            $input->SetTime_start(date("YmdHis"));
            //$input->SetTime_expire(date("YmdHis", time() + 600));//微信服务器变更后这个字段范围不详，但可以不设置 error : Oops! something went wrong:)
            $input->SetGoods_tag($payData->order_num);
            $input->SetNotify_url(WxPayConfig::NOTIFY_URL . $payData->order_num . md5(Config::get('web.PAYMENT_KEY')));
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($openId);

            $order = WxPayApi::unifiedOrder($input);

            $jsApiParameters = $tools->GetJsApiParameters($order);

            return view('home.index.wxpay', compact('jsApiParameters', 'payData'));
        }
    }

    public function my()
    {
        $mid = session('mid');

        $memberData = DB::table('members') -> where('id',$mid) -> first();

        $orderCount1 = DB::table('orders') -> where(['member_id'=>$mid, 'is_pay'=>'0']) -> count();
        $orderCount2 = DB::table('orders') -> where(['member_id'=>$mid, 'is_pay'=>'1']) -> count();
        $orderCount3 = DB::table('orders') -> where(['member_id'=>$mid, 'is_send'=>'1']) -> count();
        $orderCount4 = DB::table('orders') -> where(['member_id'=>$mid, 'is_send'=>'2']) -> count();

        $cartNum = DB::table('carts') -> where('member_id', $mid) -> count();

        $mypageAd = DB::table('banners') -> where('end_time','>',time()) -> where('position','mypage_ad') -> where('is_use',1) -> orderBy('sort', 'DESC') -> orderBy('id','DESC') -> get();

        return view('home.index.my', compact('memberData','orderCount1','orderCount2','orderCount3','orderCount4', 'cartNum', 'mypageAd'));
    }

    public function account()
    {
        $mid = session('mid');

        $memberData = DB::table('members') -> where('id',$mid) -> first();

        $perpage = 20;

        $cashFlow = DB::table('cash_flows') -> where('member_id',$mid) -> orderBy('id', 'DESC') -> limit($perpage) -> get();

        return view('home.index.account', compact('memberData', 'cashFlow'));
    }

    public function getCashFlow()
    {
        $input = Input::only('page');

        $perpage = 20;

        $offset = $input['page'] * $perpage;

        $mid = session('mid');

        $cashFlow = DB::table('cash_flows') -> where('member_id',$mid) -> orderBy('id', 'DESC') -> limit($perpage) -> offset($offset) -> get();

        return $cashFlow;
    }

    public function topupView()
    {
        $mid = session('mid');

        $memberData = DB::table('members') -> where('id',$mid) -> first();

        $topupData = DB::table('topup_moneys') -> get();

        return view('home.index.topup', compact('memberData', 'topupData'));
    }

    public function topup()
    {
        $data = array();

        $mid = session('mid');

        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please login';
            return $data;
        }

        $input = Input::only('topup_id');

        if(!$input['topup_id']){
            $data['status'] = 1;
            $data['msg'] = 'Please select topup money';
            return $data;
        }

        $topupData = DB::table('topup_moneys') -> where('id', $input['topup_id']) -> first();
        unset($input['topup_id']);

        if(!$topupData){
            $data['status'] = 2;
            $data['msg'] = 'failed';
            return $data;
        }

        $input['topup_sn'] = date('Ymdhis') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $input['member_id'] = $mid;
        $input['money'] = $topupData->money;
        $input['addtime'] = time();

        $result = DB::table('topups') -> insert($input);

        if($result){
            $data['status'] = 0;
            $data['msg'] = 'success';
            $data['topup_sn'] = Crypt::encrypt($input['topup_sn']);
        }else{
            $data['status'] = 3;
            $data['msg'] = 'failed';
        }

        return $data;
    }

    public function wxTopup()
    {
        $input = Input::only('topup_sn');

        $topupSn = $input['topup_sn'];

        $topupSn = Crypt::decrypt($topupSn);

        $mid = session('mid');

        $topupData = DB::table('topups') -> where(['member_id'=>$mid,'topup_sn'=>$topupSn]) -> first();

        if(!$topupData){
            return redirect('/');
        }

        if(isWeixin()){
            //①、获取用户openid
            $tools = new WxPayJsPay();
            $openId = $tools->GetOpenid();

            if(!$openId){
                return redirect('/my');
            }

            //②、统一下单
            $input = new WxPayUnifiedOrder();
            $input->SetBody($this->SYSTEM['sitename']);
            $input->SetAttach($this->SYSTEM['sitename']);
            $input->SetOut_trade_no($topupData->topup_sn);
            $input->SetTotal_fee((string)((int)(($topupData->money)*100)));//1代表1分
            $input->SetTime_start(date("YmdHis"));
            //$input->SetTime_expire(date("YmdHis", time() + 600));//微信服务器变更后这个字段范围不详，但可以不设置 error : Oops! something went wrong:)
            $input->SetGoods_tag($topupData->topup_sn);
            $input->SetNotify_url(url('topup_notify') . '/' . $topupData->topup_sn . md5(Config::get('web.PAYMENT_KEY')));
            $input->SetTrade_type("JSAPI");
            $input->SetOpenid($openId);

            $order = WxPayApi::unifiedOrder($input);

            $jsApiParameters = $tools->GetJsApiParameters($order);

            return view('home.index.wx_topup', compact('jsApiParameters', 'topupData'));
        }
    }

    public function topupNotify($topup_sn)
    {
        if(!strpos($topup_sn, md5(Config::get('web.PAYMENT_KEY')))){
            Log::info('valid failed');
            return 'fail';
        }
        $topupSn = substr($topup_sn, 0, 20);

        $topupData = DB::table('topups') -> where('topup_sn', $topupSn) -> first();
        if($topupData->is_pay == '1'){
            //Log::info('订单号为 ' . $topupSn . ' 充值订单支付已完成');
            return 'fail';
        }
        $result = DB::table('topups') -> where('topup_sn', $topupSn) -> update(['is_pay'=>'1']);
        if($result !== false){
            DB::table('members') -> where('id', $topupData->member_id) -> increment('money', $topupData->money);

            $this->cashFlow('2', $topupData->member_id, $topupData->money);

            $member = DB::table('members') -> where('id', $topupData->member_id) -> first();

            $_openids = $this->SYSTEM['openids'];
            $openids = explode(';', $_openids);

            foreach ($openids as $v) {
                if (!$v) continue;
                $this->topupMsgs($v,$member->nickname,$topupData->money,$topupSn,date('Y-m-d H:i:s', $topupData->addtime));
            }

            /*
            $email = DB::table('email') -> first();
            $emailArr = explode('&', $email->email);
            foreach($emailArr as $v){
                $this->topupMail($v, $member->nickname, $topupData);
            }
            */

            $this->topupMsg($member->openid, $member->nickname, $topupData->money, date('Y-m-d H:i:s', $topupData->addtime));

            Log::info('Order number ' . $topupSn . ' payment successful');
        }else{
            Log::info('Order number ' . $topupSn . ' payment failed');
        }
    }

    //充值发送邮箱
    public function topupMail($email,$nickname,$topupData)
    {
        Config::set('mail.driver', Config::get('web.MAIL_DRIVER'));
        Config::set('mail.host', Config::get('web.MAIL_HOST'));
        Config::set('mail.port', Config::get('web.MAIL_PORT'));
        Config::set('mail.username', Config::get('web.MAIL_USERNAME'));
        Config::set('mail.password', Config::get('web.MAIL_PASSWORD'));
        Config::set('mail.encryption', Config::get('web.MAIL_ENCRYPTION'));
        Config::set('mail.from', ['address'=>Config::get('web.MAIL_ADDRESS'), 'name'=>Config::get('web.MAIL_NAME')]);

        $emailData = array();
        $emailData['email'] = $email;
        $emailData['name'] = $nickname;
        $emailData['subject'] = Config::get('web.MAIL_SUBJECT');

        $topupData->nickname = $nickname;

        Mail::send('home.index.topup_email', ['emailData' => $topupData], function ($m) use ($emailData) {
            $m->to($emailData['email'], $emailData['name'])->subject($emailData['subject']);
        });
    }

    //消息推送
    public function topupMsg($memberOpenid,$nickname,$money,$time)
    {
        $notice = new Notice(Config::get('web.WE_APPID'), Config::get('web.WE_SECRET'));
        $userId = $memberOpenid;
        $templateId = 'ptanRIDFTne-5uteDUHE2qbEcDdiTLSm2SmvRNDxAWA';//通过addTemplate方法获取
        $url = url('account');

        $data = array(
            "first"    => array("4989 West Market topup success notice", '#333333'),
            "keyword1"   => array($nickname, "#333333"),
            "keyword2"   => array($money.' 元', "#333333"),
            "keyword3"   => array($money.' 元', "#333333"),
            "keyword4"   => array($time, "#333333"),
            //"Remark"   => array("订单号：$orderNum", "#333333"),
        );

        $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
    }

    //消息推送（管理员）
    public function topupMsgs($openid,$nickname,$money,$sn,$time)
    {
        $notice = new Notice(Config::get('web.WE_APPID'), Config::get('web.WE_SECRET'));
        $templateId = 'XhaqtqM1ptmsSTZZ3naTBtT103c6Vnjue0GIZ_MCAdg';//通过addTemplate方法获取

        $data = array(
            "first"    => array("4989 West Market VIP topup success notice", '#333333'),
            "keyword1"   => array($nickname, "#333333"),
            "keyword2"   => array($money.' 元', "#333333"),
            "keyword3"   => array($sn, "#333333"),
            "keyword4"   => array($time, "#333333"),
            //"Remark"   => array("订单号：$orderNum", "#333333"),
        );

        $notice->uses($templateId)->andData($data)->andReceiver($openid)->send();
    }

    public function order()
    {
        $input = Input::only('select');

        $mid = session('mid');

        $perpage = 10;//每页显示记录数

        $dataCount = DB::table('orders') -> where(['member_id'=>$mid]) -> where(function($query) use($input){
            switch ($input['select']) {
                case '1':
                    $query -> where(['is_pay'=>'0']);
                    break;
                case '2':
                    $query -> where(['is_pay'=>'1','is_send'=>'0']);
                    break;
                case '3':
                    $query -> where(['is_pay'=>'1','is_send'=>'1']);
                    break;
                case '4':
                    $query -> where(['is_pay'=>'1','is_send'=>'2']);
                    break;
            }
        }) -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage, ['pageNum'=>'3']);
        $pageShow = $page -> fpage(true);//制作分页html

        $orderData = DB::table('orders') -> select(['orders.*', 'members.nickname']) -> where(['orders.member_id'=>$mid, 'orders.is_charge'=>'0']) -> where(function($query) use($input){
            switch ($input['select']) {
                case '1':
                    $query -> where(['orders.is_pay'=>'0']);
                    break;
                case '2':
                    $query -> where(['orders.is_pay'=>'1','is_send'=>'0']);
                    break;
                case '3':
                    $query -> where(['orders.is_pay'=>'1','is_send'=>'1']);
                    break;
                case '4':
                    $query -> where(['orders.is_pay'=>'1','is_send'=>'2']);
                    break;
            }
        }) -> leftJoin('members', 'orders.member_id', '=', 'members.id') -> orderBy('orders.id','DESC') -> offset($page->getOffset()) -> limit($perpage) -> get();

        $_orderGoodsData = DB::table('order_goods') -> select('order_goods.*','goods.brand_id as brand_id','brands.name as brand_name') -> leftJoin('goods','goods.id','=','order_goods.goods_id') -> leftJoin('brands','brands.id','=','goods.brand_id') -> where('member_id',$mid) -> get();

        foreach($orderData as $v){
            //订单状态
            if($v->is_pay == 0){
                $status = 'Pending Payment';
            }else{
                switch($v->is_send){
                    case 0:
                        $status = 'Pending Shipment';
                        break;
                    case 1:
                        $status = 'Shipped';
                        break;
                    case 2:
                        $status = 'Completed';
                        break;
                    default:
                        $status = 'error';
                }
            }
            $v->status = $status;
            //订单按钮
            if($v->is_pay == 0){
                $button = 'select1';
            }else{
                switch($v->is_send){
                    case 0:
                        $button = 'select2';
                        break;
                    case 1:
                        $button = 'select3';
                        break;
                    case 2:
                        $button = 'select4';
                        break;
                    default:
                        $button = 'error';
                }
            }
            $v->button = $button;
            //订单商品
            foreach($_orderGoodsData as $k1=>$v1){
                if($v->id == $v1->order_id){
                    $v->order_goods[$v1->brand_name][] = $v1;
                }
            }
        }

        return view('home.index.order', compact('orderData','dataCount','pageShow'));
    }

    public function orderExpress($express_num,$express)
    {
        $key = 'NAAGOtCM3101';

        $data = array();

        $data['customer'] = '49146167805039136F9BFBBBE53D8EE9';
        $data["param"] = '{"com":"'.$express.'","num":"'.$express_num.'"}';
        $data["sign"] = md5($data["param"].$key.$data["customer"]);
        $data["sign"] = strtoupper($data["sign"]);

        $o="";
        foreach ($data as $k=>$v)
        {
            $o .= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
        }

        $data = substr($o,0,-1);

        $url='https://poll.kuaidi100.com/poll/query.do';

        $res = curl_post($url, $data);

        $expressData = $res['data'];

        $expressName = kuaidiTransfrom($express);

        return view('home.index.order_express', compact('expressData', 'expressName'));
    }

    public function address()
    {
        $mid = session('mid');

        $addressData = DB::table('address') -> orderBy('id','ASC') -> where('member_id',$mid) -> get();

        $expressData = DB::table('expresses') -> select('province_name','province') -> get();

        return view('home.index.address',compact('addressData','expressData'));
    }

    public function addressEdit($id)
    {
        $mid = session('mid');

        $addressData = DB::table('address') -> where(['member_id'=>$mid, 'id'=>$id]) -> first();

        $expressData = DB::table('expresses') -> select('province_name','province') -> get();

        return view('home.index.address_edit', compact('addressData','expressData'));
    }

    public function favor()
    {
        $mid = session('mid');

        $favorData = DB::table('favors') -> select('favors.*','goods.name','goods.other_name','goods.video','goods.poster','goods.image','goods.price_name','goods.org_price_name','goods.price','goods.org_price','goods.sale','goods.weight','goods.brand_id as goods_brand_id','goods.limit_num','goods.stock', 'goods.special','brands.name as brand_name','brands.image as brand_image') -> leftJoin('goods','goods.id','=','favors.goods_id') -> leftJoin('brands','brands.id','=','goods.brand_id') -> where('favors.member_id',$mid) -> where('favors.goods_id','!=','0') -> get();

        foreach($favorData as $k=>$v){
            if(!$v->name){
                unset($favorData[$k]);
            }
        }
        return view('home.index.favor', compact('favorData'));
    }

    public function favorBrand()
    {
        $mid = session('mid');

        $favorData = DB::table('favors') -> select('favors.*','brands.name','brands.image',DB::raw('COUNT(la_goods.id) as goods_count')) -> leftJoin('brands','brands.id','=','favors.brand_id') -> leftJoin('goods','brands.id','=','goods.brand_id') -> where('favors.member_id',$mid) -> where('favors.brand_id','!=','0') -> groupBy('brands.id') -> get();

        foreach($favorData as $k=>$v){
            if(!$v->name){
                unset($favorData[$k]);
            }
        }

        return view('home.index.favor_brand', compact('favorData'));
    }

    //收藏品牌
    public function ajaxBrandFavor()
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please login';
            return $data;
        }

        $input = Input::only('brand_id');

        $input['member_id'] = $mid;
        $input['goods_id'] = 0;

        if(!$input['brand_id'] || $input['brand_id'] == '0'){
            $data['status'] = 1;
            $data['msg'] = 'Get ID failed';
            return $data;
        }

        $favorData = DB::table('favors') -> where(array('member_id'=>$mid, 'brand_id'=>$input['brand_id'])) -> first();

        if(!$favorData){
            $result = DB::table('favors') -> insert($input);
        }else{
            $data['status'] = 2;
            $data['msg'] = 'Has been exists';
            return $data;
        }

        if($result){
            $data['status'] = 0;
            $data['msg'] = 'success';
        }else{
            $data['status'] = 3;
            $data['msg'] = 'fail';
        }

        return $data;
    }
    //收藏商品
    public function ajaxGoodsFavor()
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please login';
            return $data;
        }

        $input = Input::only('goods_id');
        $input['member_id'] = $mid;
        $input['brand_id'] = 0;

        if(!$input['goods_id'] || $input['goods_id'] == '0'){
            $data['status'] = 1;
            $data['msg'] = 'Get ID failed';
            return $data;
        }

        $favorData = DB::table('favors') -> where(array('member_id'=>$mid, 'goods_id'=>$input['goods_id'])) -> first();

        if(!$favorData){
            $result = DB::table('favors') -> insert($input);
        }else{
            $data['status'] = 2;
            $data['msg'] = 'Has been exists';
            return $data;
        }

        if($result){
            $data['status'] = 0;
            $data['msg'] = 'success';
        }else{
            $data['status'] = 3;
            $data['msg'] = 'fail';
        }

        return $data;
    }
    //加入购物车
    public function ajaxAddCart()
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $fromPage = Input::only('from_page');

            if ($fromPage == 'goods_view') {
                session(['add_cart_behavior'=>'1']);
            }
            
            $data['status'] = 691;
            $data['msg'] = 'Please login';
            return $data;
        }

        $input = Input::only('goods_id','goods_num');
        $_input = Input::only('goods_stock');

        if(!$input['goods_id'] || $input['goods_id'] == '0'){
            $data['status'] = 1;
            $data['msg'] = 'Get ID failed';
            return $data;
        }

        if(!$input['goods_num'] || $input['goods_num'] == '0'){
            $data['status'] = 2;
            $data['msg'] = 'number error';
            return $data;
        }

        if(!$_input['goods_stock'] || $_input['goods_stock'] == '0'){
            $data['status'] = 3;
            $data['msg'] = 'Out of stock';
            return $data;
        }

        $input['member_id'] = $mid;

        $cartData = DB::table('carts') -> where(['member_id'=>$mid, 'goods_id'=>$input['goods_id']]) -> first();

        if(!$cartData){
            $result = DB::table('carts') -> insert($input);
        }else{
            $result = DB::table('carts') -> where('id', $cartData->id) -> increment('goods_num', $input['goods_num']);
        }

        if($result){
            $data['status'] = 0;
            $data['msg'] = 'Add successfully';
        }else{
            $data['status'] = 4;
            $data['msg'] = 'Add failed';
        }

        return $data;
    }
    //立即购买
    public function ajaxAddBuy()
    {
        $input = Input::only('goods_id', 'goods_num');
        $_input = Input::only('goods_stock');

        $data = array();

        if(!$input['goods_id'] || $input['goods_id'] == '0'){
            $data['status'] = 1;
            $data['msg'] = 'get ID failed';
            return $data;
        }

        if(!$input['goods_num'] || $input['goods_num'] == '0'){
            $data['status'] = 2;
            $data['msg'] = 'number error';
            return $data;
        }

        if(!$_input['goods_stock'] || $_input['goods_stock'] == '0'){
            $data['status'] = 3;
            $data['msg'] = 'Out of stock';
            return $data;
        }

        $data['status'] = 0;
        $data['msg'] = 'success';

        session([ 'order'=>['type'=>'index','goods'=>['goods_id'=>$input['goods_id'], 'goods_num'=>$input['goods_num']]]]);

        return $data;
    }
    //商品数量变更
    public function ajaxUpdateCart()
    {
        $input = Input::only('cart_id','goods_id','goods_num');

        $mid = session('mid');

        if($mid){
            DB::table('carts') -> where(['member_id'=>$mid, 'id'=>$input['cart_id'], 'goods_id'=>$input['goods_id']]) -> update(['goods_num'=>$input['goods_num']]);
        }
    }
    //删除商品
    public function ajaxDeleteCart()
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please login';
            return $data;
        }

        $input = Input::only('cart_id','goods_id');

        $result = DB::table('carts') -> where(['member_id'=>$mid, 'id'=>$input['cart_id'], 'goods_id'=>$input['goods_id']]) -> delete();

        if($result){
            $data['status'] = 0;
            $data['msg'] = 'success';
        }else{
            $data['status'] = 1;
            $data['msg'] = 'failed';
        }
        return $data;
    }
    //提交订单
    public function ajaxCartAddBuy()
    {
        $input = Input::only('cart_id');

        $data = array();

        if($input['cart_id'] == '') {
            $data['status'] = 1;
            $data['msg'] = 'Please select goods';
            return $data;
        }else {
            $data['status'] = 0;
            $data['msg'] = 'success';
        }

        session([ 'order'=>['type'=>'cart','cart_id'=>$input['cart_id']]]);
        session(['cart_id'=>$input['cart_id']]);

        return $data;
    }
    //余额支付
    public function ajaxBuyVipPay()
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please login';
            return $data;
        }

        $input = Input::only('address_id');

        $buyData = session('buyData');

        $express_price = session('express_price');

        if(!$buyData) {
            $data['status'] = 1;
            $data['msg'] = 'Back to home';
            return $data;
        }

        if(!$mid) {
            $data['status'] = 2;
            $data['msg'] = 'Refresh';
            return $data;
        }

        if(!$input['address_id']) {
            $data['status'] = 3;
            $data['msg'] = 'Please select address';
            return $data;
        }

        $address = DB::table('address') -> where(['member_id'=>$mid,'id'=>$input['address_id']]) -> first();

        if(!$address) {
            $data['status'] = 4;
            $data['msg'] = 'Address not found';
            return $data;
        }
        $address->address = $address->province_name.' : '.$address->address;

        $addtime = time();

        $allPrice = 0;
        foreach($buyData as $v) {
            foreach ($v as $v1) {
                $allPrice += $v1->goods_price * $v1->goods_num;
            }
        }

        $orderNum = date('Ymdhis') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

        DB::beginTransaction();

        foreach($buyData as $v){
            foreach($v as $v1){
                $stock = DB::table('goods') -> select('name','stock') -> where('id',$v1->goods_id) -> first();
                if($stock->stock < $v1->goods_num){
                    DB::rollBack();
                    $data['status'] = '1';
                    $data['msg'] = '"'.$stock->name.'"<br />Out of stock';
                    return $data;
                }
            }
        }

        $money = DB::table('members') -> select('money') -> where('id',$mid) -> first();

        if($money->money < $allPrice+$express_price){
            DB::rollBack();
            $data['status'] = 7;
            $data['msg'] = "Dear Member, your balance is insufficient!\nPress 'Confirm' to enter the recharge page.";
            return $data;
        }elseif($money->money >= $allPrice+$express_price){
            DB::table('members') -> select('money') -> where('id',$mid) -> decrement('money',$allPrice+$express_price);
            $left_money = DB::table('members') -> select('money') -> where('id',$mid) -> first();
            if($left_money->money == 0){
                DB::table('members') -> where('id',$mid) -> update(['is_vip'=>0]);
            }
        }

        $result = DB::table('orders') -> insertGetId(['member_id'=>$mid,'order_num'=>$orderNum,'express_price'=>$express_price,'all_price'=>$allPrice,'addtime'=>$addtime,'name'=>$address->name,'phone'=>$address->phone,'address'=>$address->address,'pay_method'=>'2','is_pay'=>1]);

        if($result){
            foreach($buyData as $v){
                foreach($v as $v1) {
                    $result2 = DB::table('order_goods') -> insert(['member_id'=>$v1->member_id,'order_id'=>$result,'goods_id'=>$v1->goods_id,'name'=>$v1->goods_name,'image'=>$v1->goods_image,'price'=>$v1->goods_price,'weight'=>$v1->goods_weight,'goods_num'=>$v1->goods_num]);
                    DB::table('goods') -> where('id',$v1->goods_id) -> decrement('stock',$v1->goods_num);

                    if(!$result2){
                        DB::rollBack();
                        $data['status'] = 6;
                        $data['msg'] = 'error';
                        return $data;
                    }
                }
            }
        }else{
            DB::rollBack();
            $data['status'] = 5;
            $data['msg'] = 'error';
            return $data;
        }

        if(session('cart_id')){
            foreach(session('cart_id') as $v){
                DB::table('carts') -> where('id',$v) -> delete();
            }
        }
        DB::commit();

        $this->cashFlow('1', $mid, $allPrice+$express_price);

        $memberData = DB::table('members') -> select('nickname','openid') -> where('id',$mid) -> first();
        $orderGoodsData = DB::table('order_goods') -> where('order_id',$result) -> orderBy('id','DESC') -> first();
        $orderGoodsCount = DB::table('order_goods') -> where('order_id',$result) -> count();
        $_allPrice = $allPrice+$express_price;

        session(['mail'=>$memberData->nickname.'&&'.$orderNum.'&&'.$_allPrice.'&&'.$addtime.'&&'.$address->name.'&&'.$address->address.'&&'.$address->phone]);
        session(['message'=>$memberData->openid.'&&'.$_allPrice.'&&'.$orderGoodsData->name.'&&'.$orderGoodsCount.'&&'.$orderNum]);

        $data['status'] = '0';
        $data['msg'] = 'success';

        return $data;
    }

    //余额付款完成
    public function payok()
    {
        if (session('message')) {
            $message = session('message');
            $message = explode('&&', $message);
            $orderNum = $message[4];
            session(['current_order_num'=>$orderNum]);
        } else {
            $orderNum = session('current_order_num');
        }

        return view('home.index.payok', compact('orderNum'));
    }

    public function ajaxShareReturnMoney(Request $request)
    {
        $data = array();
        $input = Input::only('order_num');

        if ($input['order_num']) {
            $mid = session('mid');

            $orderExists = DB::table('orders')->where(['order_num'=>$input['order_num'], 'member_id'=>$mid])->count();

            if (!$orderExists) {
                $data['status'] = 1;
                $data['msg'] = 'error';
                return $data;
            }

            $shared = DB::table('share_returns')->where(['order_num'=>$input['order_num'], 'member_id'=>$mid])->count();

            if ($shared) {
                $data['status'] = 2;
                $data['msg'] = 'Thank you for your sharing.';
                return $data;
            }

            $payok_return_money = $this->SYSTEM['payok_return_money'];
            $ip = $request->getClientIp();
            $transfer = new Transfer(Config::get('web.PUBLIC_APPID'), Config::get('web.MCHID'), Config::get('web.KEY'), $ip);

            $member = DB::table('members')->select('openid')->where('id', $mid)->first();
            $desc = '4989 West Market Shopping Cashback';

            $res = $transfer->sendMoney($payok_return_money, $member->openid, $desc);

            if($res['return_code'] != 'SUCCESS'){
                $data['status'] = 3;
                $data['msg'] = 'error';
                return $data;
            }

            if($res['result_code'] != 'SUCCESS'){
                $data['status'] = 4;
                $data['msg'] = $res['err_code_des'];
                return $data;
            }

            DB::table('share_returns')->insert(['order_num'=>$input['order_num'], 'member_id'=>$mid, 'money'=>$payok_return_money]);

            $data['status'] = 0;
            $data['msg'] = number_format($payok_return_money, 2, '.', '');
            return $data;
        } else {
            $data['status'] = 4;
            $data['msg'] = 'error';
            return $data;
        }
    }

    //余额付款完成
    public function ajaxPayok()
    {
        $mail = session('mail');
        $message = session('message');

        session([
            'mail'=>null,
            'message'=>null,
        ]);

        if($mail && $message){
            $mail = explode('&&', $mail);
            $message = explode('&&', $message);
            $this->Message($message[0],$message[1],$message[2],$message[3],$message[4]);

            $_openids = $this->SYSTEM['openids'];
            $openids = explode(';', $_openids);

            foreach ($openids as $v) {
                if (!$v) continue;
                $this->adminMsg(trim($v),$mail[0],$mail[1],$mail[2],date('Y-m-d H:i',$mail[3]),$mail[4],$mail[5],$mail[6]);
            }
            // $this->partnerEmail($mail[1]);

            /*
            $email = DB::table('email') -> first();
            $emailArr = explode('&', $email->email);
            foreach($emailArr as $v){
                $this->sendMail($v,$mail[0],$mail[1],$mail[2]);
            }
            */
        }
    }
    //微信支付
    public function ajaxBuyPay()
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please login';
            return $data;
        }

        $input = Input::only('address_id');

        $buyData = session('buyData');

        $express_price = session('express_price');

        if(!$buyData) {
            $data['status'] = 1;
            $data['msg'] = 'Back to home';
            return $data;
        }

        if(!$mid) {
            $data['status'] = 2;
            $data['msg'] = 'Refresh';
            return $data;
        }

        if(!$input['address_id']) {
            $data['status'] = 3;
            $data['msg'] = 'Please select address';
            return $data;
        }

        $address = DB::table('address') -> where(['member_id'=>$mid,'id'=>$input['address_id']]) -> first();

        if(!$address) {
            $data['status'] = 4;
            $data['msg'] = 'Address not found';
            return $data;
        }
        $address->address = $address->province_name.' : '.$address->address;

        $addtime = time();

        $allPrice = 0;
        foreach($buyData as $v) {
            foreach ($v as $v1) {
                $allPrice += $v1->org_price * $v1->goods_num;
            }
        }

        $orderNum = date('Ymdhis') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

        $this->fp = fopen(public_path('order.lock'), 'r');
        flock($this->fp, LOCK_EX);

        DB::beginTransaction();
        foreach($buyData as $v){
            foreach($v as $v1){
                $stock = DB::table('goods') -> select('name','stock') -> where('id',$v1->goods_id) -> first();
                if($stock->stock < $v1->goods_num){
                    DB::rollBack();
                    $data['status'] = '1';
                    $data['msg'] = '"'.$stock->name.'"<br />Out of stock';
                    return $data;
                }
            }
        }
        foreach($buyData as $v){
            foreach($v as $v1){
                DB::table('goods') -> where('id',$v1->goods_id) -> decrement('stock',$v1->goods_num);
            }
        }

        $result = DB::table('orders') -> insertGetId(['member_id'=>$mid,'order_num'=>$orderNum,'express_price'=>$express_price,'all_price'=>$allPrice,'addtime'=>$addtime,'name'=>$address->name,'phone'=>$address->phone,'address'=>$address->address]);

        foreach($buyData as $v){
            if($result){
                foreach($v as $v1) {
                    $result2 = DB:: table('order_goods') -> insert(['member_id'=>$v1->member_id,'order_id'=>$result,'goods_id'=>$v1->goods_id,'name'=>$v1->goods_name,'image'=>$v1->goods_image,'price'=>$v1->org_price,'weight'=>$v1->goods_weight,'goods_num'=>$v1->goods_num]);

                    if(!$result2){
                        DB::rollBack();
                        $data['status'] = 6;
                        $data['msg'] = 'error';
                        return $data;
                    }
                }
            }else{
                DB::rollBack();
                $data['status'] = 5;
                $data['msg'] = 'error';
                return $data;
            }
        }
        if(session('cart_id')){
            foreach(session('cart_id') as $v){
                $result3 = DB::table('carts') -> where('id',$v) -> delete();
            }
            if(!$result3){
                DB::rollBack();
                $data['status'] = 16;
                $data['msg'] = 'error';
                return $data;
            }
        }
        DB::commit();

        flock($this->fp, LOCK_UN);

        $encryptOrderNum = Crypt::encrypt($orderNum);

        $data['status'] = '0';
        $data['msg'] = 'success';
        $data['order_num'] = $encryptOrderNum;

        return $data;

    }

    //立即支付
    public function ajaxOrderPay()
    {
        $input = Input::only('order_num');

        $data = array();

        if(!$input['order_num']) {
            $data['status'] = 1;
            $data['msg'] = 'error';
            return $data;
        }

        $encryptOrderNum = Crypt::encrypt($input['order_num']);
        $data['status'] = '0';
        $data['msg'] = 'success';
        $data['order_num'] = $encryptOrderNum;
        return $data;
    }
    //成功回调
    public function weNotify($order_num)
    {
        if(!strpos($order_num, md5(Config::get('web.PAYMENT_KEY')))){
            Log::info('valid failed');
            return 'fail';
        }
        $orderNum = substr($order_num, 0, 20);

        $orderData = DB::table('orders') -> where('order_num', $orderNum) -> first();
        if($orderData->is_pay == '1'){
            //Log::info('订单号为 ' . $orderNum . ' 订单支付已完成');
            return 'fail';
        }
        if($orderData->is_charge == 1){
            $result = DB::table('orders') -> where('order_num', $orderNum) -> update(['is_pay'=>'1','is_send'=>'2']);
            if($result){
                DB::table('members') -> where('id', $orderData->member_id) -> increment('money', $orderData->all_price);
            }
        }else{
            $result = DB::table('orders') -> where('order_num', $orderNum) -> update(['is_pay'=>'1']);
        }
        if($result){
            Log::info('Order number ' . $orderNum . ' payment successfully');

            DB::table('members') -> where('id', $orderData->member_id) -> increment('amount', $orderData->all_price + $orderData->express_price);

            $memberData = DB::table('members') -> select('id', 'nickname','openid') -> where('id',$orderData->member_id) -> first();
            $allPrice = $orderData->all_price + $orderData->express_price;

            $this->cashFlow('1', $memberData->id, $allPrice);

            $orderGoodsData = DB::table('order_goods') -> where('order_id',$orderData->id) -> orderBy('id','DESC') -> first();
            $orderGoodsCount = DB::table('order_goods') -> where('order_id',$orderData->id) -> count();

            $_openids = $this->SYSTEM['openids'];
            $openids = explode(';', $_openids);

            foreach ($openids as $v) {
                if (!$v) continue;
                $this->adminMsg(trim($v),$memberData->nickname,$orderNum,$allPrice,date('Y-m-d H:i',$orderData->addtime),$orderData->name,$orderData->address,$orderData->phone);
            }

            /*
            $email = DB::table('email') -> first();
            $emailArr = explode('&', $email->email);
            foreach($emailArr as $v){
                $this->sendMail($v,$memberData->nickname,$orderNum,$allPrice);
            }
            */

            //$this->partnerEmail($orderNum);
            $this->Message($memberData->openid,$allPrice,$orderGoodsData->name,$orderGoodsCount,$orderNum);

        }else{
            Log::info('Order number ' . $orderNum . ' payment failed');
        }
    }
    //确认订单
    public function ajaxOrderCheck()
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please Login';
            return $data;
        }

        $input = Input::only('order_id');

        $result = DB::table('orders') -> where(['member_id'=>$mid,'id'=>$input['order_id']]) -> update(['is_pay'=>'1','is_send'=>'2']);

        if($result !== false) {
            $data['status'] = 0;
            $data['msg'] = 'success';
            return $data;
        }else {
            $data['status'] = 1;
            $data['msg'] = 'failed';
            return $data;
        }
    }
    //取消订单
    public function ajaxOrderDelete()
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please login';
            return $data;
        }

        $input = Input::only('order_id');

        $result = DB::table('orders') -> where(['member_id'=>$mid,'id'=>$input['order_id']]) -> delete();

        $result2 = DB::table('order_goods') -> where(['member_id'=>$mid,'order_id'=>$input['order_id']]) -> delete();

        if($result !== 0 && $result2 !== 0) {
            $data['status'] = 0;
            $data['msg'] = 'success';
        }else {
            $data['status'] = 1;
            $data['msg'] = 'failed';
        }
        return $data;
    }
    //选择默认地址
    public function ajaxSelectAddress()
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please login';
            return $data;
        }

        $input = Input::only('id', 'is_default');

        DB::table('address') -> where(['member_id'=>$mid]) -> update(['is_default'=>'0',]);

        $result = DB::table('address') -> where(['member_id'=>$mid, 'id'=>$input['id']]) -> update(['is_default'=>'1',]);

        if($result){
            $data['status'] = 0;
            $data['msg'] = 'success';
        }else {
            $data['status'] = 1;
            $data['msg'] = 'failed';
        }
        return $data;
    }
    //添加新地址
    public function ajaxAddAddress()
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please Login';
            return $data;
        }

        $input = Input::only('name', 'phone','province','address','is_default');

        if($input['name'] == ''){
            $data['status'] = 1;
            $data['msg'] = 'Please input receiver name';
            return $data;
        }

        if($input['phone'] == ''){
            $data['status'] = 1;
            $data['msg'] = 'Please input receiver phone';
            return $data;
        }

        if($input['province'] == 'null'){
            $data['status'] = 1;
            $data['msg'] = 'Please input receiver province';
            return $data;
        }

        if($input['address'] == ''){
            $data['status'] = 1;
            $data['msg'] = 'Please input receiver address';
            return $data;
        }

        $province = explode('@', $input['province']);

        if($input['is_default'] == '1'){
            DB::table('address') -> where(['member_id'=>$mid]) -> update(['is_default'=>'0']);
        }

        $result = DB::table('address') -> where(['member_id'=>$mid]) -> insertGetId(['member_id' => $mid,'name' => $input['name'],'phone'=> $input['phone'],'province_name'=> $province[1],'province'=> $province[0],'address' => $input['address'],'is_default' => $input['is_default']]);

        if($result){
            $data['status'] = 0;
            $data['msg'] = 'success';
            $data['id'] =$result;
        }else {
            $data['status'] = 1;
            $data['msg'] = 'error';
        }
        return $data;
    }
    //删除地址
    public function ajaxDeleteAddress(){
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please login';
            return $data;
        }

        $input = Input::only('id');

        $default = DB::table('address') -> select('is_default') -> where(['member_id'=>$mid, 'id'=>$input['id']]) -> first();

        $result = DB::table('address') -> where(['member_id'=>$mid, 'id'=>$input['id']]) -> delete();

        if($default->is_default == 1){

            $id = DB::table('address') -> select('id') -> where(['member_id'=>$mid]) -> where('id','!=',$input['id']) -> first();

            if($id){
                DB::table('address') -> where(['member_id'=>$mid, 'id'=>$id->id]) -> update(['is_default'=>1]);
                $data['id'] = $id->id;
            }
        }

        if($result){
            $data['status'] = 0;
            $data['msg'] = 'success';
        }else{
            $data['status'] = 1;
            $data['msg'] = 'failed';
        }
        return $data;
    }
    //修改地址
    public function ajaxEditAddress()
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please login';
            return $data;
        }

        $input = Input::only('id','name','phone','province','address');

        if($input['name'] == ''){
            $data['status'] = 1;
            $data['msg'] = 'Please input receiver name';
            return $data;
        }

        if($input['phone'] == ''){
            $data['status'] = 1;
            $data['msg'] = 'Please input receiver phone';
            return $data;
        }

        if($input['province'] == 'null'){
            $data['status'] = 1;
            $data['msg'] = 'Please select receiver province';
            return $data;
        }

        if($input['address'] == ''){
            $data['status'] = 1;
            $data['msg'] = 'Please input receiver address';
            return $data;
        }

        $province = explode('@', $input['province']);

        $result = DB::table('address') -> where(['member_id'=>$mid, 'id'=>$input['id']]) -> update(['name' => $input['name'], 'phone' => $input['phone'],'province_name'=> $province[1],'province'=> $province[0], 'address' => $input['address']]);


        if($result !== false){
            $data['status'] = 0;
            $data['msg'] = 'success';
        }else {
            $data['status'] = 1;
            $data['msg'] = 'failed';
        }
        return $data;
    }
    //删除收藏
    public function ajaxFavorDelete(){
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please login';
            return $data;
        }

        $input = Input::only('id');

        $result = DB::table('favors') -> where(['member_id'=>$mid, 'id'=>$input['id']]) -> delete();

        if($result){
            $data['status'] = 0;
            $data['msg'] = 'success';
        }else{
            $data['status'] = 1;
            $data['msg'] = 'error';
        }
        return $data;
    }

    public function suggestView()
    {
        return view('home.index.suggest');
    }

    public function suggest()
    {
        $data = array();

        $mid = session('mid');
        if(!$mid){
            $data['status'] = 691;
            $data['msg'] = 'Please login';
            return $data;
        }

        $input = Input::only('types', 'content', 'image');

        if(!$input['content']){
            $data['status'] = 1;
            $data['msg'] = 'Please input content';
            return $data;
        }
        if(strlen($input['content']) < 8){
            $data['status'] = 2;
            $data['msg'] = 'Content is too short';
            return $data;
        }

        $input['member_id'] = $mid;

        if($input['image']){
            $jssdk = new Jssdk(Config::get('web.WE_APPID'), Config::get('web.WE_SECRET'));
            $access_token = $jssdk->getAccessToken();
            $images = '';

            foreach(explode('@', $input['image']) as $k=>$v){
                if($v != ''){
                    if($k != 0){
                        $images .= '@';
                    }

                    $images .= $this -> getMedia($access_token, $v, 'images');
                }
            }

            $input['image'] = $images;
        }

        $input['addtime'] = time();

        $result = DB::table('wishes') -> insertGetId($input);

        if($result){
            $data['status'] = 0;
            $data['suggest_id'] = $result;
            $data['msg'] = 'success';
        }else{
            $data['status'] = 3;
            $data['msg'] = 'error';
        }

        return $data;
    }

    public function ads()
    {
        $perpage = 10;

        $bannerData = DB::table('banners') -> where('position','ads') -> where('is_use',1) -> orderBy('sort', 'DESC') -> orderBy('id','DESC') -> get();
        $adsData = DB::table('ads') -> orderBy('sort', 'DESC') -> orderBy('id','DESC') -> limit($perpage) -> get();

        return view('home.index.ads', compact('bannerData', 'adsData'));
    }

    public function getAds()
    {
        $input = Input::only('page');

        $lists = 10;
        $perpage = 10;
        $offset = $lists + $input['page'] * $perpage;

        $adsData = DB::table('ads') -> orderBy('sort', 'DESC') -> orderBy('id','DESC') -> offset($offset) -> limit($perpage) -> get();

        return $adsData;
    }

    private function getMedia($access_token, $media_id, $foldername){
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;
        if (!file_exists(public_path()."/upload/admin/".$foldername)) {
            mkdir(public_path()."/upload/admin/".$foldername, 0777, true);
        }

        $fileName = date('YmdHis').mt_rand(1000,9999).'.jpg';
        $targetName = public_path().'/upload/admin/'.$foldername.'/'.$fileName;

        $ch = curl_init($url); // 初始化
        $fp = fopen($targetName, 'wb'); // 打开写入
        curl_setopt($ch, CURLOPT_FILE, $fp); // 设置输出文件的位置，值是一个资源类型
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        return 'upload/admin/'.$foldername.'/'.$fileName;
    }

    public function sendSuggestMsg($suggest_id)
    {
        $suggest = DB::table('wishes') -> select(['wishes.*', 'members.openid', 'members.nickname']) -> leftJoin('members', 'wishes.member_id', '=', 'members.id') -> where('wishes.id', $suggest_id) -> first();

        //发送模板消息
        $_openids = $this->SYSTEM['openids'];
        $openids = explode(';', $_openids);

        foreach ($openids as $v) {
            if (!$v) continue;
            $this->suggestMsg($v, $suggest->nickname, date('Y-m-d H:i:s', $suggest->addtime), $suggest->content);
        }
    }

    //投诉建议发送邮箱
    public function suggestMail($email,$nickname,$wish)
    {
        Config::set('mail.driver', Config::get('web.MAIL_DRIVER'));
        Config::set('mail.host', Config::get('web.MAIL_HOST'));
        Config::set('mail.port', Config::get('web.MAIL_PORT'));
        Config::set('mail.username', Config::get('web.MAIL_USERNAME'));
        Config::set('mail.password', Config::get('web.MAIL_PASSWORD'));
        Config::set('mail.encryption', Config::get('web.MAIL_ENCRYPTION'));
        Config::set('mail.from', ['address'=>Config::get('web.MAIL_ADDRESS'), 'name'=>Config::get('web.MAIL_NAME')]);

        $emailData = array();
        $emailData['email'] = $email;
        $emailData['name'] = $nickname;
        $emailData['subject'] = Config::get('web.MAIL_SUBJECT');

        Mail::send('home.index.suggest_email', ['emailData' => $wish], function ($m) use ($emailData) {
            $m->to($emailData['email'], $emailData['name'])->subject($emailData['subject']);
        });
    }

    //投诉模板消息推送
    public function suggestMsg($memberOpenid,$nickname,$time,$content)
    {
        $notice = new Notice(Config::get('web.WE_APPID'), Config::get('web.WE_SECRET'));
        $userId = $memberOpenid;
        $templateId = 'AKPxaLSbJddkSOWsnVTZVCPJ_1hylnFbOVT-K_PfIpM';//通过addTemplate方法获取
        $url = url('index');

        $data = array(
            "first"    => array('4989 West Market Complaint & Suggestion', '#333333'),
            "keyword1"   => array($nickname, "#333333"),
            "keyword2"   => array('未知', "#333333"),
            "keyword3"   => array($time, "#333333"),
            "keyword4"   => array($content, "#333333"),
            "Remark"   => array("-", "#333333"),
        );

        $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
    }

    //点赞图片上传页
    public function praiseView()
    {
        return view('home.index.praise');
    }

    //点赞图片上传页提交
    public function praise()
    {
        $data = array();
        $input = Input::only('content', 'image');

        if(!$input['content']){
            $data['status'] = 1;
            $data['msg'] = 'Please input address and phone';
            return $data;
        }

        /*
        if(!$input['image']){
            $data['status'] = 2;
            $data['msg'] = '请上传点赞截图图片';
            return $data;
        }
        */

        $input['member_id'] = session('mid');

        $input['addtime'] = time();

        /*
        $result = DB::table('praises') -> insertGetId($input);

        if($result){
            $data['status'] = 0;
            $data['praise_id'] = $result;
            $data['msg'] = '操作成功';
        }else{
            $data['status'] = 3;
            $data['msg'] = '操作失败，请稍候再试';
        }

        return $data;
        */
    }

    public function sendPraiseMsg($praise_id)
    {
        /*
        $praise = DB::table('praises') -> select(['praises.*', 'members.openid', 'members.nickname']) -> leftJoin('members', 'praises.member_id', '=', 'members.id') -> where('praises.id', $praise_id) -> first();

        //发送邮件
        $email = DB::table('email') -> first();
        $emailArr = explode('&', $email->email);
        foreach($emailArr as $v){
            $this->praiseMail($v, $praise->nickname, $praise);
        }
        */
    }

    //投诉建议发送邮箱
    public function praiseMail($email,$nickname,$wish)
    {
        Config::set('mail.driver', Config::get('web.MAIL_DRIVER'));
        Config::set('mail.host', Config::get('web.MAIL_HOST'));
        Config::set('mail.port', Config::get('web.MAIL_PORT'));
        Config::set('mail.username', Config::get('web.MAIL_USERNAME'));
        Config::set('mail.password', Config::get('web.MAIL_PASSWORD'));
        Config::set('mail.encryption', Config::get('web.MAIL_ENCRYPTION'));
        Config::set('mail.from', ['address'=>Config::get('web.MAIL_ADDRESS'), 'name'=>Config::get('web.MAIL_NAME')]);

        $emailData = array();
        $emailData['email'] = $email;
        $emailData['name'] = $nickname;
        $emailData['subject'] = Config::get('web.MAIL_SUBJECT');

        Mail::send('home.index.praise_email', ['emailData' => $wish], function ($m) use ($emailData) {
            $m->to($emailData['email'], $emailData['name'])->subject($emailData['subject']);
        });
    }

    //消息推送
    public function Message($memberOpenid,$price,$orderGoodsName,$orderGoodsCount,$orderNum)
    {
        $notice = new Notice(Config::get('web.WE_APPID'), Config::get('web.WE_SECRET'));
        $userId = $memberOpenid;
        $templateId = 'RpfXNYQ9FbY5CXvzeFj5YGGaYB2qmvN9Z4XUTE01iGY';//通过addTemplate方法获取
        $url = url('order');

        $data = array(
            "first"    => array("payment successfull", '#333333'),
            "orderMoneySum"   => array("￥".$price, "#333333"),
            "orderProductName"   => array(mb_substr($orderGoodsName,0,4)."... 等 ".$orderGoodsCount."件商品", "#333333"),
            "Remark"   => array("Order number：$orderNum", "#333333"),
        );

        $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
    }

    //发送邮箱提示
    public function sendMail($email,$user,$orderNum,$price){
        Config::set('mail.driver', Config::get('web.MAIL_DRIVER'));
        Config::set('mail.host', Config::get('web.MAIL_HOST'));
        Config::set('mail.port', Config::get('web.MAIL_PORT'));
        Config::set('mail.username', Config::get('web.MAIL_USERNAME'));
        Config::set('mail.password', Config::get('web.MAIL_PASSWORD'));
        Config::set('mail.encryption', Config::get('web.MAIL_ENCRYPTION'));
        Config::set('mail.from', ['address'=>Config::get('web.MAIL_ADDRESS'), 'name'=>Config::get('web.MAIL_NAME')]);

        $emailData = array();
        $emailData['email'] = $email;
        $emailData['name'] = $user;
        $emailData['code'] = $orderNum;
        $emailData['price'] = $price;
        $emailData['subject'] = Config::get('web.MAIL_SUBJECT');

        $orderData = DB::table('orders') -> where('order_num',$orderNum) -> first();
        $orderGoodsData = DB::table('order_goods') -> select('order_goods.*','goods.brand_id as brand_id','brands.name as brand_name') -> leftJoin('goods','goods.id','=','order_goods.goods_id') -> leftJoin('brands','brands.id','=','goods.brand_id') -> where('order_id',$orderData->id) -> get();
        foreach($orderGoodsData as $k1=>$v1){
            if($orderData->id == $v1->order_id){
                $orderData->order_goods[$v1->brand_name][] = $v1;
            }
        }
        $emailData['orderData'] = $orderData;

        Mail::send('home.index.email', ['emailData' => $emailData], function ($m) use ($emailData) {
            $m->to($emailData['email'], $emailData['name'])->subject($emailData['subject']);
        });
    }

    //给管理员发送模板消息
    public function adminMsg($openid,$nickname,$sn,$price,$addtime,$receiver,$address,$phone)
    {
        $notice = new Notice(Config::get('web.WE_APPID'), Config::get('web.WE_SECRET'));
        $templateId = '6uRSunh-JhzziRVPVH1m0sPYMYFNswGOjTmtlcuELrg';//通过addTemplate方法获取
        $url = url('admin/order_view/'.$sn);

        $data = array(
            "first"    => array('来自用户 '.$nickname.' [ '.$phone.' ]有一条新的订单', '#333333'),
            "keyword1"   => array($sn, "#333333"),
            "keyword2"   => array(number_format($price, 2, '.', ''), "#333333"),
            "keyword3"   => array($addtime, "#333333"),
            "keyword4"   => array($receiver, "#333333"),
            "keyword5"   => array($address, "#333333"),
            "Remark"   => array("请管理员及时确认", "#333333"),
        );

        $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($openid)->send();
    }

    public function partnerEmail($orderNum){
        Config::set('mail.driver', Config::get('web.MAIL_DRIVER'));
        Config::set('mail.host', Config::get('web.MAIL_HOST'));
        Config::set('mail.port', Config::get('web.MAIL_PORT'));
        Config::set('mail.username', Config::get('web.MAIL_USERNAME'));
        Config::set('mail.password', Config::get('web.MAIL_PASSWORD'));
        Config::set('mail.encryption', Config::get('web.MAIL_ENCRYPTION'));
        Config::set('mail.from', ['address'=>Config::get('web.MAIL_ADDRESS'), 'name'=>Config::get('web.MAIL_NAME')]);

        $orderData = DB::table('orders') -> select(['orders.id', 'orders.name', 'orders.phone', 'orders.address', 'orders.all_price', 'members.nickname', 'partners.rate', 'partners.email']) -> where('orders.order_num',$orderNum) -> leftJoin('members', 'orders.member_id', '=', 'members.id') -> leftJoin('partners', 'members.partner_code', '=', 'partners.code') -> first();

        $emailData = array();
        $emailData['email'] = $orderData->email;
        $emailData['name'] = $orderData->nickname;
        $emailData['code'] = $orderNum;
        $emailData['price'] = $orderData->all_price;
        $emailData['subject'] = Config::get('web.MAIL_SUBJECT');

        $orderGoodsData = DB::table('order_goods') -> select('order_goods.*','goods.brand_id as brand_id','brands.name as brand_name') -> leftJoin('goods','goods.id','=','order_goods.goods_id') -> leftJoin('brands','brands.id','=','goods.brand_id') -> where('order_id',$orderData->id) -> get();
        foreach($orderGoodsData as $k1=>$v1){
            if($orderData->id == $v1->order_id){
                $orderData->order_goods[$v1->brand_name][] = $v1;
            }
        }
        $emailData['orderData'] = $orderData;

        $emails = explode(';', $emailData['email']);

        foreach($emails as $v){
            if(!$v){
                continue;
            }
            Mail::send('home.index.partner_email', ['emailData' => $emailData], function ($m, $v) use ($emailData) {
                $m->to($v, $emailData['name'])->subject($emailData['subject']);
            });
        }
    }

    private function cashFlow($type, $member_id, $money)
    {
        $cashFlow = array();
        $cashFlow['types'] = $type;
        $cashFlow['member_id'] = $member_id;
        $cashFlow['money'] = $money;
        $cashFlow['addtime'] = time();
        DB::table('cash_flows') -> insert($cashFlow);
    }
}
