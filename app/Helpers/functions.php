<?php
/*移动端判断*/
function isMobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (array_key_exists('HTTP_USER_AGENT', $_SERVER) && isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        }
    }
    return false;
}

function isWeixin() {
    if (array_key_exists('HTTP_USER_AGENT', $_SERVER) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    }
    return false;
}

function getNextMonth($now){
	$year = date('Y', $now);
	$month = date('m', $now);
	$day = date('d', $now);
	$hour = date('H', $now);
	$minute = date('i', $now);
	$second = date('s', $now);
	
	$month += 1;
	if($month == 13){
		$month = 1;
		$year += 1;
	}
	
	return mktime($hour, $minute, $second, $month, $day, $year);
}

function curl_get($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);

    return json_decode($data);
}

function curl_post($url, $post_data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = str_replace("\"",'"',$result );
    $data = json_decode($data,true);

    return $data;
}

function kuaidiTransfrom($code)
{
    $data = [
        "jtexpress"=>	"急兔速递",
        "youzhengguonei"=>	"邮政包裹/平邮",
        "youzhengguoji"=>	"国际包裹",
        "ems"=>	"EMS",
        "emsguoji"=>	"EMS-国际件",
        "emsinten"=>	"EMS-国际件-英文结果",
        "bjemstckj"=>	"北京EMS",
        "shunfeng"=>	"顺丰",
        "shentong"=>	"申通",
        "yuantong"=>	"圆通",
        "zhongtong"=>	"中通",
        "huitongkuaidi"=>	"汇通",
        "yunda"=>	"韵达",
        "zhaijisong"=>	"宅急送",
        "tiantian"=>	"天天",
        "debangwuliu"=>	"德邦",
        "guotongkuaidi"=>	"国通",
        "zengyisudi"=>	"增益",
        "suer"=>	"速尔",
        "ztky"=>	"中铁物流",
        "zhongtiewuliu"=>	"中铁快运",
        "ganzhongnengda"=>	"能达",
        "youshuwuliu"=>	"优速",
        "quanfengkuaidi"=>	"全峰",
        "jd"=>	"京东",
        "fedex"=>	"FedEx-国际",
        "fedexus"=>	"FedEx-美国",
        "dhlen"=>	"DHL全球件",
        "dhl"=>	"DHL",
        "dhlde"=>	"DHL-德国",
        "tnten"=>	"TNT全球件",
        "tnt"=>	"TNT",
        "upsen"=>	"UPS全球件",
        "ups"=>	"UPS",
        "usps"=>	"USPS",
        "dpd"=>	"DPD",
        "dpdgermany"=>	"DPD Germany",
        "dpdpoland"=>	"DPD Poland",
        "dpduk"=>	"DPD UK",
        "gls"=>	"GLS",
        "dpexen"=>	"Toll",
        "tollpriority"=>	"Toll Priority(Toll Online)",
        "aramex"=>	"Aramex",
        "dpex"=>	"DPEX",
        "zhaijibian"=>	"宅急便",
        "yamato"=>	"黑猫雅玛多",
        "hkpost"=>	"香港邮政(HongKong Post)",
        "parcelforce"=>	"英国大包、EMS（Parcel Force）",
        "royalmail"=>	"英国小包（Royal Mail）",
        "auspost"=>	"澳大利亚邮政-英文",
        "canpost"=>	"加拿大邮政-英文版",
        "yitongfeihong"=>	"一统飞鸿",
        "rufengda"=>	"如风达",
        "haihongwangsong"=>	"海红网送",
        "tonghetianxia"=>	"通和天下",
        "zhengzhoujianhua"=>	"郑州建华",
        "sxhongmajia"=>	"红马甲",
        "zhimakaimen"=>	"芝麻开门",
        "lejiedi"=>	"乐捷递",
        "lijisong"=>	"立即送",
        "yinjiesudi"=>	"银捷",
        "menduimen"=>	"门对门",
        "hebeijianhua"=>	"河北建华",
        "weitepai"=>	"微特派",
        "fengxingtianxia"=>	"风行天下",
        "shangcheng"=>	"尚橙",
        "neweggozzo"=>	"新蛋奥硕",
        "xinhongyukuaidi"=>	"鑫飞鸿",
        "quanyikuaidi"=>	"全一",
        "biaojikuaidi"=>	"彪记",
        "xingchengjibian"=>	"星晨急便",
        "yafengsudi"=>	"亚风",
        "yuanweifeng"=>	"源伟丰",
        "quanritongkuaidi"=>	"全日通",
        "anxindakuaixi"=>	"安信达",
        "minghangkuaidi"=>	"民航",
        "fenghuangkuaidi"=>	"凤凰",
        "jinguangsudikuaijian"=>	"京广",
        "peisihuoyunkuaidi"=>	"配思货运",
        "aae"=>	"AAE-中国件",
        "datianwuliu"=>	"大田",
        "xinbangwuliu"=>	"新邦",
        "longbanwuliu"=>	"龙邦",
        "yibangwuliu"=>	"一邦",
        "lianhaowuliu"=>	"联昊通",
        "guangdongyouzhengwuliu"=>	"广东邮政",
        "zhongyouwuliu"=>	"中邮",
        "tiandihuayu"=>	"天地华宇",
        "shenghuiwuliu"=>	"盛辉",
        "changyuwuliu"=>	"长宇",
        "feikangda"=>	"飞康达",
        "yuanzhijiecheng"=>	"元智捷诚",
        "wanjiawuliu"=>	"万家",
        "yuanchengwuliu"=>	"远成",
        "xinfengwuliu"=>	"信丰",
        "wenjiesudi"=>	"文捷航空",
        "quanchenkuaidi"=>	"全晨",
        "jiayiwuliu"=>	"佳怡",
        "kuaijiesudi"=>	"快捷",
        "dsukuaidi"=>	"D速",
        "quanjitong"=>	"全际通",
        "anjiekuaidi"=>	"青岛安捷",
        "yuefengwuliu"=>	"越丰",
        "jixianda"=>	"急先达",
        "baifudongfang"=>	"百福东方",
        "bht"=>	"BHT",
        "wuyuansudi"=>	"伍圆",
        "lanbiaokuaidi"=>	"蓝镖",
        "coe"=>	"COE",
        "nanjing"=>	"南京100",
        "hengluwuliu"=>	"恒路",
        "jindawuliu"=>	"金大",
        "huaxialongwuliu"=>	"华夏龙",
        "yuntongkuaidi"=>	"运通中港",
        "jiajiwuliu"=>	"佳吉",
        "shengfengwuliu"=>	"盛丰",
        "yuananda"=>	"源安达",
        "jiayunmeiwuliu"=>	"加运美",
        "wanxiangwuliu"=>	"万象",
        "hongpinwuliu"=>	"宏品",
        "shangda"=>	"上大",
        "zhongtiewuliu"=>	"中铁",
        "yuanfeihangwuliu"=>	"原飞航",
        "haiwaihuanqiu"=>	"海外环球",
        "santaisudi"=>	"三态",
        "jinyuekuaidi"=>	"晋越",
        "lianbangkuaidi"=>	"联邦",
        "feikuaida"=>	"飞快达",
        "zhongxinda"=>	"忠信达",
        "gongsuda"=>	"共速达",
        "jialidatong"=>	"嘉里大通",
        "ocs"=>	"OCS",
        "meiguokuaidi"=>	"美国",
        "lijisong"=>	"成都立即送",
        "disifang"=>	"递四方",
        "kangliwuliu"=>	"康力",
        "kuayue"=>	"跨越",
        "haimengsudi"=>	"海盟",
        "shenganwuliu"=>	"圣安",
        "zhongsukuaidi"=>	"中速",
        "ontrac"=>	"OnTrac",
        "sevendays"=>	"七天连锁",
        "mingliangwuliu"=>	"明亮",
        "vancl"=>	"凡客配送（作废）",
        "huaqikuaiyun"=>	"华企",
        "city100"=>	"城市100",
        "suijiawuliu"=>	"穗佳",
        "feibaokuaidi"=>	"飞豹",
        "chuanxiwuliu"=>	"传喜",
        "jietekuaidi"=>	"捷特",
        "longlangkuaidi"=>	"隆浪",
        "emsen"=>	"EMS-英文",
        "zhongtianwanyun"=>	"中天万运",
        "bangsongwuliu"=>	"邦送",
        "auspost"=>	"澳大利亚(Australia Post)",
        "canpost"=>	"加拿大(Canada Post)",
        "canpostfr"=>	"加拿大邮政",
        "shunfengen"=>	"顺丰-美国件",
        "huiqiangkuaidi"=>	"汇强",
        "xiyoutekuaidi"=>	"希优特",
        "haoshengwuliu"=>	"昊盛",
        "yilingsuyun"=>	"亿领",
        "dayangwuliu"=>	"大洋",
        "didasuyun"=>	"递达",
        "yitongda"=>	"易通达",
        "youbijia"=>	"邮必佳",
        "yishunhang"=>	"亿顺航",
        "feihukuaidi"=>	"飞狐",
        "xiaoxiangchenbao"=>	"潇湘晨报",
        "balunzhi"=>	"巴伦支",
        "minshengkuaidi"=>	"闽盛",
        "syjiahuier"=>	"佳惠尔",
        "minbangsudi"=>	"民邦",
        "shanghaikuaitong"=>	"上海快通",
        "xiaohongmao"=>	"北青小红帽",
        "gsm"=>	"GSM",
        "annengwuliu"=>	"安能",
        "kcs"=>	"KCS",
        "citylink"=>	"City-Link",
        "diantongkuaidi"=>	"店通",
        "fanyukuaidi"=>	"凡宇",
        "pingandatengfei"=>	"平安达腾飞",
        "guangdongtonglu"=>	"广东通路",
        "zhongruisudi"=>	"中睿",
        "kuaidawuliu"=>	"快达",
        "adp"=>	"ADP国际",
        "fardarww"=>	"颿达国际",
        "fandaguoji"=>	"颿达国际-英文",
        "shlindao"=>	"林道国际",
        "sinoex"=>	"中外运-中文",
        "zhongwaiyun"=>	"中外运",
        "dechuangwuliu"=>	"深圳德创",
        "ldxpres"=>	"林道国际-英文",
        "ruidianyouzheng"=>	"瑞典（Sweden Post）",
        "postenab"=>	"PostNord(Posten AB)",
        "nuoyaao"=>	"偌亚奥国际",
        "xianglongyuntong"=>	"祥龙运通",
        "pinsuxinda"=>	"品速心达",
        "yuxinwuliu"=>	"宇鑫",
        "peixingwuliu"=>	"陪行",
        "hutongwuliu"=>	"户通",
        "xianchengliansudi"=>	"西安城联",
        "yujiawuliu"=>	"煜嘉",
        "yiqiguojiwuliu"=>	"一柒国际",
        "fedexcn"=>	"Fedex-国际件-中文",
        "lianbangkuaidien"=>	"联邦-英文",
        "zhongtongphone"=>	"中通（带电话）",
        "saiaodimmb"=>	"赛澳递for买卖宝",
        "shanghaiwujiangmmb"=>	"上海无疆for买卖宝",
        "singpost"=>	"新加坡小包(Singapore Post)",
        "yinsu"=>	"音素",
        "ndwl"=>	"南方传媒",
        "sucheng"=>	"速呈宅配",
        "chuangyi"=>	"创一",
        "dianyi"=>	"云南滇驿",
        "cqxingcheng"=>	"重庆星程",
        "scxingcheng"=>	"四川星程",
        "gzxingcheng"=>	"贵州星程",
        "ytkd"=>	"运通中港(作废)",
        "gatien"=>	"Gati-英文",
        "gaticn"=>	"Gati-中文",
        "jcex"=>	"jcex",
        "peex"=>	"派尔",
        "kxda"=>	"凯信达",
        "advancing"=>	"安达信",
        "huiwen"=>	"汇文",
        "yxexpress"=>	"亿翔",
        "donghong"=>	"东红",
        "feiyuanvipshop"=>	"飞远配送",
        "hlyex"=>	"好运来",
        "kuaiyouda"=>	"四川快优达",
        "riyuwuliu"=>	"日昱",
        "sutongwuliu"=>	"速通",
        "nanjingshengbang"=>	"晟邦",
        "anposten"=>	"爱尔兰(An Post)",
        "japanposten"=>	"日本（Japan Post）",
        "postdanmarken"=>	"丹麦(Post Denmark)",
        "brazilposten"=>	"巴西(Brazil Post/Correios)",
        "postnlcn"=>	"荷兰挂号信(PostNL international registered mail)",
        "postnl"=>	"荷兰挂号信(PostNL international registered mail)",
        "emsukrainecn"=>	"乌克兰EMS-中文(EMS Ukraine)",
        "emsukraine"=>	"乌克兰EMS(EMS Ukraine)",
        "ukrpostcn"=>	"乌克兰邮政包裹",
        "ukrpost"=>	"乌克兰小包、大包(UkrPost)",
        "haihongmmb"=>	"海红for买卖宝",
        "fedexuk"=>	"FedEx-英国件（FedEx UK)",
        "fedexukcn"=>	"FedEx-英国件",
        "dingdong"=>	"叮咚",
        "upsfreight"=>	"UPS Freight",
        "abf"=>	"ABF",
        "purolator"=>	"Purolator",
        "bpost"=>	"比利时（Bpost）",
        "bpostinter"=>	"比利时国际(Bpost international)",
        "lasership"=>	"LaserShip",
        "yodel"=>	"YODEL",
        "dhlnetherlands"=>	"DHL-荷兰（DHL Netherlands）",
        "myhermes"=>	"MyHermes",
        "fastway"=>	"Fastway Ireland",
        "chronopostfra"=>	"法国大包、EMS-法文（Chronopost France）",
        "selektvracht"=>	"Selektvracht",
        "lanhukuaidi"=>	"蓝弧",
        "belgiumpost"=>	"比利时(Belgium Post)",
        "upsmailinno"=>	"UPS Mail Innovations",
        "postennorge"=>	"挪威（Posten Norge）",
        "swisspostcn"=>	"瑞士邮政",
        "swisspost"=>	"瑞士(Swiss Post)",
        "royalmailcn"=>	"英国邮政小包",
        "dhlbenelux"=>	"DHL Benelux",
        "novaposhta"=>	"Nova Poshta",
        "dhlpoland"=>	"DHL-波兰（DHL Poland）",
        "estes"=>	"Estes",
        "tntuk"=>	"TNT UK",
        "deltec"=>	"Deltec Courier",
        "opek"=>	"OPEK",
        "italysad"=>	"Italy SDA",
        "mrw"=>	"MRW",
        "chronopostport"=>	"Chronopost Portugal",
        "correosdees"=>	"西班牙(Correos de Espa?a)",
        "directlink"=>	"Direct Link",
        "eltahell"=>	"ELTA Hellenic Post",
        "ceskaposta"=>	"捷克（?eská po?ta）",
        "siodemka"=>	"Siodemka",
        "seur"=>	"International Seur",
        "jiuyicn"=>	"久易",
        "hrvatska"=>	"克罗地亚（Hrvatska Posta）",
        "bulgarian"=>	"保加利亚（Bulgarian Posts）",
        "portugalseur"=>	"Portugal Seur",
        "ecfirstclass"=>	"EC-Firstclass",
        "dtdcindia"=>	"DTDC India",
        "safexpress"=>	"Safexpress",
        "koreapost"=>	"韩国（Korea Post）",
        "tntau"=>	"TNT Australia",
        "thailand"=>	"泰国（Thailand Thai Post）",
        "skynetmalaysia"=>	"SkyNet Malaysia",
        "malaysiapost"=>	"马来西亚小包（Malaysia Post(Registered)）",
        "malaysiaems"=>	"马来西亚大包、EMS（Malaysia Post(parcel,EMS)）",
        "saudipost"=>	"沙特阿拉伯(Saudi Post)",
        "southafrican"=>	"南非（South African Post Office）",
        "ocaargen"=>	"OCA Argentina",
        "nigerianpost"=>	"尼日利亚(Nigerian Postal)",
        "chile"=>	"智利(Correos Chile)",
        "israelpost"=>	"以色列(Israel Post)",
        "estafeta"=>	"Estafeta",
        "gdkd"=>	"港快",
        "mexico"=>	"墨西哥（Correos de Mexico）",
        "romanian"=>	"罗马尼亚（Posta Romanian）",
        "tntitaly"=>	"TNT Italy",
        "multipack"=>	"Mexico Multipack",
        "portugalctt"=>	"葡萄牙（Portugal CTT）",
        "interlink"=>	"Interlink Express",
        "hzpl"=>	"华航",
        "gatikwe"=>	"Gati-KWE",
        "redexpress"=>	"Red Express",
        "mexicodenda"=>	"Mexico Senda Express",
        "tcixps"=>	"TCI XPS",
        "hre"=>	"高铁",
        "speedpost"=>	"新加坡EMS、大包(Singapore Speedpost)",
        "emsinten"=>	"EMS-国际件-英文",
        "asendiausa"=>	"Asendia USA",
        "chronopostfren"=>	"法国大包、EMS-英文(Chronopost France)",
        "italiane"=>	"意大利(Poste Italiane)",
        "gda"=>	"冠达",
        "chukou1"=>	"出口易",
        "huangmajia"=>	"黄马甲",
        "anlexpress"=>	"新干线",
        "shipgce"=>	"飞洋",
        "xlobo"=>	"贝海国际",
        "emirates"=>	"阿联酋(Emirates Post)",
        "nsf"=>	"新顺丰（NSF）",
        "pakistan"=>	"巴基斯坦(Pakistan Post)",
        "shiyunkuaidi"=>	"世运",
        "ucs"=>	"合众(UCS）",
        "afghan"=>	"阿富汗(Afghan Post)",
        "belpost"=>	"白俄罗斯(Belpochta)",
        "quantwl"=>	"全通",
        "efs"=>	"EFS Post",
        "tntpostcn"=>	"TNT Post",
        "gml"=>	"英脉",
        "gtongsudi"=>	"广通",
        "donghanwl"=>	"东瀚",
        "rpx"=>	"rpx",
        "rrs"=>	"日日顺",
        "htongexpress"=>	"华通",
        "kyrgyzpost"=>	"吉尔吉斯斯坦(Kyrgyz Post)",
        "latvia"=>	"拉脱维亚(Latvijas Pasts)",
        "libanpost"=>	"黎巴嫩(Liban Post)",
        "lithuania"=>	"立陶宛（Lietuvos pa?tas）",
        "maldives"=>	"马尔代夫(Maldives Post)",
        "malta"=>	"马耳他（Malta Post）",
        "macedonia"=>	"马其顿(Macedonian Post)",
        "newzealand"=>	"新西兰（New Zealand Post）",
        "moldova"=>	"摩尔多瓦(Posta Moldovei)",
        "bangladesh"=>	"孟加拉国(EMS)",
        "serbia"=>	"塞尔维亚(PE Post of Serbia)",
        "cypruspost"=>	"塞浦路斯(Cyprus Post)",
        "tunisia"=>	"突尼斯EMS(Rapid-Poste)",
        "uzbekistan"=>	"乌兹别克斯坦(Post of Uzbekistan)",
        "caledonia"=>	"新喀里多尼亚[法国](New Caledonia)",
        "republic"=>	"叙利亚(Syrian Post)",
        "haypost"=>	"亚美尼亚(Haypost-Armenian Postal)",
        "yemen"=>	"也门(Yemen Post)",
        "india"=>	"印度(India Post)",
        "england"=>	"英国(大包,EMS)",
        "jordan"=>	"约旦(Jordan Post)",
        "vietnam"=>	"越南小包(Vietnam Posts)",
        "montenegro"=>	"黑山(Po?ta Crne Gore)",
        "correos"=>	"哥斯达黎加(Correos de Costa Rica)",
        "xilaikd"=>	"西安喜来",
        "greenland"=>	"格陵兰[丹麦]（TELE Greenland A/S）",
        "phlpost"=>	"菲律宾（Philippine Postal）",
        "ecuador"=>	"厄瓜多尔(Correos del Ecuador)",
        "iceland"=>	"冰岛(Iceland Post)",
        "emonitoring"=>	"波兰小包(Poczta Polska)",
        "albania"=>	"阿尔巴尼亚(Posta shqipatre)",
        "aruba"=>	"阿鲁巴[荷兰]（Post Aruba）",
        "egypt"=>	"埃及（Egypt Post）",
        "omniva"=>	"爱沙尼亚(Eesti Post)",
        "leopard"=>	"云豹国际货运",
        "sinoairinex"=>	"中外运空运",
        "hyk"=>	"上海昊宏国际货物",
        "ckeex"=>	"城晓国际",
        "hungary"=>	"匈牙利（Magyar Posta）",
        "macao"=>	"澳门(Macau Post)",
        "postserv"=>	"台湾（中华邮政）",
        "kuaitao"=>	"快淘",
        "peru"=>	"秘鲁(SERPOST)",
        "indonesia"=>	"印度尼西亚EMS(Pos Indonesia-EMS)",
        "kazpost"=>	"哈萨克斯坦(Kazpost)",
        "lbbk"=>	"立白宝凯",
        "bqcwl"=>	"百千诚",
        "pfcexpress"=>	"皇家",
        "csuivi"=>	"法国(La Poste)",
        "austria"=>	"奥地利(Austrian Post)",
        "ukraine"=>	"乌克兰小包、大包(UkrPoshta)",
        "uganda"=>	"乌干达(Posta Uganda)",
        "azerbaijan"=>	"阿塞拜疆EMS(EMS AzerExpressPost)",
        "finland"=>	"芬兰(Itella Posti Oy)",
        "slovak"=>	"斯洛伐克(Slovenská Posta)",
        "ethiopia"=>	"埃塞俄比亚(Ethiopian postal)",
        "luxembourg"=>	"卢森堡(Luxembourg Post)",
        "mauritius"=>	"毛里求斯(Mauritius Post)",
        "brunei"=>	"文莱(Brunei Postal)",
        "quantium"=>	"Quantium",
        "tanzania"=>	"坦桑尼亚(Tanzania Posts)",
        "oman"=>	"阿曼(Oman Post)",
        "gibraltar"=>	"直布罗陀[英国]( Royal Gibraltar Post)",
        "byht"=>	"博源恒通",
        "vnpost"=>	"越南EMS(VNPost Express)",
        "anxl"=>	"安迅",
        "dfpost"=>	"达方",
        "huoban"=>	"兰州伙伴",
        "tianzong"=>	"天纵",
        "bohei"=>	"波黑(JP BH Posta)",
        "bolivia"=>	"玻利维亚",
        "cambodia"=>	"柬埔寨(Cambodia Post)",
        "bahrain"=>	"巴林(Bahrain Post)",
        "namibia"=>	"纳米比亚(NamPost)",
        "rwanda"=>	"卢旺达(Rwanda i-posita)",
        "lesotho"=>	"莱索托(Lesotho Post)",
        "kenya"=>	"肯尼亚(POSTA KENYA)",
        "cameroon"=>	"喀麦隆(CAMPOST)",
        "belize"=>	"伯利兹(Belize Postal)",
        "paraguay"=>	"巴拉圭(Correo Paraguayo)",
        "sfift"=>	"十方通",
        "hnfy"=>	"飞鹰",
        "iparcel"=>	"UPS i-parcle",
        "bjxsrd"=>	"鑫锐达",
        "mailikuaidi"=>	"麦力",
        "rfsd"=>	"瑞丰",
        "letseml"=>	"美联",
        "cnpex"=>	"CNPEX中邮",
        "xsrd"=>	"鑫世锐达",
        "chinatzx"=>	"同舟行",
        "qbexpress"=>	"秦邦",
        "idada"=>	"大达",
        "skynet"=>	"skynet",
        "nedahm"=>	"红马",
        "czwlyn"=>	"云南中诚",
        "wanboex"=>	"万博",
        "nntengda"=>	"腾达",
        "sujievip"=>	"郑州速捷",
        "gotoubi"=>	"UBI Australia",
        "ecmsglobal"=>	"ECMS Express",
        "fastgo"=>	"速派(FastGo)",
        "ecmscn"=>	"易客满",
        "eshunda"=>	"俄顺达",
        "suteng"=>	"广东速腾",
        "gdxp"=>	"新鹏",
        "yundaexus"=>	"美国韵达",
        "szdpex"=>	"深圳DPEX",
        "baishiwuliu"=>	"百世",
        "postnlpacle"=>	"荷兰包裹(PostNL International Parcels)",
        "ltexp"=>	"乐天",
        "ztong"=>	"智通",
        "xtb"=>	"鑫通宝",
        "airpak"=>	"airpak expresss",
        "postnlchina"=>	"荷兰邮政-中国件",
        "colissimo"=>	"法国小包（colissimo）",
        "pcaexpress"=>	"PCA Express",
        "hanrun"=>	"韩润",
        "cosco"=>	"中远e环球",
        "sundarexpress"=>	"顺达",
        "ajexpress"=>	"捷记方舟",
        "arkexpress"=>	"方舟",
        "adaexpress"=>	"明大",
        "changjiang"=>	"长江国际",
        "bdatong"=>	"八达通",
        "stoexpress"=>	"美国申通",
        "epanex"=>	"泛捷国际",
        "shunjiefengda"=>	"顺捷丰达",
        "nmhuahe"=>	"华赫",
        "deutschepost"=>	"德国(Deutsche Post)",
        "baitengwuliu"=>	"百腾",
        "pjbest"=>	"品骏",
        "quansutong"=>	"全速通",
        "zhongjiwuliu"=>	"中技",
        "jiuyescm"=>	"九曳供应链",
        "tykd"=>	"天翼",
        "dabei"=>	"德意思",
        "chengji"=>	"城际",
        "chengguangkuaidi"=>	"程光",
        "sagawa"=>	"佐川急便",
        "lantiankuaidi"=>	"蓝天",
        "yongchangwuliu"=>	"永昌",
        "birdex"=>	"笨鸟海淘",
        "yizhengdasuyun"=>	"一正达",
        "sdyoupei"=>	"优配",
        "trakpak"=>	"TRAKPAK",
        "gts"=>	"GTS",
        "aolau"=>	"AOL澳通",
        "yiex"=>	"宜送",
        "tongdaxing"=>	"通达兴",
        "hkposten"=>	"香港(HongKong Post)英文",
        "flysman"=>	"飞力士",
        "zhuanyunsifang"=>	"转运四方",
        "ilogen"=>	"logen路坚",
        "dongjun"=>	"成都东骏",
        "japanpost"=>	"日本郵便",
        "jiajiatong56"=>	"佳家通货运",
        "jrypex"=>	"吉日优派",
        "xaetc"=>	"西安胜峰",
        "doortodoor"=>	"CJ",
        "xintianjie"=>	"信天捷",
        "sd138"=>	"泰国138国际",
        "hjs"=>	"猴急送",
        "quanxintong"=>	"全信通",
        "amusorder"=>	"amazon-国际订单",
        "junfengguoji"=>	"骏丰国际",
        "kingfreight"=>	"货运皇",
        "subida"=>	"速必达",
        "sucmj"=>	"特急便",
        "yamaxunwuliu"=>	"亚马逊中国",
        "jinchengwuliu"=>	"锦程",
        "jgwl"=>	"景光",
        "yufeng"=>	"御风",
        "zhichengtongda"=>	"至诚通达",
        "rytsd"=>	"日益通",
        "hangyu"=>	"航宇",
        "pzhjst"=>	"急顺通",
        "yousutongda"=>	"优速通达",
        "qinyuan"=>	"秦远",
        "auexpress"=>	"澳邮中国",
        "zhdwl"=>	"众辉达",
        "fbkd"=>	"飞邦",
        "huada"=>	"华达",
        "fox"=>	"FOX GLOBAL EXPRESS",
        "huanqiu"=>	"环球",
        "huilian"=>	"辉联",
        "a2u"=>	"A2U",
        "ueq"=>	"UEQ",
        "scic"=>	"中加国际",
        "yidatong"=>	"易达通",
        "ruexp"=>	"捷网俄全通",
        "htwd"=>	"华通务达",
        "speedoex"=>	"申必达",
        "lianyun"=>	"联运",
        "jieanda"=>	"捷安达",
        "shlexp"=>	"SHL畅灵国际",
        "ewe"=>	"EWE全球",
        "abcglobal"=>	"全球",
        "mangguo"=>	"芒果",
        "goldhaitao"=>	"金海淘",
        "jiguang"=>	"极光",
        "ftd"=>	"富腾达国际",
        "dcs"=>	"DCS",
        "chengda"=>	"成达国际",
        "zhonghuan"=>	"中环",
        "shunbang"=>	"顺邦国际",
        "qichen"=>	"启辰国际",
        "auex"=>	"澳货通",
        "aosu"=>	"澳速",
        "aus"=>	"澳世",
        "tianma"=>	"天马迅达",
        "mjexp"=>	"美龙快递",
        "chunfai"=>	"香港骏辉物流",
        "zenzen"=>	"三三国际物流",
        "mxe56"=>	"淼信快递",
        "hipito"=>	"海派通",
        "pengcheng"=>	"鹏程快递",
        "guanting"=>	"冠庭国际物流",
        "jinan"=>	"金岸物流",
        "haidaibao"=>	"海带宝",
        "cllexpress"=>	"澳通华人物流",
        "banma"=>	"斑马物流",
        "youjia"=>	"友家速递",
        "buytong"=>	"百通物流",
        "xingyuankuaidi"=>	"新元快递",
        "quansu"=>	"全速物流",
        "sunjex"=>	"新杰物流",
        "lutong"=>	"鲁通快运",
        "xynyc"=>	"新元国际",
        "xiaocex"=>	"小C海淘",
        "airgtc"=>	"航空快递",
        "dindon"=>	"叮咚澳洲转运",
        "hqtd"=>	"环球通达",
        "haoyoukuai"=>	"好又快物流",
        "yongwangda"=>	"永旺达快递",
        "mchy"=>	"木春货运",
        "flyway"=>	"程光快递",
        "qzx56"=>	"全之鑫物流",
        "bsht"=>	"百事亨通",
        "ilyang"=>	"ILYANG",
        "xianfeng"=>	"先锋快递",
        "timedg"=>	"万家通快递",
        "meiquick"=>	"美快国际物流",
        "tny"=>	"泰中物流",
        "valueway"=>	"美通",
        "sunspeedy"=>	"新速航",
        "bphchina"=>	"速方",
        "yingchao"=>	"英超物流",
        "correoargentino"=>	"阿根廷(Correo Argentina)",
        "vanuatu"=>	"瓦努阿图(Vanuatu Post)",
        "barbados"=>	"巴巴多斯(Barbados Post)",
        "samoa"=>	"萨摩亚(Samoa Post)",
        "fiji"=>	"斐济(Fiji Post)",
        "edlogistics"=>	"益递物流",
        "esinotrans"=>	"中外运电子商务",
        "kuachangwuliu"=>	"跨畅（直邮易）",
        "cnausu"=>	"中澳速递",
        "gslhkd"=>	"联合快递",
        "ccd"=>	"河南次晨达",
        "benteng"=>	"奔腾物流",
        "mapleexpress"=>	"今枫国际快运",
        "topspeedex"=>	"中运全速",
        "yjxlm"=>	"宜家行",
        "otobv"=>	"中欧快运",
        "jmjss"=>	"金马甲",
        "onehcang"=>	"一号仓",
        "hfwuxi"=>	"和丰同城",
        "wtdchina"=>	"威时沛运货运",
        "shunjieda"=>	"顺捷达",
        "qskdyxgs"=>	"千顺快递",
        "tlky"=>	"天联快运",
        "cloudexpress"=>	"CE易欧通国际速递",
        "speeda"=>	"行必达",
        "zhongtongguoji"=>	"中通国际",
        "xipost"=>	"西邮寄",
        "nle"=>	"NLE",
        "nlebv"=>	"亚欧专线",
        "stkd"=>	"顺通快递",
        "sinatone"=>	"信联通",
        "auod"=>	"澳德物流",
        "ahdf"=>	"德方物流",
        "wzhaunyun"=>	"微转运",
        "lntjs"=>	"沈阳特急送",
        "iexpress"=>	"iExpress",
        "bcwelt"=>	"BCWELT",
        "euasia"=>	"欧亚专线",
        "ycgky"=>	"远成快运",
        "ledii"=>	"乐递供应链",
        "gswtkd"=>	"万通快递",
        "zyzoom"=>	"增速海淘",
        "globaltracktrace"=>	"globaltracktrace",
        "sendtochina"=>	"速递中国",
        "exfresh"=>	"安鲜达",
        "emsluqu"=>	"高考通知书",
        "sccod"=>	"丰程物流",
        "runhengfeng"=>	"全时速运",
        "hkems"=>	"云邮跨境快递",
        "skypost"=>	"荷兰Sky Post",
        "ruidaex"=>	"瑞达国际速递",
        "decnlh"=>	"德中快递",
        "suning"=>	"苏宁快递",
        "nzzto"=>	"新西兰中通",
        "lmfex"=>	"良藤国际速递",
        "lineone"=>	"一号线",
        "sihaiet"=>	"四海快递",
        "wotu"=>	"渥途国际速运",
        "dekuncn"=>	"德坤供应链",
        "zsky123"=>	"准实快运",
        "hongjie"=>	"宏捷国际物流",
        "hongxun"=>	"鸿讯物流",
        "ahkbps"=>	"卡邦配送",
    ];

    return $data[$code];
}

