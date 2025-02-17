<?php

namespace App\Http\Controllers\Admin;

use App\Tool\Page\Page;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class PartnersController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perpage = 20;//每页显示记录数------`

        $dataCount = DB::table('partners') -> count();//数据表记录计数
        $page = new Page($dataCount, $perpage);
        $pageShow = $page -> fpage();//制作分页html

        $partnersData = DB::table('partners') -> offset($page->getOffset()) -> orderBy('id','DESC') -> limit($perpage) -> get();

        return view('admin.partners.index', compact('partnersData', 'dataCount', 'pageShow'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.partners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::only('name','image', 'code', 'banner', 'url', 'rate' ,'email');

        $rules = [
            'name' => 'required|max:100',
            'code' => 'required',
        ];

        $msg = [
            'name.required' => 'Please input partner name.',
            'name.max' => 'Partner name cannot be more than 100 characters.',
            'code.required' => 'Please input partner code.',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $input['addtime'] = time();

            $result = DB::table('partners') -> insert($input);

            if($result){
                return redirect('/admin/partners');
            }else{
                return back() -> with(['error'=>'Add partners error']);
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
        $field = DB::table('partners') -> where('id', $id) -> first();

        if(!$field){
            return redirect('/admin/partners');
        }

        return view('admin.partners.edit', compact('field'));
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
            'name' => 'required|max:100',
        ];

        $msg = [
            'name.required' => 'Please input partner name.',
            'name.max' => 'Partner name cannot be more than 100 characters.',
        ];

        $validator = Validator::make($input, $rules, $msg);

        if($validator -> passes()){
            $result = DB::table('partners') -> where('id', $id) -> update($input);

            if($result !== false){
                return redirect('/admin/partners?page='.$page);
            }else{
                return back() -> with(['error'=>'Update partners error']);
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
        $field = DB::table('partners') -> select('image', 'banner') -> where('id', $id) -> first();

        $result = DB::table('partners') -> where('id', $id) -> delete();

        if($result !== false){
            @unlink(public_path().'/'.$field->image);
            @unlink(public_path().'/'.$field->banner);

            $data = [
                'status' => 0,
                'msg' => 'Delete partners successfully',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => 'Delete partners error',
            ];
        }

        return $data;
    }

    public function getPartnerCode()
    {
        $sitename = '4989xishichang';
        $now = time();
        $uniq = uniqid();

        $code = md5($sitename.$now.$uniq);

        return $code;
    }

    public function getPartner($code)
    {
        $data = array();

        $partner = DB::table('partners') -> select('banner', 'url') -> where('code', $code) -> first();

        if(!$partner){
            $data['status'] = 'error';
            $data['data'] = (object)[];
            return $data;
        }

        $partner->banner = url('public/'.$partner->banner);
        $data['status'] = 'success';
        $data['data'] = $partner;
        return $data;
    }
}
