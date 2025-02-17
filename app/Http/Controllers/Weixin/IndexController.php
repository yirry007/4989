<?php

namespace App\Http\Controllers\Weixin;

use App\Tool\Page\Page;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Overtrue\Wechat\Broadcast;
use Overtrue\Wechat\Group;
use Overtrue\Wechat\Media;
use Overtrue\Wechat\Menu;
use Overtrue\Wechat\MenuItem;
use Overtrue\Wechat\Message;
use Overtrue\Wechat\Notice;
use Overtrue\Wechat\Server;
use Overtrue\Wechat\Staff;
use Overtrue\Wechat\Tag;
use Overtrue\Wechat\User;

class IndexController extends Controller
{
    private $appid;
    private $appsecret;
    private $aeskey;
    private $token;
    private $origin_id;

    public function __construct(){
        $config = Cache::get('config');
        if(!$config){
            //$config = DB::table('weixins') -> where('id', session('weixin')->id) -> first();
            $config = DB::table('weixins') -> where('id', '1') -> first();

            if(!$config){
                return redirect('admin/login');
            }

            Cache::add('config', $config, 63072000);
        }

        $this->appid = $config->appid;
        $this->appsecret = $config->appsecret;
        $this->aeskey = $config->aeskey;
        $this->token = $config->token;
        $this->origin_id = $config->origin_id;
    }



    /**
     * 图标查看
     */
    public function icon()
    {
        $path = public_path().'/we/font/css/font-awesome.css';

        $file = fopen($path, 'r');

        $content = fread($file, filesize($path));

        preg_match_all('/(?:\.).*(?:\:)/iU', $content, $arr);

        unset($arr[0][0]);
        unset($arr[0][1]);
        unset($arr[0][2]);

        $icon = $arr[0];

        return view('we.icon', compact('icon'));
    }

    /**
     * 列表模板
     */
    public function lst()
    {
        return view('we.list');
    }

    /**
     * 添加数据模板
     */
    public function add()
    {
        return view('we.add');
    }

    public function index()
    {
        return view('we.index');
    }

    public function main()
    {
        $mysqlVersion = DB::select('SELECT VERSION() AS version');

        return view('we.main', compact('mysqlVersion'));
    }

    public function config()
    {
        $config = DB::table('weixins') -> where('id', session('weixin')->id) -> first();

        return view('we.config', compact('config'));
    }

    public function configSet()
    {
        $input = Input::only('appid', 'appsecret', 'token', 'aeskey', 'origin_id');

        $result = DB::table('weixins') -> where('id', session('weixin')->id) -> update($input);

        if($result !== false){

            $file = Input::file('valid_file');

            if($file && $file -> isValid()){
                $filename = $file->getClientOriginalName();
                $file->move(public_path(), $filename);

                DB::table('weixins') -> where('id', session('weixin')->id) -> update(['valid_file'=>$filename]);
            }
        }

        return redirect('weixin/main');
    }



    public function listen()
    {
        $server = new Server($this->appid, $this->token, $this->aeskey);

        //关注和取消关注
        $server->on('event', 'subscribe', [$this, 'subscribe']);
        $server->on('event', 'unsubscribe', [$this, 'unsubscribe']);

        //菜单事件
        $server->on('event', 'CLICK', function($event){
            $menuEvent = DB::table('menu_events') -> where('event', $event['EventKey']) -> first();

            if(!$menuEvent){
                return false;
            }

            $msg = $this->replyEvent($menuEvent->event_type, $menuEvent->event_value);

            return $msg;
        });

        //接收与回复消息
        $server->on('message', [$this, 'message']);

        return $server -> serve();
    }

    /**
     * 菜单，关注，自动回复时生成的消息
     * @param $event_type
     * @param $event_value
     * @return mixed
     */
    protected function replyEvent($event_type, $event_value)
    {
        switch($event_type){
            case '1':
                $msg = Message::make('text')->content($event_value);
                break;
            case '2':
                $msg = Message::make('image')->media_id($event_value);
                break;
            case '3':
                $msg = Message::make('voice')->media_id($event_value);
                break;
            case '4':
                $msg = Message::make('video')->media_id($event_value);
                break;
            case '5':
                $msg = $this->replyNews($event_value);
                break;
        }

        return $msg;
    }

    /**
     * 点击自定义菜单或自动回复发送图文素材
     */
    protected function replyNews($media_id)
    {
        $type = 'news';

        $media = new Media($this->appid, $this->appsecret);

        $_newsData = $media->lists($type);

        $newsData = null;

        foreach($_newsData['item'] as $v){
            if($v['media_id'] == $media_id){
                $newsData = $v['content']['news_item'];
                break;
            }
        }

        if(!$newsData){
            return false;
        }

        $news = Message::make('news')->items(function() use($newsData){
            $newsArr = array();

            foreach($newsData as $v){
                //假如直接跳转到原文地址，则 $v['url'] 修改为 $v['content_source_url'] 即可
                $msg = Message::make('news_item')->title($v['title'])->url($v['url'])->picUrl($v['thumb_url'])->description($v['digest']);

                array_push($newsArr, $msg);
            }

            return $newsArr;
        });

        return $news;
    }

