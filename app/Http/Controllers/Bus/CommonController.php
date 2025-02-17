<?php

namespace App\Http\Controllers\Bus;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class CommonController extends BaseController
{
    public function __construct(Request $request){
        parent::__construct($request);

        $ip = get_client_ip();
        $url = 'https://restapi.amap.com/v3/ip?ip='.$ip.'&key=f2d68b18a08f66196e3cb8d60d1c19da';
        $location = file_get_contents($url);

        $location = (array)(json_decode($location));

        $location['adcode'] = !empty($location['adcode']) ? $location['adcode'] : '222401';
        $location['city'] = !empty($location['city']) ? $location['city'] : '延边朝鲜族自治州';

        $partnerCode = session('partner_code');

        if(!$partnerCode){
            $defaultPartner = DB::table('partners') -> select('code') -> orderBy('id', 'ASC') -> first();
            $partnerCode = $defaultPartner->code;
        }

        $busAd = DB::table('bus_ads') -> where('partner_code', $partnerCode) -> orderByRaw('RAND()') -> first();

        View::share(array(
            'busAd' => $busAd,
            'location' => $location
        ));
    }
}
