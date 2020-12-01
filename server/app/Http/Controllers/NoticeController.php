<?php

namespace App\Http\Controllers;

use App\Notice;
use Illuminate\Http\Request;
use DB;
use Log;

class NoticeController extends Controller
{

    // const USER_CHECK = '1';
    // const SERVER_CHECK = '9';
    // private $girl;

    public function __construct(){}

    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * API
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */
    public function apiList(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $list = Notice::select('*')->where('id', $user_id)->get();
        return response()->json($list);
    }

    public function apiDetail(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $notice_id = $request->id;
        Notice::select('*')->where('id', $notice_id)->update(['confirmed' => 1]);
        $detail = Notice::select('*')->where('id', $notice_id)->first();
        return response()->json($detail);
    }


    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * 管理者
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */


    public function adminCreate(Request $request)
    {
        return view('/admin/notice/create');
    }

    public function adminConfirm(Request $request)
    {
        $input_data = $request->all();
        return view('/admin/notice/confirm', compact($input_data));
    }

    public function adminComplete(Request $request)
    {
        Notice::create($request->all());
        return view('/admin/notice/list');
    }

    public function adminList(Request $request)
    {
        $column = '*';
        if (!empty($request->input('title'))) {
            $column.= ', CASE WHEN notices.title like "%' . $request->input('title') . '%" THEN 1 ELSE 0 END as name_hit';
        }
        $query = Notice::select(DB::raw($column));
        if ($request->input('title')) {
            $query->where('notices.title', 'like BINARY', "%$request->input('title')%");
        }
        if (!empty($request->input('title'))) {
            $query->orderBy('name_hit', 'desc');
        }
        $query->orderBy('created_at', 'desc');
        $list = $query->get();
        if ($list) {
            $list = $list->toArray();
        }
        return view('/admin/notice/list', compact('list'));
    }

    public function adminSearch(Request $request)
    {
        // $search_param['sort_type'] = $request->sort_type;
        // $search_param['free_word'] = $request->free_word;
        // $search_param['company_id'] = $request->company_id;
        // $search_param['category'] = $request->category;
        // $search_param['min_price'] = $request->min_price;
        // $search_param['max_price'] = $request->max_price;
        // $search_param['period'] = $request->period;
        // $search_param['min_total_post'] = $request->min_total_post;
        // $search_param['max_total_post'] = $request->max_total_post;
        // $search_param['min_get_coin'] = $request->min_get_coin;
        // $search_param['max_get_coin'] = $request->max_get_coin;

        $search_param = [];
        $list = $this->noticeSearch($search_param);
        return view('/admin/notice/list', compact('list'));
    }

    public function adminDetail(Request $request)
    {
        $notice_id = $request->input('id');
        $detail = Notice::select('*')->where('id', $notice_id)->first();
        return view('/admin/notice/detail', compact('detail'));
    }

    public function adminEdit(Request $request)
    {
        $notice_id = $request->input('id');
        $detail = Notice::select('*')->where('id', $notice_id)->first();
        return view('/admin/notice/detail', compact('detail'));
    }

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



    public function noticeSearch($search_param)
    {
        $list = [];
        return $list;
    }




    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * キャスト管理
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */



}