    /**
     * 关注时执行的代码
     * @param $event
     * @return string
     */
    public function subscribe($event)
    {
        $openid = $event->FromUserName;

        $member = DB::table('members') -> where('openid', $openid) ->first();
        if($member){
            DB::table('members') -> where('id', $member->id) -> update(['is_scr'=>'1']);
        }

        $subscribe = DB::table('subscribes') -> first();

        $msg = $this->replyEvent($subscribe->event_type, $subscribe->event_value);

        return $msg;
    }

    /**
     * 设置关注事件表单
     */
    public function subscribeEventView()
    {
        $subscribe = DB::table('subscribes') -> first();

        return view('we.subscribe', compact('subscribe'));
    }

    /**
     * 设置关注事件
     */
    public function subscribeEvent()
    {
        $input = Input::only('id', 'event_type', 'event_value');

        $id = $input['id'];
        unset($input['id']);

        DB::table('subscribes') -> where('id', $id) -> update($input);

        return redirect('weixin/subscribe_event');
    }

    /**
     * 取消关注时可能修改数据库
     * @param $event
     */
    public function unsubscribe($event)
    {
        $openid = $event->FromUserName;

        $member = DB::table('members') -> where('openid', $openid) ->first();
        if($member){
            DB::table('members') -> where('id', $member->id) -> update(['is_scr'=>'0']);
        }
    }



    /**
     * 自动回复列表
     */
    public function reply()
    {
        $perpage = 20;

        $dataCount = DB::table('replies') -> count();

        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();

        $replyData = DB::table('replies') -> orderBy('id', 'DESC') -> offset($page->getOffset()) -> limit($perpage) -> get();

        return view('we.reply', compact('replyData', 'pageShow'));
    }

    /**
     * 自动回复添加表单
     */
    public function replyAddView()
    {
        return view('we.reply_add');
    }

    /**
     * 自动回复添加
     */
    public function replyAdd()
    {
        $input = Input::except('_token');

        $result = DB::table('replies') -> insert($input);

        if($result){
            return redirect('weixin/reply');
        }else{
            return back();
        }
    }

    /**
     * 自动回复修改表单
     */
    public function replyEditView($id)
    {
        $replyData = DB::table('replies') -> where('id', $id) -> first();
        return view('we.reply_edit', compact('replyData'));
    }

    /**
     * 自动回复修改
     */
    public function replyEdit($id)
    {
        $input = Input::except('_token', '_method');

        $page = $input['page'];
        unset($input['page']);

        $result = DB::table('replies') -> where('id', $id) -> update($input);

        if($result !== false){
            return redirect('weixin/reply?page='.$page);
        }else{
            return back();
        }
    }

    /**
     * 自动回复删除
     */
    public function replyDel($id)
    {
        $result = DB::table('replies') -> where('id', $id) -> delete();

        if($result !== false){
            $data = [
                'status' => 0,
                'msg' => '删除成功',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '删除失败',
            ];
        }

        return $data;
    }



    /**
     * 接收所有消息，一共有 text, image, location, link 等四个消息，这里只对 text 进行处理，其他忽略。
     * @param $msg
     * @return mixed
     */
    public function message($msg)
    {
        $type = $msg->MsgType;

        if($type != 'text'){
            return false;
        }

        $content = $msg->Content;

        $replyData = DB::table('replies') -> where('msg', $content) -> first();

        if(!$replyData){
            $replyData = DB::table('replies') -> where('msg', 'default') -> first();
        }

        if(!$replyData){
            return false;
        }

        //event_type 为 0 时呼叫客服，不再处理消息
        if($replyData->event_type == '0'){
            return Message::make('transfer');
        }

        $result = $this->replyEvent($replyData->event_type, $replyData->event_value);

        return $result;
    }


    /**
     * 根据接收的消息呼叫客服
     * @param $msg
     * @return mixed
     */
    public function _transfer($msg)
    {
        $transfer = Message::make('transfer');

        return $transfer;
    }



    /**
     * 公众号菜单修改表单
     */
    public function menu()
    {
        $menuService = new Menu($this->appid, $this->appsecret);

        $_menuData = $menuService->get();

        $menuData = $_menuData['menu']['button'];

        return view('we.menu', compact('menuData'));
    }

