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

class CategoriesController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categoryModel = new Category();
        $categoryData = $categoryModel -> getTree();

        return view('admin.categories.index', compact('categoryData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoryModel = new Category();
        $categoryData = $categoryModel -> getTree();

        return view('admin.categories.create', compact('categoryData'));
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
        /*
         * Models -> creste      Input::except('_token');
         * DB -> insert    Input::only('title','image','background_img')
         * */

        if($input['sort'] == ''){
            unset($input['sort']);
        }

        $rules = [
            'name' => 'required|max:20',
        ];

        $msg = [
            'name.required' => 'Please input category name',
            'name.max' => 'Category name cannot be more than 20 characters',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
//            $input['password'] = Crypt::encrypt($input['password']);
//  添加时间(不需要就隐藏)          $input['addtime'] = time();

            $result = DB::table('categories') -> insert($input);//$input 数据插入到数据库
            /*
             * Models -> creste(可能)
             * DB -> insert
             * */
            if($result){
                return redirect('/admin/categories');
            }else{
                return back() -> with(['error'=>'Add Category Failed']);
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
        return view('admin.categories.batch_create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categoryModel = new Category();
        $categoryData = $categoryModel -> getTree();
        $categoryChildren = $categoryModel -> getChildren($id);
        $field = $categoryModel -> find($id);

        if(!$field){
            return redirect('/admin/categories');
        }

        return view('admin.categories.edit', compact('categoryData', 'field', 'categoryChildren'));
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

        if($input['sort'] == ''){
            unset($input['sort']);
        }

        $rules = [
            'name' => 'required|max:20',
        ];

        $msg = [
            'name.required' => 'Please input category name',
            'name.max' => 'Category name cannot be more than 20 characters',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $result = Category::where('id', $id) -> update($input);

            if($result !== false){
                return redirect('/admin/categories');
            }else{
                return back() -> with(['error'=>'Update Category Failed']);
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
        $categoryModel = new Category();
        $categoryChildren = $categoryModel -> getChildren($id);

        if(!empty($categoryChildren)){
            $categoryData = $categoryModel -> whereIn('id', $categoryChildren) -> get();
            foreach($categoryData as $v){
                @unlink(public_path().'/'.$v->image);
            }
            $categoryModel -> destroy($categoryChildren);
        }

        $field = $categoryModel -> find($id);
        @unlink(public_path().'/'.$field->image);
        $result = $categoryModel -> destroy($id);

        if($result !== false){
            $data = [
                'status' => 0,
                'msg' => 'Delete Category Success',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => 'Delete Category Failed',
            ];
        }

        return $data;
    }
}

/*
 * $arr = array();
 * $arr[] = 1;//$arr[0] = 1
 * $arr[] = 1;//$arr[1] = 1
 *
 * $arr['num'] = 1; //
 */
