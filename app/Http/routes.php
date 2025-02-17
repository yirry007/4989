<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('login', 'BaseController@login');

Route::group(['namespace'=>'Home'], function(){
    Route::get('/', 'IndexController@index');
    Route::get('get_header_info', 'IndexController@getHeaderInfo');
    Route::get('/index', 'IndexController@index');

    Route::get('/nav', 'IndexController@nav');

    Route::get('/goods', 'IndexController@goods');
    Route::get('/goods_view/{id}', 'IndexController@goodsView');
    Route::get('ajax_get_goods', 'IndexController@ajaxGetGoods');

    Route::get('/brand', 'IndexController@brand');
    Route::get('/brand_view/{id}', 'IndexController@brandView');

    Route::get('ads', 'IndexController@ads');
    Route::get('ajax_get_ads', 'IndexController@getAds');

    Route::group(['middleware'=>'home.login'], function(){
        Route::get('/address', 'IndexController@address');
        Route::get('/address_edit/{id}', 'IndexController@addressEdit');

        Route::get('/cart', 'IndexController@cart');
        Route::get('/buy', 'IndexController@buy');
        Route::get('/payok', 'IndexController@payok');

        Route::get('/wxpay', 'IndexController@wxpay');

        Route::get('/my', 'IndexController@my');
        Route::get('/account', 'IndexController@account');

        Route::get('/topup', 'IndexController@topupView');
        Route::get('/wx_topup', 'IndexController@wxTopup');

        Route::get('/order', 'IndexController@order');
        Route::get('/order_express/{express_num}/{express}', 'IndexController@orderExpress');

        Route::get('/favor', 'IndexController@favor');
        Route::get('/favor_brand', 'IndexController@favorBrand');

        Route::get('/suggest', 'IndexController@suggestView');

        //Route::get('/praise', 'IndexController@praiseView');
    });

    Route::post('/ajax_goods_favor', 'IndexController@ajaxGoodsFavor');
    Route::post('ajax_add_cart', 'IndexController@ajaxAddCart');
    Route::post('ajax_add_buy', 'IndexController@ajaxAddBuy');

    Route::get('ajax_get_brand_goods', 'IndexController@ajaxGetBrandGoods');
    Route::post('/ajax_brand_favor', 'IndexController@ajaxBrandFavor');

    Route::post('ajax_select_address', 'IndexController@ajaxSelectAddress');
    Route::post('ajax_add_address', 'IndexController@ajaxAddAddress');
    Route::post('ajax_delete_address', 'IndexController@ajaxDeleteAddress');
    Route::post('ajax_edit_address', 'IndexController@ajaxEditAddress');

    Route::post('ajax_update_cart', 'IndexController@ajaxUpdateCart');
    Route::post('ajax_delete_cart', 'IndexController@ajaxDeleteCart');
    Route::post('ajax_cart_add_buy', 'IndexController@ajaxCartAddBuy');

    Route::post('ajax_buy_pay', 'IndexController@ajaxBuyPay');
    Route::post('ajax_buy_vip_pay', 'IndexController@ajaxBuyVipPay');
    Route::get('/ajax_share_return_money', 'IndexController@ajaxShareReturnMoney');
    Route::get('/ajax_payok', 'IndexController@ajaxPayok');

    Route::post('we_notify/{order_num}', 'IndexController@weNotify');

    Route::get('/get_cash_flow', 'IndexController@getCashFlow');
    Route::post('/topup', 'IndexController@topup');
    Route::post('topup_notify/{topup_sn}', 'IndexController@topupNotify');
    Route::post('/ajax_charge', 'IndexController@ajaxCharge');

    Route::post('ajax_order_pay', 'IndexController@ajaxOrderPay');
    Route::post('ajax_order_check', 'IndexController@ajaxOrderCheck');
    Route::post('ajax_order_delete', 'IndexController@ajaxOrderDelete');

    Route::post('ajax_favor_delete', 'IndexController@ajaxFavorDelete');

    Route::post('/suggest', 'IndexController@suggest');
    Route::post('/send_suggest_msg/{suggest_id}', 'IndexController@sendSuggestMsg');

    //Route::post('/praise', 'IndexController@praise');
    //Route::post('/send_praise_msg/{praise_id}', 'IndexController@sendPraiseMsg');
    Route::any('upload_one/{path}', 'CommonController@uploadOne');
    Route::any('delete_one', 'CommonController@deleteOne');
});

Route::group(['prefix'=>'bus', 'namespace'=>'Bus'], function(){
    Route::get('line', 'IndexController@line');
    Route::get('line_list', 'IndexController@lineList');
    Route::get('line_view', 'IndexController@lineView');
    Route::get('station', 'IndexController@station');
    Route::get('station_list', 'IndexController@stationList');
    Route::get('station_view', 'IndexController@stationView');
});

