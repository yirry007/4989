<?php

namespace App\Http\Controllers\Admin;

use App\Tool\Page\Page;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class BusAdsController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perpage = 20;//每页显示记录数------`

        $dataCount = DB::table('bus_ads') -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $busAdsData = DB::table('bus_ads') -> select(['bus_ads.*', 'partners.name']) -> leftJoin('partners', 'bus_ads.partner_code', '=', 'partners.code') -> offset($page->getOffset()) -> orderBy('bus_ads.id','DESC') -> limit($perpage) -> get();

        return view('admin.bus_ads.index', compact('busAdsData', 'dataCount', 'pageShow'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $partners = DB::table('partners') -> select('name', 'code') -> get();

        return view('admin.bus_ads.create', compact('partners'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::only('url','image', 'partner_code');

        $result = DB::table('bus_ads') -> insert($input);

        if($result){
            return redirect('/admin/bus_ads');
        }else{
            return back() -> with(['error'=>'Add Bus Ads Fail']);
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
        $field = DB::table('bus_ads') -> where('id', $id) -> first();

        if(!$field){
            return redirect('/admin/bus_ads');
        }

        $partners = DB::table('partners') -> select('name', 'code') -> get();

        return view('admin.bus_ads.edit', compact('field', 'partners'));
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

        $result = DB::table('bus_ads') -> where('id', $id) -> update($input);

        if($result !== false){
            return redirect('/admin/bus_ads?page='.$page);
        }else{
            return back() -> with(['error'=>'Update Bus Ads Fail']);
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
        $field = DB::table('bus_ads') -> select('image') -> where('id', $id) -> first();

        $result = DB::table('bus_ads') -> where('id', $id) -> delete();

        if($result !== false){
            @unlink(public_path().'/'.$field->image);

            $data = [
                'status' => 0,
                'msg' => 'Delete Bus Ads Success',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => 'Delete Bus Ads Fail',
            ];
        }

        return $data;
    }
}