    /**
     * 公众号菜单修改表单
     */
    public function menuEdit()
    {
        $input = Input::except('_token');

        $menus = array();

        foreach($input['name'] as $k=>$v){
            if($v == '') continue;

            $menus[$k]['name'] = $v;
            $menus[$k]['type'] = array_key_exists('type', $input) && array_key_exists($k, $input['type']) ? $input['type'][$k] : null;
            $menus[$k]['key'] = array_key_exists($k, $input['event']) ? $input['event'][$k] : null;

            if(array_key_exists($k, $input['sub_name'])){
                foreach($input['sub_name'][$k] as $k1=>$v1){
                    if($v1 == '' || !array_key_exists($k1, $input['sub_type'][$k]) || $input['sub_event'][$k][$k1] == '') continue;
                    $pattern = '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%
=~_|]/i';
                    if ($input['sub_type'][$k][$k1] == 'view' && !preg_match($pattern, $input['sub_event'][$k][$k1])) continue;

                    $menus[$k]['buttons'][$k1]['name'] = $v1;
                    $menus[$k]['buttons'][$k1]['type'] = $input['sub_type'][$k][$k1];
                    $menus[$k]['buttons'][$k1]['key'] = $input['sub_event'][$k][$k1];
                }

                if(!empty($menus[$k]['buttons'])){
                    $menus[$k]['type'] = null;
                    $menus[$k]['key'] = null;
                }else{
                    unset($menus[$k]['buttons']);
                }
            }
        }

        $target = [];

        // 构建菜单
        foreach ($menus as $menu) {
            // 创建一个菜单项
            $item = new MenuItem($menu['name'], $menu['type'], $menu['key']);

            // 子菜单
            if (!empty($menu['buttons'])) {
                $buttons = [];
                foreach ($menu['buttons'] as $button) {
                    $buttons[] = new MenuItem($button['name'], $button['type'], $button['key']);
                }

                $item->buttons($buttons);
            }

            $target[] = $item;
        }

        $menuService = new Menu($this->appid, $this->appsecret);

        $menuService->set($target); // 失败会抛出异常

