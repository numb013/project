<?php

namespace App\Http\Controllers;

use App\Cast;
use App\User;
use App\Notice;
use App\CastAdmin;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class CastController extends Controller
{

    // const USER_CHECK = '1';
    // const SERVER_CHECK = '9';
    // private $girl;

    public function __construct(){}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('/admin/cast/index');
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
    public function adminCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|max:100',
            'password' => 'required',
        ]);

        // バリデーションエラーだった場合
        if ($validator->fails()) {
            return view('/admin/cast/create');
        }
        $insert_data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password);
        ];
        CastAdmin::create($insert_data);
        return view('/admin/cast/detail');
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
            $column.= ', CASE WHEN casts.name like "%' . $request->input('name') . '%" THEN 1 ELSE 0 END as name_hit';
        }
        $query = Cast::select(DB::raw($column));
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
        $cast_list = $query->get();
        if ($cast_list) {
            $cast_list = $cast_list->toArray();
        }
        return view('/admin/cast/detail', compact('cast_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminDetail(Request $request)
    {
        $cast_id = $request->input('id');
        $cast_detail = Cast::select('*')->where('id', $cast_id)->first();
        return view('/admin/cast/detail', compact('cast_detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminEdit(Request $request)
    {
        $cast_id = $request->input('id');
        $cast_detail = Cast::select('*')->where('id', $cast_id)->first();
        return view('/admin/cast/detail', compact('cast_detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminUpdate(Request $request)
    {
        $cast_id = $request->id;
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
        Cast::where('user_id', $cast_id)->update($update_data);
        return view('/admin/cast/detail', compact('cast_detail'));
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
    public function castAdminCreate()
    {
        return view('/cast_admin/cast/detail');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castAdminEdit()
    {
        return view('/cast_admin/cast/detail');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castAdminDetail()
    {
        return view('/cast_admin/cast/detail');
    }
}
