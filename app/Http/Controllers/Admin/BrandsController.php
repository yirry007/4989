<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Role;
use App\Tool\Page\Page;
use Illuminate\Http\Request;
use App\Models\Category;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class BrandsController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = Input::only('name');

        $perpage = 20;//每页显示记录数------`

        $dataCount = DB::table('brands') -> where(function($query) use($search){
            $brandName = $search['name'];
            if($brandName){
                $query -> where('name', 'like', '%'.$brandName.'%');
            }
        }) -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $brandData = DB::table('brands') -> where(function($query) use($search){
            $brandName = $search['name'];
            if($brandName){
                $query -> where('name', 'like', '%'.$brandName.'%');
            }
        }) -> orderBy('id','DESC') -> offset($page->getOffset()) -> limit($perpage) -> get();

        $system = DB::table('systems') -> select('sys_value') -> where('sys_key','defaultportrait') -> first();

        return view('admin.brands.index', compact('brandData', 'dataCount', 'pageShow','system'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $system = DB::table('systems') -> select('sys_value') -> where('sys_key','defaultportrait') -> first();

        return view('admin.brands.create',compact('system'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::only('name','image','is_use','is_rec','is_korea','sort');
        /*
         * Models -> creste      Input::except('_token');
         * DB -> insert    Input::only('title','image','background_img')
         * */

        if($input['sort'] == ''){
            $input['sort'] = 0;
        }

        $rules = [
            'name' => 'required|max:20|unique:brands,name',
        ];

        $msg = [
            'name.required' => 'Please input brand name',
            'name.max' => 'Brand name can not be more than 20 character',
            'name.unique' => 'Brand name already exists',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
//            $input['password'] = Crypt::encrypt($input['password']);
//  添加时间(不需要就隐藏)          $input['addtime'] = time();

            $result = DB::table('brands') -> insert($input);//$input 数据插入到数据库
            /*
             * Models -> creste(可能)
             * DB -> insert
             * */
            if($result){
                return redirect('/admin/brands');
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
        $field = DB::table('brands') -> where('id', $id) -> first();

        if(!$field){
            return redirect('/admin/brands');
        }

        $system = DB::table('systems') -> select('sys_value') -> where('sys_key','defaultportrait') -> first();

        return view('admin.brands.edit', compact('field','system'));
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
            $input['sort'] = 0;
        }

        $rules = [
            'name' => 'required|max:20|unique:brands,name,'.$id,
        ];

        $msg = [
            'name.required' => 'Please input brand name',
            'name.max' => 'Brand name can not be more than 20 character',
            'name.unique' => 'Brand name already exists',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
//            if(!$input['password']){
//                unset($input['password']);
//            }else{
//                $input['password'] = Crypt::encrypt($input['password']);
//            }

            $result = DB::table('brands') -> where('id', $id) -> update($input);

            if($result !== false){
                return redirect('/admin/brands?page='.$page);
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
        $result = DB::table('brands') -> where('id', $id) -> delete();

        if($result !== false){
            DB::table('favors') -> where('brand_id', $id) -> delete();
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
