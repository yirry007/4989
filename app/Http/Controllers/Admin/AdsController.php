<?php

namespace App\Http\Controllers\Admin;

use App\Tool\Page\Page;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class AdsController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perpage = 20;//每页显示记录数------`

        $dataCount = DB::table('ads') -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $adsData = DB::table('ads') -> offset($page->getOffset()) -> orderBy('id','DESC') -> limit($perpage) -> get();

        return view('admin.ads.index', compact('adsData', 'dataCount', 'pageShow'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.ads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::only('url','image', 'sort');
        /*
         * Models -> creste      Input::except('_token');
         * DB -> insert    Input::only('title','image','background_img')
         * */

        if($input['sort'] == ''){
            unset($input['sort']);
        }

        $result = DB::table('ads') -> insert($input);//$input 数据插入到数据库
        /*
         * Models -> create(可能)
         * DB -> insert
         * */
        if($result){
            return redirect('/admin/ads');
        }else{
            return back() -> with(['error'=>'Add data error']);
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
        $field = DB::table('ads') -> where('id', $id) -> first();

        if(!$field){
            return redirect('/admin/ads');
        }

        return view('admin.ads.edit', compact('field'));
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

        if($input['sort'] == ''){
            unset($input['sort']);
        }

        $result = DB::table('ads') -> where('id', $id) -> update($input);

        if($result !== false){
            return redirect('/admin/ads?page='.$page);
        }else{
            return back() -> with(['error'=>'Update data error']);
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
        $result = DB::table('ads') -> where('id', $id) -> delete();

        if($result !== false){
            $data = [
                'status' => 0,
                'msg' => 'Delete data success',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => 'Delete data error',
            ];
        }

        return $data;
    }
}
