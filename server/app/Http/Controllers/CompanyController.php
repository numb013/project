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
use App\Services\CompanyService;

class CompanyController extends Controller
{
    private $companyService;

    public function __construct(
        CompanyService $companyService
    ) {
        // $this->middleware('auth');
        // $this->middleware('auth:cast_admin');
        $this->companyService = $companyService;
    }
    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * 管理者
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */
    //事務所作成
    public function adminCreate(Request $request)
    {
        $company = Company::select('id', 'name')->where('id', '>', 0)->get()->toArray();
        Log::debug($company);
        return view('/admin/company/create', compact('company'));
    }

    //事務所作成確認
    public function adminConfirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|max:100',
            'password' => 'required',
        ])->validate();

        $input_data = $request->all();
        return view('/admin/company/confirm', compact('input_data'));
    }

    //事務所作成完了
    public function adminComplete(Request $request)
    {
        $insert_data = $this->companyService->arrOnly($request->all());
        $insert_data['password'] = bcrypt($insert_data['password']);
        $insert_data['category'] = implode( ",", $request->genre );
        $insert_data['hash_id'] = str_random(6) . '_' . str_random(4);
        $insert_data['company_id'] = 1;

        CastAdmin::create($insert_data);
        return redirect('/admin/company/list');
    }

    //事務所一覧
    public function adminList(Request $request)
    {
        $search_param = [];
        $list = $this->companyService->castSearch($search_param);
        return view('/admin/company/list', compact('list'));
    }

    //事務所検索
    public function adminSearch(Request $request)
    {
        $search_param['name'] = $request->name;
        $search_param['category'] = $request->category;
        $search_param['address'] = $request->address;
        $search_param['tel'] = $request->tel;
        $search_param['email'] = $request->email;
        $search_param['hp_url'] = $request->hp_url;
        $search_param['contact_name'] = $request->contact_name;
        $search_param['contact_tel'] = $request->contact_tel;
        $search_param['contact_mail'] = $request->contact_mail;
        $search_param['company_address'] = $request->company_address;
        $search_param['company_tel'] = $request->company_tel;
        $search_param['accouont_type'] = $request->accouont_type;
        $search_param['transfer_name'] = $request->transfer_name;


        return view('/admin/company/list', compact('list'));
    }

    //事務所詳細
    public function adminDetail(Request $request)
    {
        $cast_id = $request->input('id');
        $detail = CastAdmin::select('*')->where('id', $cast_id)->first()->toArray();
        log::debug("aaaaaa");
        log::debug($detail);
        return view('/admin/company/detail', compact('detail'));
    }

    //事務所編集
    public function adminEdit(Request $request)
    {
        $cast_id = $request->input('id');
        $detail = CastAdmin::select('*')->where('id', $cast_id)->first();
        $company = Company::select('id', 'name')->where('id', '>', 0)->get()->toArray();

        return view('/admin/company/edit', compact('detail', 'company'));
    }

    //事務所アップデート
    public function adminUpdate(Request $request)
    {
        $cast_id = $request->id;
        $update_data = $this->companyService->arrOnly($request->all());
        $insert_data['password'] = bcrypt($insert_data['password']);
        $insert_data['category'] = implode( ",", $request->genre );
        CastAdmin::where('user_id', $cast_id)->update($update_data);
        return view('/admin/company/detail?id='.$cast_id);
    }





    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * 事務所管理
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
