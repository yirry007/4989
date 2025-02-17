<?php

namespace App\Http\Controllers\Bus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class IndexController extends CommonController
{
    public function line()
    {
        return view('bus.line');
    }

    public function lineList()
    {
        return view('bus.line_list');
    }

    public function lineView()
    {
        return view('bus.line_view');
    }

    public function station()
    {
        return view('bus.station');
    }

    public function stationList()
    {
        return view('bus.station_list');
    }

    public function stationView()
    {
        $input = Input::only('bus_id', 'bus_ids');
        $bus_id = $input['bus_id'];
        $bus_ids = $input['bus_ids'];

        return view('bus.station_view', compact('bus_id', 'bus_ids'));
    }
}
