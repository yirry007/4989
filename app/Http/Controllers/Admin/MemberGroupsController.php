<?php

namespace App\Http\Controllers\Admin;


use App\Tool\Page\Page;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class MemberGroupsController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groupData = DB::table('member_groups') -> get();

        return view('admin.member_groups.index', compact('groupData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.member_groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::only('name');
        /*
         * Models -> creste      Input::except('_token');
         * DB -> insert    Input::only('title','image','background_img')
         * */

        $rules = [
            'name' => 'required',
        ];

        $msg = [
            'name.required' => 'Please input group name',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $result = DB::table('member_groups') -> insert($input);//$input 数据插入到数据库
            /*
             * Models -> creste(可能)
             * DB -> insert
             * */
            if($result){
                return redirect('/admin/member_groups');
            }else{
                return back() -> with(['error'=>'Add group failed']);
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
        $field = DB::table('member_groups') -> where('id', $id) -> first();

        if(!$field){
            return redirect('/admin/member_groups');
        }

        return view('admin.member_groups.edit', compact('field'));
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
        $input = Input::only('name', 'page');

        $page = $input['page'];
        unset($input['page']);

        $rules = [
            'name' => 'required',
        ];

        $msg = [
            'name.required' => 'Please input group name',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $result = DB::table('member_groups') -> where('id', $id) -> update($input);

            if($result !== false){
                return redirect('/admin/member_groups?page='.$page);
            }else{
                return back() -> with(['error'=>'Update group failed']);
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
        $result = DB::table('member_groups') -> where('id', $id) -> delete();

        if($result !== false){
            $data = [
                'status' => 0,
                'msg' => 'Delete group success',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => 'Delete group failed',
            ];
        }

        return $data;
    }
}
