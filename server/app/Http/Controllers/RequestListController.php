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
use App\Services\NoticeService;
use App\User;
use App\ManageRequestMessage;

class RequestListController extends Controller
{

    private $requestListService;
    private $videoService;
    private $noticeService;

    public function __construct(
        RequestListService $requestListService,
        VideoService $videoService,
        NoticeService $noticeService
    ) {
        // $this->middleware('auth');
        // $this->middleware('auth:cast_admin');
        $this->requestListService = $requestListService;
        $this->videoService = $videoService;
        $this->noticeService = $noticeService;
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

    //管理者のリクエスト検索
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

    //管理者のリクエスト詳細
    public function adminDetail(Request $request)
    {
        $request_list_id = $request->input('id');
        $detail = $this->requestListService->requestDetail($request_list_id);
        return view('/admin/request_list/detail', compact('detail'));
    }

    //管理者のリクエスト編集画面
    public function adminEdit(Request $request)
    {
        $request_list_id = $request->input('id');
        $detail = $this->requestListService->requestDetail($request_list_id);
        return view('/admin/request_list/edit', compact('detail'));
    }

    //管理者のリクエストチェックメッセージ返信
    public function adminManageRequestMessage(Request $request)
    {
        $admin = Auth::user();
        $request_list_id = $request->id;
        $request_check_message_param = [
            'request_list_id' => $request_list_id,
            'admin_id' => $admin->id,
            'confirmed' => 0,
            'message' => $request->check_message,
        ];
        ManageRequestMessage::create($request_check_message_param);

        $detail = $this->requestListService->requestDetail($request_list_id);
        return view('/cast_admin/request_list/detail', compact('detail'));
    }

    //管理者のリクエスト更新
    public function adminUpdate(Request $request)
    {
        $admin = Auth::user();
        $request_list_id = $request->id;
        $update_data =$this->requestListService->arrOnly($request->all());
        RequestList::where('id', $request_list_id)->update($update_data);

        //再提出の場合はメッセージ必須
        if ($request->state == 2) {
            if (empty($request->check_message)) {

            }
        }

        $request_list = RequestList::find($request_list_id);
        if (!empty($request->video_id)) {
            $video_param = [
                'video_id' => $request->video_id,
                'state' => $request->state,
            ];
            $this->videoService->videoUpdate($video_param);

            $notice_param = [
                'user_id' => $request_list->user_id,
                'cast_id' => $request_list->cast_id,
                'state' => $request->state,
                'type' => 1,
            ];
            $this->noticeService->addNotice($notice_param);

            $request_check_message_param = [
                'request_list_id' => $request_list_id,
                'admin_id' => $admin->id,
                'confirmed' => 0,
                'message' => $request->check_memo,
            ];
            ManageRequestMessage::create($request_check_message_param);
        }

        $detail = $this->requestListService->requestDetail($request_list_id);
        return view('/admin/request_list/detail', compact('detail'));
    }





    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * キャスト管理
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    //キャストのリクエスト一覧
    public function castAdminList(Request $request)
    {
        $user = Auth::user();

        $search_param['cast_id'] = $user->id;
        $list = $this->requestListService->requestSearch($search_param);
        return view('/cast_admin/request_list/list', compact('list'));
    }

    //キャストのリクエスト詳細
    public function castAdminDetail(Request $request)
    {
        $request_list_id = $request->input('id');
        $detail = $this->requestListService->requestDetail($request_list_id);
        return view('/cast_admin/request_list/detail', compact('detail'));
    }

    //キャストのリクエスト編集
    public function castAdminEdit(Request $request)
    {
        $request_list_id = $request->input('id');
        $detail = $this->requestListService->requestDetail($request_list_id);
        return view('/cast_admin/request_list/detail', compact('detail'));
    }


    //キャストのリクエストチェックメッセージ返信
    public function castAdminManageRequestMessage(Request $request)
    {
        $cast = Auth::user();
        $request_list_id = $request->id;
        $request_check_message_param = [
            'request_list_id' => $request_list_id,
            'cast_id' => $cast->id,
            'confirmed' => 0,
            'message' => $request->check_message,
        ];
        ManageRequestMessage::create($request_check_message_param);

        $detail = $this->requestListService->requestDetail($request_list_id);
        return view('/cast_admin/request_list/detail', compact('detail'));
    }

    //キャストの動画アップロード
    public function castAdminVideoUpload(Request $request)
    {
        $user = Auth::user();
        $request_list_id = $request->request_list_id;
        $video_id = $this->videoService->videoUpload($request, $user->hash_id);

        RequestList::where('id', $request_list_id)->update([
            'status' => 1,
            'video_id' => $video_id,
        ]);

        //リクエストの動画がアップされたら管理者にお知らせ
        $notice_param = [
            'user_id' => $request_list->user_id,
            'cast_id' => $request_list->cast_id,
            'state' => $request->state,
            'type' => 2,
        ];
        $this->noticeService->addNotice($notice_data);

        return redirect('/cast_admin/request_list/detail/?id=' . $request_list_id);
    }
}