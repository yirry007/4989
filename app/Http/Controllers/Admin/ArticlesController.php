<?php

namespace App\Http\Controllers\Admin;

use App\Tool\Page\Page;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ArticlesController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perpage = 20;

        $dataCount = DB::table('articles') -> count();
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();

        $articlesData = DB::table('articles') -> offset($page->getOffset()) -> orderBy('id','DESC') -> limit($perpage) -> get();

        return view('admin.articles.index', compact('articlesData', 'dataCount', 'pageShow'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::only('title','author','sharing','following','msg','content','hit');

        $rules = [
            'title' => 'required',
        ];

        $msg = [
            'title.required' => 'Please input title',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $input['addtime'] = time();

            $result = DB::table('articles') -> insert($input);

            if($result){
                return redirect('/admin/articles');
            }else{
                return back() -> with(['error'=>'Add Article Failed']);
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
        $field = DB::table('articles') -> where('id', $id) -> first();

        if(!$field){
            return redirect('/admin/articles');
        }

        return view('admin.articles.edit', compact('field'));
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
            'title' => 'required',
        ];

        $msg = [
            'title.required' => 'Please input title',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $input['addtime'] = time();

            $result = DB::table('articles') -> where('id', $id) -> update($input);

            if($result !== false){
                return redirect('/admin/articles?page='.$page);
            }else{
                return back() -> with(['error'=>'Update Article Failed']);
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
        $result = DB::table('articles') -> where('id', $id) -> delete();

        if($result !== false){
            $data = [
                'status' => 0,
                'msg' => 'Delete Article Success',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => 'Delete Article Failed',
            ];
        }

        return $data;
    }
}
