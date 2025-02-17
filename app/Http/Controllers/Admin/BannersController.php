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

class BannersController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perpage = 20;//每页显示记录数------`

        $dataCount = DB::table('banners') -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $bannerData = DB::table('banners') -> offset($page->getOffset()) -> orderBy('id','DESC') -> limit($perpage) -> get();

        return view('admin.banners.index', compact('bannerData', 'dataCount', 'pageShow'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::only('title','position','image','link','end_time','is_use','sort');
        /*
         * Models -> creste      Input::except('_token');
         * DB -> insert    Input::only('title','image','background_img')
         * */

        if($input['sort'] == ''){
            unset($input['sort']);
        }

        $rules = [
            'image' => 'required',
        ];

        $msg = [
            'image.required' => 'Please upload an image',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $input['end_time'] = strtotime($input['end_time']);
//            $input['password'] = Crypt::encrypt($input['password']);
//  添加时间(不需要就隐藏)          $input['addtime'] = time();

            $result = DB::table('banners') -> insert($input);//$input 数据插入到数据库
            /*
             * Models -> creste(可能)
             * DB -> insert
             * */
            if($result){
                return redirect('/admin/banners');
            }else{
                return back() -> with(['error'=>'Add data Failed']);
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
        $field = DB::table('banners') -> where('id', $id) -> first();

        if(!$field){
            return redirect('/admin/banners');
        }

        return view('admin.banners.edit', compact('field'));
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

        $rules = [
            'image' => 'required',
        ];

        $msg = [
            'image.required' => '请上传图片',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $input['end_time'] = strtotime($input['end_time']);
//            if(!$input['password']){
//                unset($input['password']);
//            }else{
//                $input['password'] = Crypt::encrypt($input['password']);
//            }

            $result = DB::table('banners') -> where('id', $id) -> update($input);

            if($result !== false){
                return redirect('/admin/banners?page='.$page);
            }else{
                return back() -> with(['error'=>'Update data Failed']);
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
        $result = DB::table('banners') -> where('id', $id) -> delete();

        if($result !== false){
            $data = [
                'status' => 0,
                'msg' => 'Delete data success',
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
