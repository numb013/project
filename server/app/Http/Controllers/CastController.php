<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Auth;
use App\User;
use App\Notice;
use App\CastAdmin;
use App\Company;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CastService;



class CastController extends Controller
{

    private $castService;

    public function __construct(
        CastService $castService
    ) {
        // $this->middleware('auth');
        // $this->middleware('auth:cast_admin');
        $this->castService = $castService;
    }

    public function index()
    {
        return view('/admin/cast/index');
    }

    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * 管理者
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */
    //キャスト作成
    public function adminCreate(Request $request)
    {
        $company = Company::select('id', 'name')->where('id', '>', 0)->get()->toArray();
        Log::debug($company);
        return view('/admin/cast/create', compact('company'));
    }

    //キャスト作成確認
    public function adminConfirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|max:100',
            'password' => 'required',
        ])->validate();

        $input_data = $request->all();
        return view('/admin/cast/confirm', compact('input_data'));
    }

    //キャスト作成完了
    public function adminComplete(Request $request)
    {
        $insert_data = $this->castService->arrOnly($request->all());
        $insert_data['password'] = bcrypt($insert_data['password']);
        $insert_data['category'] = implode( ",", $request->genre );

        CastAdmin::create($insert_data);
        return redirect('/admin/cast/list');
    }

    //キャスト一覧
    public function adminList(Request $request)
    {
        $search_param = [];
        $list = $this->castService->castSearch($search_param);
        return view('/admin/cast/list', compact('list'));
    }

    //キャスト検索
    public function adminSearch(Request $request)
    {
        $search_param['sort_type'] = $request->sort_type;
        $search_param['free_word'] = $request->free_word;
        $search_param['company_id'] = $request->company_id;
        $search_param['category'] = $request->category;
        $search_param['min_price'] = $request->min_price;
        $search_param['max_price'] = $request->max_price;
        $search_param['period'] = $request->period;
        $search_param['min_total_post'] = $request->min_total_post;
        $search_param['max_total_post'] = $request->max_total_post;
        $search_param['min_get_coin'] = $request->min_get_coin;
        $search_param['max_get_coin'] = $request->max_get_coin;
        $list = $this->castService->castSearch($search_param);
        return view('/admin/cast/list', compact('list'));
    }

    //キャスト詳細
    public function adminDetail(Request $request)
    {
        $cast_id = $request->input('id');
        $cast_detail = CastAdmin::select('*')->where('id', $cast_id)->first();
        return view('/admin/cast/detail', compact('cast_detail'));
    }

    //キャスト編集
    public function adminEdit(Request $request)
    {
        $cast_id = $request->input('id');
        $detail = CastAdmin::select('*')->where('id', $cast_id)->first();
        $company = Company::select('id', 'name')->where('id', '>', 0)->get()->toArray();

        return view('/admin/cast/edit', compact('detail', 'company'));
    }

    //キャストアップデート
    public function adminUpdate(Request $request)
    {
        $cast_id = $request->id;
        $update_data = $this->castService->arrOnly($request->all());
        $insert_data['password'] = bcrypt($insert_data['password']);
        $insert_data['category'] = implode( ",", $request->genre );
        CastAdmin::where('user_id', $cast_id)->update($update_data);
        return view('/admin/cast/detail?id='.$cast_id);
    }





    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * キャスト管理
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    public function castAdminCreate(Request $request)
    {
        Log::debug("sssssssssssssssssssssss");
        Log::debug($request);
        return view('/cast_admin/cast/detail');
    }

    public function castAdminEdit()
    {
        return view('/cast_admin/cast/edit');
    }

    public function castAdminDetail()
    {
        return view('/cast_admin/cast/detail');
    }
}
