<?php

namespace App\Http\Controllers\Admin;


use App\Tool\Page\Page;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class TopupMoneysController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $topupData = DB::table('topup_moneys') -> get();

        return view('admin.topup_moneys.index', compact('topupData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.topup_moneys.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::only('money', 'memo');
        /*
         * Models -> creste      Input::except('_token');
         * DB -> insert    Input::only('title','image','background_img')
         * */

        $rules = [
            'money' => 'required',
        ];

        $msg = [
            'money.required' => 'Please input topup money',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $result = DB::table('topup_moneys') -> insert($input);//$input 数据插入到数据库
            /*
             * Models -> creste(可能)
             * DB -> insert
             * */
            if($result){
                return redirect('/admin/topup_moneys');
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
        $field = DB::table('topup_moneys') -> where('id', $id) -> first();

        if(!$field){
            return redirect('/admin/topup_moneys');
        }

        return view('admin.topup_moneys.edit', compact('field'));
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
        $input = Input::only('money', 'memo', 'page');

        $page = $input['page'];
        unset($input['page']);

        $rules = [
            'money' => 'required',
        ];

        $msg = [
            'money.required' => 'Please input topup money',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $result = DB::table('topup_moneys') -> where('id', $id) -> update($input);

            if($result !== false){
                return redirect('/admin/topup_moneys?page='.$page);
            }else{
                return back() -> with(['error'=>'Update data failed']);
            }
        }else{
            return back() -> withErrors($validator);
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
        $result = DB::table('topup_moneys') -> where('id', $id) -> delete();

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
}
