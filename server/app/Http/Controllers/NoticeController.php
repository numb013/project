<?php

namespace App\Http\Controllers;

use App\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{

    // const USER_CHECK = '1';
    // const SERVER_CHECK = '9';
    // private $girl;

    public function __construct(){}

 
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
    public function adminCreate(Request $request)
    {
        return view('/admin/notice/create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminConfirm(Request $request)
    {
        $input_data = $request->all();
        return view('/admin/notice/confirm', compact($input_data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminComplete(Request $request)
    {
        Notice::create($request->all());
        return view('/admin/notice/list');
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminList(Request $request)
    {
        $column = '*';
        if (!empty($request->input('title'))) {
            $column.= ', CASE WHEN notice.title like "%' . $request->input('title') . '%" THEN 1 ELSE 0 END as name_hit';
        }
        $query = Notice::select(DB::raw($column));
        $query->join('user', 'users.id', '=', 'notice.user_id');
        if ($request->input('title')) {
            $query->where('notice.name', 'like BINARY', "%$request->input('title')%");
        }
        if (!empty($request->input('title'))) {
            $query->orderBy('name_hit', 'desc');
        }
        $query->orderBy('created_at', 'desc');
        $list = $query->get();
        if ($list) {
            $list = $list->toArray();
        }
        return view('/admin/notice/detail', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminDetail(Request $request)
    {
        $notice_id = $request->input('id');
        $detail = Notice::select('*')->where('id', $notice_id)->first();
        return view('/admin/notice/detail', compact('detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminEdit(Request $request)
    {
        $notice_id = $request->input('id');
        $detail = Notice::select('*')->where('id', $notice_id)->first();
        return view('/admin/notice/detail', compact('detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminUpdate(Request $request)
    {
        $notice_id = $request->id;
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
        Notice::where('id', $notice_id)->update($update_data);
        return view('/admin/notice/detail');
    }





    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * キャスト管理
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */



}
