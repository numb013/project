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
use App\Services\VideoService;
use App\User;

class RequestListController extends Controller
{

    private $requestListService;
    private $videoService;

    public function __construct(
        RequestListService $requestListService,
        VideoService $videoService
    ) {
        // $this->middleware('auth');
        // $this->middleware('auth:cast_admin');
        $this->requestListService = $requestListService;
        $this->videoService = $videoService;
    }

    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * 管理者
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    //リクエスト作成
    public function adminCreate(Request $request)
    {
        return view('/admin/request_list/create');
    }


    //リクエスト作成完了
    public function adminComplete(Request $request)
    {
        $insert_data = $this->requestListService->arrOnly($request->all());
        $insert_data['category'] = implode( ",", $request->genre );
        $insert_data['category1'] = implode( ",", $request->genre );

        RequestList::create($insert_data);
        return redirect('/admin/request_list/list');
    }

    public function adminList(Request $request)
    {
        $search_param = [];
        $list = $this->requestListService->requestSearch($search_param);
        return view('/admin/request_list/list', compact('list'));
    }

    public function adminSearch(Request $request)
    {
        $search_param['sort_type'] = $request->sort_type;
        $search_param['free_word'] = $request->free_word;
        $search_param['user_id'] = $request->user_id;
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

        $search_param['cast_id'] = $user->id;
        $list = $this->requestListService->requestSearch($search_param);
        Log::debug("dddddddddddddddddd");
        Log::debug($list);
        return view('/cast_admin/request_list/list', compact('list'));
    }

    public function castAdminDetail(Request $request)
    {
        $request_id = $request->input('id');
        $detail = RequestList::select('*')->where('id', $request_id)->first();
        return view('/cast_admin/request_list/detail', compact('detail'));
    }

    public function castAdminEdit(Request $request)
    {
        $request_id = $request->input('id');
        $detail = RequestList::select('*')->where('id', $request_id)->first();
        return view('/cast_admin/request_list/detail', compact('detail'));
    }


    public function castAdminVideoUpload(Request $request)
    {
        $user = Auth::user();
        $request_list_id = $request->request_list_id;
        $video_id = $this->videoService->videoUpload($request, $user->hash_id);

        RequestList::where('id', $request_list_id)->update([
            'status' => 1,
            'video_id' => $video_id,
        ]);
        return redirect('/cast_admin/request_list/detail/?id=' . $request_list_id);
    }
}