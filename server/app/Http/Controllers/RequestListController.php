<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $column = '*';
        if (!empty($request->input('name'))) {
            $column.= ', CASE WHEN casts.name like "%' . $request->input('name') . '%" THEN 1 ELSE 0 END as name_hit';
        }
        $query = RequestList::select(DB::raw($column));
        $query->join('user', 'users.id', '=', 'casts.user_id');
        if ($request->input('name')) {
            $query->where('cast.name', 'like BINARY', "%$request->input('name')%");
        }
        if ($request->input('category')) {
            $query->whereIn('cast.category', $request->input('category'));
        }
        if (!empty($request->input('name'))) {
            $query->orderBy('name_hit', 'desc');
        }
        $query->orderBy('score', 'desc');
        $list = $query->get();
        if ($list) {
            $list = $list->toArray();
        }
        return view('/admin/request_list/detail', compact('list'));
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
        $detail = RequestList::select('*')->where('id', $request_list_id)->first();
        return view('/admin/request_list/detail', compact('detail'));
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
            'user_id',
            'company_id',
            'name',
            'category',
            'can_type',
            'period',
            'descript',
            'total_post',
            'score',
        ]);
        RequestList::where('user_id', $request_list_id)->update($update_data);
        return view('/admin/request_list/detail', compact('cast_detail'));
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
        $cast_id = $request->input('id');
        $list = RequestList::select('*')->where('cast_id', $cast_id)->get();
        if ($list) {
            $list = $list->toArray();
        }
        return view('/cast_admin/request_list/list');
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
        return view('/cast_admin/request_list/detail', compact('cast_detail'));
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