Route::group(['prefix'=>'api', 'namespace'=>'Admin', 'middleware'=>'enable.cross'], function(){
    Route::get('get_partner/{code}', 'PartnersController@getPartner');
});

Route::group(['namespace'=>'Event'], function(){
    Route::get('yesterday', 'IndexController@yesterday');
    Route::get('get_yesterday', 'IndexController@getYesterday');
    Route::get('shared/{id}', 'IndexController@shared');
    Route::get('get_shared/{id}', 'IndexController@getShared');

    Route::get('fiveday', 'IndexController@fiveday');

    Route::get('article/{id}', 'IndexController@article');
    Route::get('article_content/{id}', 'IndexController@articleContent');

    Route::group(['middleware'=>'home.login'], function(){
        //Route::get('signed', 'IndexController@signed');
        //Route::get('finish', 'IndexController@finish');
        //Route::get('sign_enter', 'IndexController@signEnter');
        //Route::get('sign', 'IndexController@signView');
        //Route::get('transfer', 'IndexController@transferView');
    });
    
    //Route::get('get_signed', 'IndexController@getSigned');
    //Route::post('sign', 'IndexController@sign');
    //Route::post('transfer', 'IndexController@transfer');

    Route::any('user_upload/{path}', 'IndexController@userUpload');
    Route::any('user_delete', 'IndexController@userDelete');
});

Route::group(['prefix'=>'admin', 'namespace'=>'Admin'], function(){
    Route::any('login', 'LoginController@login');
    Route::get('code', 'LoginController@code');

    Route::get('order_view/{sn}', 'OrdersController@orderView');

    Route::group(['middleware'=>'admin.login'], function(){
        Route::get('logout', 'LoginController@logout');

        Route::get('/', 'IndexController@index');
        Route::get('main', 'IndexController@main');

        Route::any('upload_one/{path}/{width?}/{height?}', 'CommonController@uploadOne');
        Route::any('delete_one', 'CommonController@deleteOne');
        Route::any('upload_many/{dir}/{fileNum?}', 'CommonController@uploadMany');
        Route::any('delete_image', 'CommonController@deleteImage');

        Route::post('delete_cache', 'IndexController@deleteCache');

        Route::get('sale_condition_graph', 'StatisticsController@saleContitionGraph');

        Route::get('system', 'SystemController@system');
        Route::post('system', 'SystemController@update');
        Route::get('env', 'SystemController@env');
        Route::post('env', 'SystemController@refresh');

        Route::get('order_print_view', 'OrdersController@orderPrintView');
        Route::get('order_print', 'OrdersController@orderPrint');

        Route::resource('admin', 'AdminController');
        Route::resource('banners', 'BannersController');
        Route::resource('articles', 'ArticlesController');
        Route::resource('ads', 'AdsController');
        Route::resource('categories', 'CategoriesController');
        Route::resource('brands', 'BrandsController');
        Route::resource('goods', 'GoodsController');
        Route::resource('members', 'MembersController');
        Route::get('suggest', 'MembersController@suggest');
        Route::post('suggest_del/{id}', 'MembersController@suggestDel');
        Route::post('suggest_confirm/{id}', 'MembersController@suggestConfirm');
        Route::get('bonus_view/{id}', 'MembersController@bonusView');
        Route::post('bonus', 'MembersController@bonus');
        Route::post('bonus_del/{id}', 'MembersController@bonusDel');
        Route::get('bonus_list', 'MembersController@bonusList');
        Route::get('topups', 'MembersController@topups');
        Route::get('withdraw', 'MembersController@withdraw');
        Route::get('sign', 'MembersController@sign');
        Route::resource('member_groups', 'MemberGroupsController');
        Route::resource('topup_moneys', 'TopupMoneysController');
        Route::post('set_group', 'MembersController@setGroup');
        Route::resource('orders', 'OrdersController');
        Route::get('order_goods/{id}', 'OrdersController@orderGoods');
        Route::get('sale_goods', 'OrdersController@saleGoods');
        Route::get('benefit', 'OrdersController@benefit');
        Route::get('partner_excel', 'OrdersController@partnerExcel');
        Route::get('areas', 'OrdersController@areas');
        Route::get('express', 'ExpressController@index');
        Route::post('express_edit', 'ExpressController@edit');
        Route::get('email', 'EmailController@index');
        Route::post('email_edit', 'EmailController@edit');
        Route::resource('partners', 'PartnersController');
        Route::get('get_partner_code', 'PartnersController@getPartnerCode');
        Route::resource('bus_ads', 'BusAdsController');
    });

});