function randomFloat($min = 0, $max = 1) {
    $rand = $min + mt_rand() / mt_getrandmax() * ($max - $min);
    return number_format($rand, 2, '.', '');
}

function get_client_ip() {
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos)
            unset($arr[$pos]);
        $ip = trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';

    return $ip;
}

/**
 * 判断今天是否为五日场
 * @return bool
 */
function is_fiveday()
{
    $keys = ['fiveday_on', 'fiveday_begin', 'fiveday_space', 'fiveday_duration'];
    $systemData = \Illuminate\Support\Facades\DB::table('systems')->select('sys_key', 'sys_value')->whereIn('sys_key',  $keys)->get();

    $system = array();
    foreach ($systemData as $v) {
        $system[$v->sys_key] = $v->sys_value;
    }

    if (date('Y-m-d') === date('Y-m-d', $system['fiveday_on'])) {
        return true;
    }

    $todayBegin = strtotime(date('Y-m-d').' 00:00:00');
    $everyDay = 86400;
    $space = $system['fiveday_space'];
    $duration = $system['fiveday_duration'];

    if ($todayBegin >= $system['fiveday_begin'] && (($todayBegin - $system['fiveday_begin']) / $everyDay) % ($space + $duration) < $duration) {
        return true;
    }

    return false;
}

function receive_time($days)
{
	$day = 86400;
	$time = time() + $days * $day;
	return date('m月d日', $time) . '['. week(date('w', $time)) .']';
}

function week($num)
{
	$weeks = [
		'0'=>'周日',
		'1'=>'周一',
		'2'=>'周二',
		'3'=>'周三',
		'4'=>'周四',
		'5'=>'周五',
		'6'=>'周六',
	];
	
	return $weeks[$num];
}


