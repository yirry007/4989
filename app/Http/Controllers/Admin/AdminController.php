<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Role;
use App\Tool\Page\Page;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class AdminController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perpage = 20;//每页显示记录数

        $dataCount = Admin::count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $adminData = Admin::offset($page->getOffset()) -> limit($perpage) -> get();

        return view('admin.admin.index', compact('adminData', 'dataCount', 'pageShow'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::except('_token');

        $rules = [
            'username' => 'required|max:30|unique:admins,username',
            'password' => 'required',
        ];

        $msg = [
            'username.required' => 'Please input username',
            'username.max' => 'Username must be 30 character long',
            'username.unique' => 'Username has been exists',
            'password.required' => 'Please input password',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $input['password'] = Crypt::encrypt($input['password']);
            $input['addtime'] = time();

            $result = Admin::create($input);//$input 数据插入到数据库

            if($result){
                return redirect('/admin/admin');
            }else{
                return back() -> with(['error'=>'Add data fail']);
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
        $field = Admin::where('id', $id) -> first();

        if(!$field){
            return redirect('/admin/admin');
        }

        return view('admin.admin.edit', compact('field'));
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

        $rules = [
            'username' => 'required|max:50|unique:admins,username,'.$id,
            'password' => 'required',
        ];

        $msg = [
            'username.required' => 'Please input username',
            'username.max' => 'Username must be 30 character long',
            'username.unique' => 'Username has been exists',
            'password.required' => 'Please input password',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            if(!$input['password']){
                unset($input['password']);
            }else{
                $input['password'] = Crypt::encrypt($input['password']);
            }

            $result = Admin::where('id', $id) -> update($input);

            if($result !== false){
                return redirect('/admin/admin?page='.$page);
            }else{
                return back() -> with(['error'=>'Update data fail']);
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
        if($id == 1){
            $data = ['status' => 2,'msg' => 'Can not delete admin'];
            return $data;
        }

        //$result = Admin::destroy($id);
        $result = Admin::where('id', $id) -> delete();

        if($result !== false){
            $data = [
                'status' => 0,
                'msg' => 'Delete data success',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => 'Delete data fail',
            ];
        }

        return $data;
    }
}