        return redirect('weixin/menu');
    }

    /**
     * 公众号菜单事件表单
     */
    public function menuEventView()
    {
        $menuService = new Menu($this->appid, $this->appsecret);

        $_menuData = $menuService->get();

        $menuData = $_menuData['menu']['button'];

        $eventData = array();

        foreach($menuData as $k=>$v){
            foreach($v['sub_button'] as $k1=>$v1){
                if($v1['type'] == 'click'){
                    $eventData[$k.$k1]['menu'] = $v1['name'];
                    $eventData[$k.$k1]['event'] = $v1['key'];
                    $eventData[$k.$k1]['event_type'] = 1;
                    $eventData[$k.$k1]['event_value'] = '默认事件';
                }
            }

            if(array_key_exists('type', $v) && $v['type'] && $v['type'] == 'click'){
                $eventData[$k]['menu'] = $v['name'];
                $eventData[$k]['event'] = array_key_exists('key', $v) ? $v['key'] : null;
                $eventData[$k]['event_type'] = 1;
                $eventData[$k]['event_value'] = '默认事件';
            }
        }

        $menuEvent = DB::table('menu_events') -> get();

        foreach($eventData as $k=>$v){
            foreach($menuEvent as $v1){
                if($v['event'] == $v1->event){
                    $eventData[$k]['event_type'] = $v1->event_type;
                    $eventData[$k]['event_value'] = $v1->event_value;
                }
            }
        }

        $eventData = array_values($eventData);

        return view('we.menu_event', compact('eventData'));
    }

    /**
     * 公众号菜单事件修改，存入本地数据库
     */
    public function menuEvent()
    {
        $input = Input::except('_token');

        if(!array_key_exists('menu', $input)){
            return back();
        }

        $data = array();

        foreach($input['menu'] as $k=>$v){
            $data[$k]['menu'] = $v;
            $data[$k]['event'] = $input['event'][$k];
            $data[$k]['event_value'] = $input['event_value'][$k];
            $data[$k]['event_type'] = $input['event_type'][$k];
        }

        DB::table('menu_events')->truncate();

        foreach($data as $v){
            DB::table('menu_events') -> insert($v);
        }

        return redirect('weixin/menu_event');
    }

    /**
     * 修复没有菜单时点击菜单管理就发生错误，当菜单设置完毕后这个方法不得再执行
     */
    public function menuReset()
    {
        $menuService = new Menu($this->appid, $this->appsecret);

        $menus = [
            [
                "name"=>"DEMO",
                "type"=>"view",
                "key" =>"http://www.baidu.com"
            ],
            [
                "name"=>"MORE",
                "type"=>null,
                "key"=>null,
                "buttons"=>[
                    [
                        "name"=>"TEST_1",
                        "type"=>"view",
                        "key"=>"http://www.baidu.com"
                    ],
                    [
                        "name"=>"TEST_1",
                        "type"=>"view",
                        "key" =>"http://www.baidu.com"
                    ]
                ]
            ]
        ];

        $target = [];

        // 构建你的菜单
        foreach ($menus as $menu) {
            // 创建一个菜单项
            $item = new MenuItem($menu['name'], $menu['type'], $menu['key']);

            // 子菜单
            if (!empty($menu['buttons'])) {
                $buttons = [];
                foreach ($menu['buttons'] as $button) {
                    $buttons[] = new MenuItem($button['name'], $button['type'], $button['key']);
                }

                $item->buttons($buttons);
            }

            $target[] = $item;
        }

        $result = $menuService->set($target); // 失败会抛出异常

        dd($result);
    }



    /**
     * 设置行业表单
     */
    public function setIndustryView()
    {
        $notice = new Notice($this->appid, $this->appsecret);

        $industry = $notice->industries();

        return view('we.set_industry', compact('industry'));
    }

    /**
     * 设置行业
     */
    public function setIndustry()
    {
        $input = Input::only('industry_1', 'industry_2');

        if(!$input['industry_1'] || !$input['industry_2']){
            return back();
        }

        $notice = new Notice($this->appid, $this->appsecret);

        $notice->setIndustry($input['industry_1'], $input['industry_2']);

        return redirect('weixin/set_industry');
    }



    /**
     * 模板消息列表
     */
    public function template()
    {
        $notice = new Notice($this->appid, $this->appsecret);

        $_template = $notice->getAllPrivateTemplate();

        $template = $_template['template_list'];

        return view('we.template', compact('template'));
    }

    /**
     * 模板消息添加表单
     */
    public function templateAddView()
    {
        return view('we.template_add');
    }

    /**
     * 模板消息添加
     */
    public function templateAdd()
    {
        $input = Input::only('prefix', 'short_id');

        $notice = new Notice($this->appid, $this->appsecret);

        $id = $input['prefix'].$input['short_id'];

        $result = $notice->addTemplate($id);

        if($result){
            return redirect('weixin/template');
        }else{
            return back();
        }
    }

    /**
     * 模板消息删除
     */
    public function templateDel($id)
    {
        $notice = new Notice($this->appid, $this->appsecret);

        $result = $notice->delPrivateTemplate($id);

        if($result !== false){
            $data = [
                'status' => 0,
                'msg' => '删除成功',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '删除失败',
            ];
        }

        return $data;
    }

    /**
     * 发送模板消息
     */
    public function sendTemplate()
    {
        $notice = new Notice($this->appid, $this->appsecret);

        $userId = 'o7uGJ1mfRvmllGwcExnxb0gpaqgM';
        $templateId = '-gVq5xkFC7xA3LJijP5DhxdJpPSoJBEjNLnuEiHVNYs';//通过addTemplate方法获取
        $url = 'http://www.baidu.com';

        $data = array(
            "name"    => array("呼啦啦网购", '#ff3300'),
            "remark"   => array("谢谢使用嗨科产品！", "#5599FF"),
        );

        $result = $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();

        dd($result);
    }

    /**
     * 模板消息群发表单
     */
    public function msgView()
    {
        return view('we.msg');
    }

    /**
     * 模板消息群发
     */
    public function msg()
    {
        set_time_limit(0);

        $input = Input::only('first', 'keyword1', 'keyword2', 'keyword3', 'remark', 'url');
        $notice = new Notice($this->appid, $this->appsecret);

        $templateId = '9_dJhphJ3O4sGowMH1hL6jpXeovWJGsyoGrot3JF740';//通过addTemplate方法获取
        $url = $input['url'];

        $data = array(
            "first"    => array($input['first'], '#5599FF'),
            "keyword1"    => array($input['keyword1'], '#5599FF'),
            "keyword2"    => array($input['keyword2'], '#5599FF'),
            "keyword3"    => array($input['keyword3'], '#5599FF'),
            "remark"   => array($input['remark'], "#5599FF"),
        );

        $members = DB::table('members') -> get();

        foreach($members as $v){
            $userId = $v->openid;

            if(!$userId || !$v->is_scr)continue;
            
            $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($userId)->send();
        }

        return redirect(url('weixin'));
    }


    /**
     * 群发消息表单
     */
    public function broadcastView()
    {
        $perpage = 100;

        $userService = new User($this->appid, $this->appsecret);

        //获取用户openid列表
        $userList = $userService->lists();

        $openidArrAll = $userList['data']['openid'];

        $dataCount = count($openidArrAll);

        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();

        $openidArr = array_slice($openidArrAll, $page->getOffset(), $perpage);

        $userData = array();
        if(!empty($openidArr)){
            //批量获取用户信息
            $userData = $userService->batchGet($openidArr);
        }

        //获取用户组数据
        $group = new Group($this->appid, $this->appsecret);

        $groupListAll = $group->lists();

        return view('we.broadcast', compact('userData', 'groupListAll', 'pageShow'));
    }

    /**
     * 群发消息发送
     */
    public function broadcast()
    {
        $data = array();

        $input = Input::only('orientation', 'event_type', 'event_value', 'group_id', 'openid');

        $broadcast = new Broadcast($this->appid, $this->appsecret);

        $msg = $this->replyEvent($input['event_type'], $input['event_value']);

        switch($input['orientation']){
            case '1':
                $result = $broadcast->send($msg)->to();
                break;
            case '2':
                $result = $broadcast->send($msg)->to($input['group_id']);
                break;
            case '3':
                $openidArr = explode('@', $input['openid']);
                $result = $broadcast->send($msg)->to($openidArr);
                break;
        }

        if($result){
            $data['status'] = 0;
            $data['msg'] = '发送成功';
        }else{
            $data['status'] = 1;
            $data['msg'] = '发送失败';
        }

        return $data;
    }



    /**
     * 用户列表，先获取appid列表，再根据appid列表获取用户的详细信息
     */
    public function user()
    {
        $perpage = 20;

        $userService = new User($this->appid, $this->appsecret);

        //获取用户openid列表
        $userList = $userService->lists();

        $openidArrAll = $userList['data']['openid'];

        $dataCount = count($openidArrAll);

        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();

        $openidArr = array_slice($openidArrAll, $page->getOffset(), $perpage);

        $userData = array();
        if(!empty($openidArr)){
            //批量获取用户信息
            $userData = $userService->batchGet($openidArr);
        }

        //获取用户组数据
        $group = new Group($this->appid, $this->appsecret);

        $groupListAll = $group->lists();

        return view('we.user', compact('userData', 'pageShow', 'groupListAll'));
    }

    /**
     * 批量移动用户到指定分组
     */
    public function moveUsers()
    {
        $data = array();

        $input = Input::only('group_id', 'openid_arr');

        if($input['group_id'] === null || $input['group_id'] === ''){
            $data['status'] = 1;
            $data['msg'] = '用户组数据传递错误，请稍候再试';
            return $data;
        }

        if(!$input['openid_arr']){
            $data['status'] = 2;
            $data['msg'] = '用户数据传递错误，请稍候再试';
            return $data;
        }

        $group = new Group($this->appid, $this->appsecret);

        $result = $group->moveUsers($input['openid_arr'], $input['group_id']);

        if($result){
            $data['status'] = 0;
            $data['msg'] = '用户移动成功';
        }else{
            $data['status'] = 3;
            $data['msg'] = '用户移动失败，请稍后再试';
        }

        return $data;
    }

    /**
     * 用户编辑，目前只能修改备注
     */
    public function userEditView($openid)
    {
        $userService = new User($this->appid, $this->appsecret);

        $userData = $userService->get($openid);

        $tag = new Tag($this->appid, $this->appsecret);

        $tagList = $tag->lists();

        $userTag = $tag->userTags($openid);

        return view('we.user_edit', compact('userData', 'tagList', 'userTag'));
    }

    /**
     * 用户编辑提交表单，修改备注
     */
    public function userEdit()
    {
        $input = Input::except('_token');

        $userService = new User($this->appid, $this->appsecret);

        $result = $userService->remark($input['openid'], $input['remark']);

        if($result){
            $tag = new Tag($this->appid, $this->appsecret);

            $tagArr = json_decode($input['tag_id']);
            //先把用户的所有标签删除
            foreach($tagArr as $v){
                $tag->batchUntagUsers([$input['openid']], $v->id);
            }
            //在给用户添加标签
            foreach($input['has_tag_id'] as $v){
                $tag->batchTagUsers([$input['openid']], $v);
            }

            return redirect('weixin/user');
        }else{
            return back();
        }
    }

    /**
     * 修改数据库里把之前关注公众号的会员的关注状态
     */
    public function userSubscribe()
    {
        set_time_limit(0);
        $userService = new User($this->appid, $this->appsecret);

        //获取用户openid列表
        $userList = $userService->lists();

        $openids = $userList['data']['openid'];

        foreach($openids as $v){
            DB::table('members') -> where('openid', $v) -> update(['is_scr'=>'1']);
        }

        return redirect(url('weixin'));
    }



    /**
     * 获取所有分组
     */
    public function group()
    {
        $perpage = 20;

        $group = new Group($this->appid, $this->appsecret);

        $groupListAll = $group->lists();

        $dataCount = count($groupListAll);

        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();

        $groupList = array_slice($groupListAll, $page->getOffset(), $perpage);

        return view('we.group', compact('groupList', 'pageShow'));
    }

    /**
     * 添加用户组表单
     */
    public function groupAddView()
    {
        return view('we.group_add');
    }

    /**
     * 添加用户组
     */
    public function groupAdd()
    {
        $input = Input::only('name');

        $name = $input['name'];

        $group = new Group($this->appid, $this->appsecret);

        $result = $group->create($name);

        if($result){
            return redirect('weixin/group');
        }else{
            return back();
        }
    }

    /**
     * 修改用户组表单
     */
    public function groupEditView($id)
    {
        return view('we.group_edit', compact('id'));
    }

    /**
     * 修改用户组
     */
    public function groupEdit()
    {
        $input = Input::only('group_id', 'name');

        $group = new Group($this->appid, $this->appsecret);

        $result = $group->update($input['group_id'], $input['name']);

        if($result){
            return redirect('weixin/group');
        }else{
            return back();
        }
    }

    /**
     * 删除用户组
     */
    public function groupDelete()
    {
        $data = array();

        $input = Input::only('group_id');

        $group = new Group($this->appid, $this->appsecret);

        $result = $group->delete($input['group_id']);

        if($result){
            $data['status'] = 0;
            $data['msg'] = '删除成功';
        }else{
            $data['status'] = 1;
            $data['msg'] = '删除失败，请稍后再试';
        }

        return $data;
    }



    /**
     * 获取所有标签
     */
    public function tag()
    {
        $perpage = 20;

        $tag = new Tag($this->appid, $this->appsecret);

        $tagListAll = $tag->lists();

        $dataCount = count($tagListAll);

        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();

        $tagList = array_slice($tagListAll, $page->getOffset(), $perpage);

        return view('we.tag', compact('tagList', 'pageShow'));
    }

    /**
     * 添加标签表单
     */
    public function tagAddView()
    {
        return view('we.tag_add');
    }

    /**
     * 添加标签
     */
    public function tagAdd()
    {
        $input = Input::only('name');

        $name = $input['name'];

        $tag = new Tag($this->appid, $this->appsecret);

        $result = $tag->create($name);

        if($result){
            return redirect('weixin/tag');
        }else{
            return back();
        }
    }

    /**
     * 修改标签表单
     */
    public function tagEditView($id)
    {
        return view('we.tag_edit', compact('id'));
    }

    /**
     * 修改标签
     */
    public function tagEdit()
    {
        $input = Input::only('tag_id', 'name');

        $tag = new Tag($this->appid, $this->appsecret);

        $result = $tag->update($input['tag_id'], $input['name']);

        if($result){
            return redirect('weixin/tag');
        }else{
            return back();
        }
    }

    /**
     * 删除标签
     */
    public function tagDelete()
    {
        $data = array();

        $input = Input::only('tag_id');

        $tag = new Tag($this->appid, $this->appsecret);

        $result = $tag->delete($input['tag_id']);

        if($result){
            $data['status'] = 0;
            $data['msg'] = '删除成功';
        }else{
            $data['status'] = 1;
            $data['msg'] = '删除失败，请稍后再试';
        }

        return $data;
    }



    /*
     * 素材管理，素材类型为 'image', 'voice', 'video', 'news'四种，分别处理
     */
    /**
     * 图像素材列表
     */
    public function image()
    {
        $type = 'image';

        $perpage = 20;

        $media = new Media($this->appid, $this->appsecret);

        $_imageData = $media->lists($type);

        $page = new Page($_imageData['item_count'], $perpage);
        $pageShow = $page -> fpage();

        $imageData = array_slice($_imageData['item'], $page->getOffset(), $perpage);

        return view('we.image', compact('pageShow', 'imageData'));
    }

    /**
     * 上传图像素材表单
     */
    public function imageAddView()
    {
        return view('we.image_add');
    }

    /**
     * 上传图像素材
     */
    public function imageAdd()
    {
        $file = Input::file('image');

        if($file && $file -> isValid()){
            $filename = $file->getClientOriginalName();
            $path = public_path().'/we/tmp/';
            $file->move($path, $filename);

            $media = new Media($this->appid, $this->appsecret);

            $result = $media->forever()->image($path.$filename);

            if($result){
                @unlink($path.$filename);
                return redirect('weixin/image');
            }else{
                return back();
            }
        }else{
            return back();
        }
    }

    /**
     * 删除图像素材
     */
    public function imageDelete()
    {
        $data = array();

        $input = Input::only('media_id');

        $media = new Media($this->appid, $this->appsecret);

        $result = $media->delete($input['media_id']);

        if($result){
            $data['status'] = 0;
            $data['msg'] = '删除成功';
        }else{
            $data['status'] = 1;
            $data['msg'] = '删除失败，请稍后再试';
        }

        return $data;
    }



    /**
     * 声音素材列表
     */
    public function voice()
    {
        $type = 'voice';

        $perpage = 20;

        $media = new Media($this->appid, $this->appsecret);

        $_voiceData = $media->lists($type);

        $page = new Page($_voiceData['item_count'], $perpage);
        $pageShow = $page -> fpage();

        $voiceData = array_slice($_voiceData['item'], $page->getOffset(), $perpage);

        return view('we.voice', compact('pageShow', 'voiceData'));
    }

    /**
     * 上传声音素材表单
     */
    public function voiceAddView()
    {
        return view('we.voice_add');
    }

    /**
     * 上传声音素材
     */
    public function voiceAdd()
    {
        $file = Input::file('voice');

        if($file && $file -> isValid()){
            $filename = $file->getClientOriginalName();
            $path = public_path().'/we/tmp/';
            $file->move($path, $filename);

            $media = new Media($this->appid, $this->appsecret);

            $result = $media->forever()->voice($path.$filename);

            if($result){
                @unlink($path.$filename);
                return redirect('weixin/voice');
            }else{
                return back();
            }
        }else{
            return back();
        }
    }

    /**
     * 删除声音素材
     */
    public function voiceDelete()
    {
        $data = array();

        $input = Input::only('media_id');

        $media = new Media($this->appid, $this->appsecret);

        $result = $media->delete($input['media_id']);

        if($result){
            $data['status'] = 0;
            $data['msg'] = '删除成功';
        }else{
            $data['status'] = 1;
            $data['msg'] = '删除失败，请稍后再试';
        }

        return $data;
    }



    /**
     * 视频素材列表
     */
    public function video()
    {
        $type = 'video';

        $perpage = 20;

        $media = new Media($this->appid, $this->appsecret);

        $_videoData = $media->lists($type);

        $page = new Page($_videoData['item_count'], $perpage);
        $pageShow = $page -> fpage();

        $videoData = array_slice($_videoData['item'], $page->getOffset(), $perpage);

        return view('we.video', compact('pageShow', 'videoData'));
    }

    /**
     * 上传视频素材表单
     */
    public function videoAddView()
    {
        return view('we.video_add');
    }

    /**
     * 上传视频素材
     */
    public function videoAdd()
    {
        $file = Input::file('video');
        $input = Input::only('title', 'description');

        if($file && $file -> isValid()){
            $filename = $file->getClientOriginalName();
            $path = public_path().'/we/tmp/';
            $file->move($path, $filename);

            $media = new Media($this->appid, $this->appsecret);

            $result = $media->forever()->video($path.$filename, $input['title'], $input['description']);

            if($result){
                @unlink($path.$filename);
                return redirect('weixin/video');
            }else{
                return back();
            }
        }else{
            return back();
        }
    }

    /**
     * 删除视频素材
     */
    public function videoDelete()
    {
        $data = array();

        $input = Input::only('media_id');

        $media = new Media($this->appid, $this->appsecret);

        $result = $media->delete($input['media_id']);

        if($result){
            $data['status'] = 0;
            $data['msg'] = '删除成功';
        }else{
            $data['status'] = 1;
            $data['msg'] = '删除失败，请稍后再试';
        }

        return $data;
    }



    /**
     * 图文素材列表
     */
    public function news()
    {
        $type = 'news';

        $perpage = 20;

        $media = new Media($this->appid, $this->appsecret);

        $_newsData = $media->lists($type);

        $page = new Page($_newsData['item_count'], $perpage);
        $pageShow = $page -> fpage();

        $newsData = array_slice($_newsData['item'], $page->getOffset(), $perpage);

        return view('we.news', compact('pageShow', 'newsData'));
    }

    /**
     * 上传图文素材表单
     */
    public function newsAddView()
    {
        return view('we.news_add');
    }

    /**
     * 上传图文素材
     */
    public function newsAdd()
    {
        set_time_limit(0);

        $input = Input::except('_token');

        $article = array();

        $media = new Media($this->appid, $this->appsecret);

        $pattern = "/(href|src)=([\"|']?)([^\"'>]+.(jpg|JPG|jpeg|JPEG|gif|GIF|png|PNG))/i";

        foreach($input['title'] as $k=>$v){
            if($v == '')continue;

            $article[$k]['title'] = $v;
            $article[$k]['thumb_media_id'] = $input['thumb_media_id'][$k];
            $article[$k]['author'] = $input['author'][$k];
            $article[$k]['digest'] = $input['digest'][$k];
            $article[$k]['show_cover_pic'] = $input['show_cover_pic'][$k];
            $article[$k]['content_source_url'] = $input['content_source_url'][$k];

            $content = $input['content'][$k];

            preg_match_all($pattern, $content, $images);

            foreach($images[3] as $k1=>$v1){
                if(!strstr($v1, 'ueditor'))continue;

                $result = $media->newsImage(public_path().$v1);
                $content = str_replace($v1, $result['url'], $content);
                @unlink(public_path().$v1);
            }

            $article[$k]['content'] = $content;
        }

        if(empty($article)){
            return back();
        }

        $article = array_values($article);

        $result = $media->news($article);

        if($result){
            return redirect('weixin/news');
        }else{
            return back();
        }
    }

    /**
     * session 里存储修改的图文信息
     */
    public function storeNews()
    {
        $input = Input::only('media_index', 'media_id', 'data');
        session(['news_data'=>$input]);
        return array('status'=>0, 'msg'=>'操作成功');
    }

    /**
     * 修改图文素材表单
     */
    public function newsEditView()
    {
        $_newsData = session('news_data');
        if(!$_newsData){
            return redirect('weixin/news');
        }

        $mediaIndex = $_newsData['media_index'];
        $mediaId = $_newsData['media_id'];
        $newsData = json_decode($_newsData['data']);

        return view('we.news_edit', compact('mediaIndex', 'mediaId', 'newsData'));
    }

    /**
     * 修改图文素材
     */
    public function newsEdit()
    {
        $article = Input::only('title', 'thumb_media_id', 'author', 'digest', 'show_cover_pic', 'content', 'content_source_url');
        $mediaData = Input::only('media_id', 'media_index');

        $pattern = "/(href|src)=([\"|']?)([^\"'>]+.(jpg|JPG|jpeg|JPEG|gif|GIF|png|PNG))/i";

        $media = new Media($this->appid, $this->appsecret);

        $content = $article['content'];

        preg_match_all($pattern, $content, $images);

        foreach($images[3] as $k=>$v){
            if(!strstr($v, 'ueditor'))continue;

            $result = $media->newsImage(public_path().$v);
            $content = str_replace($v, $result['url'], $content);
            @unlink(public_path().$v);
        }

        $article['content'] = $content;

        $result = $media->updateNews($mediaData['media_id'], $article, $mediaData['media_index']);

        if($result){
            return redirect('weixin/news');
        }else{
            return back();
        }
    }

    /**
     * 删除图文素材
     */
    public function newsDelete()
    {
        $data = array();

        $input = Input::only('media_id');

        $media = new Media($this->appid, $this->appsecret);

        $result = $media->delete($input['media_id']);

        if($result){
            $data['status'] = 0;
            $data['msg'] = '删除成功';
        }else{
            $data['status'] = 1;
            $data['msg'] = '删除失败，请稍后再试';
        }

        return $data;
    }



    /**
     * 客服管理
     */
    public function staff()
    {
        $input = Input::only('status');

        $staff = new Staff($this->appid, $this->appsecret);

        $staffData = null;
        $onlineStaffData = null;

        if($input['status'] == 'online'){
            $onlineStaffData = $staff->onlines();
        }else{
            $staffData = $staff->lists();
        }

        return view('we.staff', compact('staffData', 'onlineStaffData'));
    }

    /**
     * 添加客服表单
     */
    public function staffAddView()
    {
        return view('we.staff_add');
    }

    /**
     * 添加客服
     */
    public function staffAdd()
    {
        $input = Input::only('email', 'nickname', 'password', 'invite_wx', 'headimg');

        if(!$input['email']){
            return back();
        }
        if(!$input['nickname']){
            return back();
        }
        if(!$input['password']){
            return back();
        }

        $email = $input['email'].'@'.$this->origin_id;

        $staff = new Staff($this->appid, $this->appsecret);

        $res = $staff->create($email, $input['nickname'], $input['password']);

        if(!$res){
            return back();
        }

        $file = Input::file('headimg');

        if($file && $file -> isValid()){
            $filename = $file->getClientOriginalName();
            $path = public_path().'/we/tmp/';
            $file->move($path, $filename);

            //之前调用了该类的函数，所以变更了一些属性，因此需要重新实例化。（反正不能删）
            $staff = new Staff($this->appid, $this->appsecret);

            $result = $staff->avatar($email, $path.$filename);

            if($result){
                @unlink($path.$filename);
            }
        }

        if($input['invite_wx']){
            $staff->bind($email, $input['invite_wx']);
        }

        return redirect('weixin/staff');
    }

    /**
     * 修改客服表单
     */
    public function staffEditView()
    {
        return view('we.staff_edit');
    }

    /**
     * 修改客服
     */
    public function staffEdit()
    {
        $input = Input::only('kf_wx', 'email', 'nickname', 'password', 'invite_wx', 'headimg');

        if(!$input['email']){
            return back();
        }
        if(!$input['nickname']){
            return back();
        }
        if(!$input['password']){
            return back();
        }

        $email = $input['email'].'@'.$this->origin_id;

        $staff = new Staff($this->appid, $this->appsecret);

        $res = $staff->update($email, $input['nickname'], $input['password']);

        if(!$res){
            return back();
        }

        $file = Input::file('headimg');

        if($file && $file -> isValid()){
            $filename = $file->getClientOriginalName();
            $path = public_path().'/we/tmp/';
            $file->move($path, $filename);

            //之前调用了该类的函数，所以变更了一些属性，因此需要重新实例化。（反正不能删）
            $staff = new Staff($this->appid, $this->appsecret);

            $result = $staff->avatar($email, $path.$filename);

            if($result){
                @unlink($path.$filename);
            }
        }

        if(!$input['kf_wx'] && $input['invite_wx']){
            $staff->bind($email, $input['invite_wx']);
        }

        return redirect('weixin/staff');
    }

    /**
     * 删除客服
     */
    public function staffDelete()
    {
        $data = array();

        $input = Input::only('email', 'nickname', 'password');

        $staff = new Staff($this->appid, $this->appsecret);

        $result = $staff->delete($input['email'], $input['nickname'], $input['password']);

        if($result){
            $data['status'] = 0;
            $data['msg'] = '删除成功';
        }else{
            $data['status'] = 1;
            $data['msg'] = '删除失败，请稍后再试';
        }

        return $data;
    }



    /**
     * 客服发送消息表单
     */
    public function staffSendView()
    {
        return view('we.staff_send');
    }

    /**
     * 客服发送消息
     */
    public function staffSend()
    {
        $input = Input::only('event_type', 'event_value', 'openid');

        if(!$input['event_type']){
            return back();
        }
        if(!$input['event_value']){
            return back();
        }
        if(!$input['openid']){
            return back();
        }

        $msg = $this->replyEvent($input['event_type'], $input['event_value']);

        $staff = new Staff($this->appid, $this->appsecret);

        $staff->send($msg)->to($input['openid']);

        return redirect('weixin/staff_send');
    }
}
