<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use DB;
use Log;
use App\RequestList;
use App\Services\RequestListService;
use App\User;

class RequestListController extends Controller
{

    private $requestListService;

    public function __construct(
        RequestListService $requestListService
    ) {
        // $this->middleware('auth');
        // $this->middleware('auth:cast_admin');
        $this->requestListService = $requestListService;
    }

    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * 管理者
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    public function adminList(Request $request)
    {
        Log::debug("bbbbbb");
        $search_param = [];
        $list = $this->requestSearch($search_param);
        return view('/admin/request_list/list', compact('list'));
    }

    public function adminSearch(Request $request)
    {
        $search_param['sort_type'] = $request->sort_type;
        $search_param['free_word'] = $request->free_word;
        $search_param['viewer_id'] = $request->viewer_id;
        $search_param['category'] = $request->category;
        $search_param['cast_id'] = $request->cast_id;
        $search_param['to_name'] = $request->to_name;
        $search_param['message'] = $request->message;

        $list = $this->requestListService->requestSearch($search_param);

        return view('/admin/request_list/list', compact('list'));
    }

    public function adminDetail(Request $request)
    {
        $request_list_id = $request->input('id');
        $detail = RequestList::select('*')->where('id', $request_list_id)->first();
        return view('/admin/request_list/detail', compact('detail'));
    }

    public function adminEdit(Request $request)
    {
        $request_list_id = $request->input('id');
        $detail = $this->requestListService->requestDetail($request_list_id);
        return view('/admin/request_list/edit', compact('detail'));
    }

    public function adminUpdate(Request $request)
    {
        $request_list_id = $request->id;
        $update_data =$this->requestListService->arrOnly($request->all());
        RequestList::where('id', $request_list_id)->update($update_data);

        $search_param = [];
        $list = $this->requestListService->requestSearch($search_param);
        return view('/admin/request_list/list', compact('list'));
    }















    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * キャスト管理
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    public function castAdminList(Request $request)
    {
        $user = Auth::user();
        // $cast_id = $request->input('id');
        $list = RequestList::select('*')->get();
        if ($list) {
            $list = $list->toArray();
        }
        return view('/cast_admin/request_list/list', compact('list'));
    }

    public function castAdminDetail(Request $request)
    {
        $request_id = $request->input('id');
        $detail = RequestList::select('*')->where('id', $request_id)->first();
        return view('/cast_admin/request_list/detail', compact('detail'));
    }

    public function castAdminMovieUpload(Request $request)
    {
        // ① フォームの入力値を取得
        $inputs = \Request::all();
     
        // ② デバッグ： $inputs の内容確認
        //    dd($inputs);
         
        // $this->validate($request, [
        //     'file' => [
        //         // 必須
        //         'required',
        //         // アップロードされたファイルであること
        //         'file',
        //         // ファイルであること
        //         'image',
        //         // MIMEタイプを指定
        //         'mimes:jpeg,png',
        //         // 最小縦横120px 最大縦横400px
        //         'dimensions:min_width=120,min_height=120,max_width=400,max_height=400',
        //     ]
        // ]);
     
        if ($request->file('file')->isValid([])) {
            $mes = $request->file('file')->getClientOriginalName();
            $filename = $request->file->storeAs(
                'public/files', $mes
            );
            $user = User::find(auth()->id());
            $user->avatar_filename = basename($filename);
            $user->save();
            return redirect('/cast_admin/home')->with('success', '保存しました。'.$mes);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['file' => 'アップロードされていないか不正なデータです。']);
        }
        return view('/cast_admin/home');

    }
}