Route::group(['prefix'=>'weixin', 'namespace'=>'Weixin'], function(){

    Route::any('listen', 'IndexController@listen');

    Route::any('login', 'LoginController@login');
    Route::get('code', 'LoginController@code');

    Route::group(['middleware'=>'weixin.login'], function(){

        Route::get('logout', 'LoginController@logout');
        Route::get('password', 'LoginController@password');
        Route::post('password', 'LoginController@passwordSet');

        //以下三项为临时使用链接，开发完后删除
        Route::get('icon', 'IndexController@icon');
        Route::get('lst', 'IndexController@lst');
        Route::get('add', 'IndexController@add');

        Route::get('/', 'IndexController@index');
        Route::get('main', 'IndexController@main');
        Route::get('config', 'IndexController@config');
        Route::post('config', 'IndexController@configSet');

        Route::get('subscribe_event', 'IndexController@subscribeEventView');
        Route::post('subscribe_event', 'IndexController@subscribeEvent');

        Route::get('reply', 'IndexController@reply');
        Route::get('reply_add', 'IndexController@replyAddView');
        Route::post('reply_add', 'IndexController@replyAdd');
        Route::get('reply_edit/{id}', 'IndexController@replyEditView');
        Route::post('reply_edit/{id}', 'IndexController@replyEdit');
        Route::post('reply_del/{id}', 'IndexController@replyDel');

        Route::get('menu', 'IndexController@menu');
        Route::post('menu_edit', 'IndexController@menuEdit');
        Route::get('menu_event', 'IndexController@menuEventView');
        Route::post('menu_event', 'IndexController@menuEvent');
        Route::get('menu_reset', 'IndexController@menuReset');

        Route::get('set_industry', 'IndexController@setIndustryView');
        Route::post('set_industry', 'IndexController@setIndustry');

        Route::get('template', 'IndexController@template');
        Route::get('template_add', 'IndexController@templateAddView');
        Route::post('template_add', 'IndexController@templateAdd');
        Route::post('template_del/{id}', 'IndexController@templateDel');
        Route::get('send_template', 'IndexController@sendTemplate');
        Route::get('msg', 'IndexController@msgView');
        Route::post('msg', 'IndexController@msg');

        Route::get('broadcast', 'IndexController@broadcastView');
        Route::post('broadcast', 'IndexController@broadcast');

        Route::get('user', 'IndexController@user');
        Route::post('move_users', 'IndexController@moveUsers');
        Route::get('user_edit_view/{openid}', 'IndexController@userEditView');
        Route::post('user_edit', 'IndexController@userEdit');
        Route::get('user_subscribe', 'IndexController@userSubscribe');

        Route::get('group', 'IndexController@group');
        Route::get('group_add', 'IndexController@groupAddView');
        Route::post('group_add', 'IndexController@groupAdd');
        Route::get('group_edit/{id}', 'IndexController@groupEditView');
        Route::post('group_edit', 'IndexController@groupEdit');
        Route::post('group_delete', 'IndexController@groupDelete');

        Route::get('tag', 'IndexController@tag');
        Route::get('tag_add', 'IndexController@tagAddView');
        Route::post('tag_add', 'IndexController@tagAdd');
        Route::get('tag_edit/{id}', 'IndexController@tagEditView');
        Route::post('tag_edit', 'IndexController@tagEdit');
        Route::post('tag_delete', 'IndexController@tagDelete');

        Route::get('image', 'IndexController@image');
        Route::get('image_add', 'IndexController@imageAddView');
        Route::post('image_add', 'IndexController@imageAdd');
        Route::post('image_delete', 'IndexController@imageDelete');

        Route::get('voice', 'IndexController@voice');
        Route::get('voice_add', 'IndexController@voiceAddView');
        Route::post('voice_add', 'IndexController@voiceAdd');
        Route::post('voice_delete', 'IndexController@voiceDelete');

        Route::get('video', 'IndexController@video');
        Route::get('video_add', 'IndexController@videoAddView');
        Route::post('video_add', 'IndexController@videoAdd');
        Route::post('video_delete', 'IndexController@videoDelete');

        Route::get('news', 'IndexController@news');
        Route::get('news_add', 'IndexController@newsAddView');
        Route::post('news_add', 'IndexController@newsAdd');
        Route::post('store_news', 'IndexController@storeNews');
        Route::get('news_edit', 'IndexController@newsEditView');
        Route::post('news_edit', 'IndexController@newsEdit');
        Route::post('news_delete', 'IndexController@newsDelete');

        Route::get('staff', 'IndexController@staff');
        Route::get('staff_add', 'IndexController@staffAddView');
        Route::post('staff_add', 'IndexController@staffAdd');
        Route::get('staff_edit', 'IndexController@staffEditView');
        Route::post('staff_edit', 'IndexController@staffEdit');
        Route::post('staff_delete', 'IndexController@staffDelete');

        Route::get('staff_send', 'IndexController@staffSendView');
        Route::post('staff_send', 'IndexController@staffSend');
    });
});
