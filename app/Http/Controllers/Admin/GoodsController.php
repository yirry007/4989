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

class GoodsController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = Input::only('name', 'other_name', 'category_id', 'brand_id', 'express_id', 'fiveday');

        $perpage = 20;//每页显示记录数------`

        $goodsFilter = array();
        if($search['category_id'] && $search['category_id'] != '-1'){
            $categories = DB::table('goods_categories') -> where('category_id', $search['category_id']) -> get();
            foreach($categories as $v){
                $goodsFilter[] = $v->goods_id;
            }
        }

        $dataCount = DB::table('goods') -> where(function($query) use($search){
            $goodName = $search['name'];
            $otherName = $search['other_name'];
            $categoryId = $search['category_id'];
            $brandId = $search['brand_id'];
            $expressId = $search['express_id'];
            $fiveday = $search['fiveday'];
            if($goodName){
                $query -> where('name', 'like', '%'.$goodName.'%');
            }
            if($otherName){
                $query -> where('other_name', 'like', '%'.$otherName.'%');
            }
            if($categoryId && $categoryId != '-1'){
                $query -> where('category_id', $categoryId);
            }
            if($brandId && $brandId != '-1'){
                $query -> where('brand_id', $brandId);
            }
            if($expressId != null && $expressId != '-1'){
                $query -> where('express_id', $expressId);
            }
            if($fiveday != null && $fiveday != '-1'){
                $query -> where('fiveday', $fiveday);
            }
        }) -> orWhere(function($query) use($goodsFilter){
            if(!empty($goodsFilter)){
                $query->whereIn('id', $goodsFilter);
            }
        }) -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $goodsData = DB::table('goods') -> select('goods.*','categories.name as cat_name','brands.name as brand_name', 'expresses.province_name') -> leftJoin('categories', 'goods.category_id', '=', 'categories.id') -> leftJoin('brands', 'goods.brand_id', '=', 'brands.id') -> leftJoin('expresses', 'goods.express_id', '=', 'expresses.id') -> where(function($query) use($search){
            $goodsName = $search['name'];
            $otherName = $search['other_name'];
            $categoryId = $search['category_id'];
            $brandId = $search['brand_id'];
            $expressId = $search['express_id'];
            $fiveday = $search['fiveday'];
            if($goodsName){
                $query -> where('goods.name', 'like', '%'.$goodsName.'%');
            }
            if($otherName){
                $query -> where('other_name', 'like', '%'.$otherName.'%');
            }
            if($categoryId && $categoryId != '-1'){
                $query -> where('category_id', $categoryId);
            }
            if($brandId && $brandId != '-1'){
                $query -> where('brand_id', $brandId);
            }
            if($expressId != null && $expressId != '-1'){
                $query -> where('express_id', $expressId);
            }
            if($fiveday != null && $fiveday != '-1'){
                $query -> where('fiveday', $fiveday);
            }
        }) -> orWhere(function($query) use($goodsFilter){
            if(!empty($goodsFilter)){
                $query->whereIn('goods.id', $goodsFilter);
            }
        }) -> orderBy('id','DESC') -> offset($page->getOffset()) -> limit($perpage) -> get();

        $categoryData = DB::table('categories') -> where('parent_id', '!=', '0') -> get();
        $brandData = DB::table('brands') -> get();
        $expressData = DB::table('expresses') -> get();

        return view('admin.goods.index', compact('goodsData', 'dataCount', 'pageShow', 'categoryData', 'brandData', 'expressData'));
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

        $brandData = DB::table('brands') -> get();

        $express = DB::table('expresses') -> select(['id', 'province_name']) -> orderBy('id', 'ASC') -> get();

        return view('admin.goods.create', compact('categoryData', 'brandData', 'express'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::only('name','other_name','video','poster','category_id','brand_id','image','detail','market_price','price_name','org_price_name','price','org_price','weight','express_id','limit_num','sale','stock','is_use','is_rec','is_sale','is_one','special','fiveday','sort');
        /*
         * Models -> creste      Input::except('_token');
         * DB -> insert    Input::only('title','image','background_img')
         * */

        if($input['limit_num'] == ''){
            $input['limit_num'] = 1;
        }
        if($input['sale'] == ''){
            $input['sale'] = 0;
        }
        if($input['sort'] == ''){
            $input['sort'] = 0;
        }

        $rules = [
            'name' => 'required|max:50',
            'image' => 'required',
            'market_price' => 'required',
            'price' => 'required',
            'org_price' => 'required',
            'weight' => 'required',
            'stock' => 'required',
        ];

        $msg = [
            'name.required' => 'Please input goods name',
            'name.max' => 'Goods name cannot be more than 50 characters',
            'image.required' => 'Please upload image',
            'market_price.required' => 'Please input event price',
            'price.required' => 'Please input price',
            'org_price.required' => 'Please input original price',
            'weight.required' => 'Please input weight',
            'stock.required' => 'Please input stock',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $result = DB::table('goods') -> insertGetId($input);//$input 数据插入到数据库

            if($result){
                $goodsCategories = Input::only('goods_category');
                if($goodsCategories['goods_category']){
                    $categoryData = array();

                    foreach($goodsCategories['goods_category'] as $k=>$v){
                        $categoryData[$k]['goods_id'] = $result;
                        $categoryData[$k]['category_id'] = $v;
                    }

                    DB::table('goods_categories') -> insert($categoryData);
                }

                $goodsImages = Input::only('goods_images');
                if($goodsImages['goods_images']){
                    $imageData = array();

                    foreach($goodsImages['goods_images'] as $k=>$v){
                        $_imageArr = explode('@', $v);

                        $imageData[$k]['goods_id'] = $result;
                        $imageData[$k]['img'] = $_imageArr[2];
                        $imageData[$k]['big_img'] = $_imageArr[1];
                        $imageData[$k]['sm_img'] = $_imageArr[0];
                    }

                    DB::table('goods_images') -> insert($imageData);
                }

                return redirect('/admin/goods');
            }else{
                return back() -> with(['error'=>'Add data error']);
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
        $field = DB::table('goods') -> where('id', $id) -> first();

        if(!$field){
            return redirect('/admin/goods');
        }

        $categoryModel = new Category();
        $categoryData = $categoryModel -> getTree();

        $brandData = DB::table('brands') -> get();

        $goodsCategory = DB::table('goods_categories') -> where('goods_id', $id) -> orderBy('id', 'ASC') -> get();

        $express = DB::table('expresses') -> select(['id', 'province_name']) -> orderBy('id', 'ASC') -> get();

        $goodImg = DB::table('goods_images') -> where('goods_id', $id) -> get();

        return view('admin.goods.edit', compact('field','categoryData','brandData','goodsCategory','express','goodImg'));
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
        $input = Input::except('_token', '_method', 'goods_images', 'goods_category');

        if(array_key_exists('file', $input)){
            unset($input['file']);
        }

        $page = $input['page'];
        unset($input['page']);

        $fiveday = $input['o_fiveday'];
        unset($input['o_fiveday']);
        $category_id = $input['o_category_id'];
        unset($input['o_category_id']);
        $brand_id = $input['o_brand_id'];
        unset($input['o_brand_id']);

        if($input['limit_num'] == ''){
            $input['limit_num'] = 1;
        }
        if($input['sale'] == ''){
            $input['sale'] = 0;
        }
        if($input['sort'] == ''){
            $input['sort'] = 0;
        }

        $rules = [
            'name' => 'required|max:50',
            'image' => 'required',
            'price' => 'required',
            'org_price' => 'required',
            'weight' => 'required',
            'stock' => 'required',
        ];

        $msg = [
            'name.required' => 'Please input goods name',
            'name.max' => 'Goods name cannot be more than 50 characters',
            'image.required' => 'Please upload image',
            'price.required' => 'Please input price',
            'org_price.required' => 'Please input original price',
            'weight.required' => 'Please input weight',
            'stock.required' => 'Please input stock',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $result = DB::table('goods') -> where('id', $id) -> update($input);

            if($result !== false){
                $goodsCategories = Input::only('goods_category');
                DB::table('goods_categories') -> where('goods_id', $id) -> delete();

                if($goodsCategories['goods_category']){
                    $categoryData = array();

                    foreach($goodsCategories['goods_category'] as $k=>$v){
                        $categoryData[$k]['goods_id'] = $id;
                        $categoryData[$k]['category_id'] = $v;
                    }

                    DB::table('goods_categories') -> insert($categoryData);
                }

                $goodsImages = Input::only('goods_images');
                DB::table('goods_images') -> where('goods_id', $id) -> delete();

                if($goodsImages['goods_images']){
                    $imageData = array();

                    foreach($goodsImages['goods_images'] as $k=>$v){
                        $_imageArr = explode('@', $v);

                        $imageData[$k]['goods_id'] = $id;
                        $imageData[$k]['img'] = $_imageArr[2];
                        $imageData[$k]['big_img'] = $_imageArr[1];
                        $imageData[$k]['sm_img'] = $_imageArr[0];
                    }

                    DB::table('goods_images') -> insert($imageData);
                }

                return redirect('/admin/goods?'.'category_id='.$category_id.'&brand_id='.$brand_id.'&page='.$page.'&fiveday='.$fiveday);
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
        $field = DB::table('goods') -> select(['video', 'poster', 'image']) -> where('id', $id) -> first();

        $result = DB::table('goods') -> where('id', $id) -> delete();

        if($result !== false){
            DB::table('carts') -> where('goods_id', $id) -> delete();
            DB::table('favors') -> where('goods_id', $id) -> delete();

            //删除图片和视频
            @unlink(public_path().'/'.$field->video);
            @unlink(public_path().'/'.$field->poster);
            @unlink(public_path().'/'.$field->image);

            $goodsImage = DB::table('goods_images') -> where('goods_id', $id) -> get();
            foreach($goodsImage as $v){
                @unlink(public_path().'/'.$v->img);
                @unlink(public_path().'/'.$v->big_img);
                @unlink(public_path().'/'.$v->sm_img);
            }

            DB::table('goods_categories') -> where('goods_id', $id) -> delete();

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
