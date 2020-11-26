<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use DB;
use Log;
use App\RequestList;
use App\User;

class RequestListController extends Controller
{


    public function __construct() {
        // $this->middleware('auth');
        // $this->middleware('auth:cast_admin');  //変更
    }

    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * 管理者
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function adminList(Request $request)
    {
        Log::debug("bbbbbb");
        $search_param = [];
        $list = $this->requestSearch($search_param);
        return view('/admin/request_list/list', compact('list'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminSearch(Request $request)
    {
        $search_param['sort_type'] = $request->sort_type;
        $search_param['free_word'] = $request->free_word;
        $search_param['viewer_id'] = $request->viewer_id;
        $search_param['category'] = $request->category;
        $search_param['cast_id'] = $request->cast_id;
        $search_param['to_name'] = $request->to_name;
        $search_param['message'] = $request->message;

        $list = $this->requestSearch($search_param);

        return view('/admin/request_list/list', compact('list'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminDetail(Request $request)
    {
        $request_list_id = $request->input('id');
        $detail = RequestList::select('*')->where('id', $request_list_id)->first();
        return view('/admin/request_list/detail', compact('detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminEdit(Request $request)
    {
        $request_list_id = $request->input('id');

        $column = 'request_lists.*';
        $column .= ', viewers.name as viewer_name, cast_admins.name as cast_name';
        $query = RequestList::select(DB::raw($column));
        $query->join('viewers', 'viewers.id', '=', 'request_lists.viewer_id');
        $query->join('cast_admins', 'cast_admins.id', '=', 'request_lists.cast_id');
        $query->where('request_lists.id', $request_list_id);
        $detail = $query->first();

        return view('/admin/request_list/edit', compact('detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminUpdate(Request $request)
    {
        $request_list_id = $request->id;
        $update_data = Arr::only($request->all(), [
            'viewer_id',
            'cast_id',
            'status',
            'category',
            'to_name',
            'message',
        ]);
        RequestList::where('id', $request_list_id)->update($update_data);

        $search_param = [];
        $list = $this->requestSearch($search_param);
        return view('/admin/request_list/list', compact('list'));
    }


    public function requestSearch($search_param)
    {
        $column = 'request_lists.*';
        $column .= ', request_movie.hash_id as request_movie_hash_id, request_movie.status as request_movie_status, check_status, check_memo';
        $column .= ', viewers.name as viewer_name, cast_admins.name as cast_name';
        if (!empty($search_param['free_word'])) {
            $column.= ', CASE WHEN request_lists.message like "%' . $search_param['free_word'] . '%" THEN 1 ELSE 0 END as name_hit';
        }
        $query = RequestList::select(DB::raw($column));
        $query->join('viewers', 'viewers.id', '=', 'request_lists.viewer_id');
        $query->join('cast_admins', 'cast_admins.id', '=', 'request_lists.cast_id');
        $query->leftjoin('request_movie', 'request_movie.request_id', '=', 'request_lists.id');

        if (!empty($search_param['free_word'])) {
            $query->where('request_lists.message', 'like BINARY', "%".$search_param['message']."%");
        }
        if (!empty($search_param['cast_id'])) {
            $query->whereIn('request_lists.cast_id', $search_param['cast_id']);
        }
        if (!empty($search_param['status'])) {
            $query->whereIn('request_lists.status', $search_param['status']);
        }
        if (!empty($search_param['category'])) {
            $query->where('request_lists.category', $search_param['category']);
        }

        if (!empty($search_param['free_word'])) {
            $query->orderBy('name_hit', 'desc');
        }

        $request_list = $query->get();
        if ($request_list) {
            $request_list = $request_list->toArray();
        }


Log::debug("ssssssssssssssss");
Log::debug($request_list);


        return $request_list;
    }














    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * キャスト管理
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castAdminDetail(Request $request)
    {
        $request_id = $request->input('id');
        $detail = RequestList::select('*')->where('id', $request_id)->first();
        return view('/cast_admin/request_list/detail', compact('detail'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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