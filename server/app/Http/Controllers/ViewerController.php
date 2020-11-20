<?php

namespace App\Http\Controllers;

use App\Viewer;
use Illuminate\Http\Request;

class ViewerController extends Controller
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|max:100',
            'password' => 'required',
        ]);

        // バリデーションエラーだった場合
        if ($validator->fails()) {
            return view('/admin/viewer/create');
        }
        $insert_data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password);
        ];
        Viewer::create($insert_data);
        return view('/admin/viewer/detail');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminList(Request $request)
    {
        $column = '*';
        if (!empty($request->input('name'))) {
            $column.= ', CASE WHEN viewers.name like "%' . $request->input('name') . '%" THEN 1 ELSE 0 END as name_hit';
        }
        $query = Viewer::select(DB::raw($column));
        $query->join('user', 'users.id', '=', 'viewers.user_id');
        if ($request->input('name')) {
            $query->where('viewers.name', 'like BINARY', "%$request->input('name')%");
        }
        if (!empty($request->input('name'))) {
            $query->orderBy('name_hit', 'desc');
        }
        $query->orderBy('score', 'desc');
        $viewer_list = $query->get();
        if ($viewer_list) {
            $viewer_list = $viewer_list->toArray();
        }
        return view('/admin/viewer/detail', compact('viewer_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminDetail(Request $request)
    {
        $viewer_id = $request->input('id');
        $viewer_detail = Viewer::select('*')->where('id', $viewer_id)->first();
        return view('/admin/viewer/detail', compact('viewer_detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminEdit(Request $request)
    {
        $viewer_id = $request->input('id');
        $viewer_detail = Viewer::select('*')->where('id', $viewer_id)->first();
        return view('/admin/viewer/detail', compact('viewer_detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminUpdate(Request $request)
    {
        $viewer_id = $request->id;
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
        Viewer::where('user_id', $viewer_id)->update($update_data);
        return view('/admin/viewer/detail');
    }




    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * キャスト管理
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminDetail(Request $request)
    {
        $viewer_id = $request->input('id');
        $viewer_detail = Viewer::select('*')->where('id', $viewer_id)->first();
        return view('/cast_admin/viewer/detail', compact('viewer_detail'));
    }


}
