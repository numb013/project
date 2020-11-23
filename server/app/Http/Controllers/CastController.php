<?php

namespace App\Http\Controllers;

use App\Cast;
use App\User;
use App\Notice;
use App\CastAdmin;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use DB;
use Log;

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
        return view('/admin/cast/create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminCreateComplete(Request $request)
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
            'password' => bcrypt($request->password),
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
        $search_param = [];
        $list = $this->castSearch($search_param);

Log::debug($list);

        return view('/admin/cast/list', compact('list'));
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
        $search_param['company_id'] = $request->company_id;
        $search_param['category'] = $request->category;
        $search_param['min_price'] = $request->min_price;
        $search_param['max_price'] = $request->max_price;
        $search_param['period'] = $request->period;
        $search_param['min_total_post'] = $request->min_total_post;
        $search_param['max_total_post'] = $request->max_total_post;
        $search_param['min_get_coin'] = $request->min_get_coin;
        $search_param['max_get_coin'] = $request->max_get_coin;


        $list = $this->castSearch($search_param);
        return view('/admin/cast/list', compact('list'));
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

        return view('/admin/cast/detail?id='.$cast_id);
    }



    public function castSearch($search_param)
    {
        $column = '*';
        if (!empty($search_param['free_word'])) {
            $column.= ', CASE WHEN casts.name like "%' . $search_param['free_word']. '%" THEN 1 ELSE 0 END as name_hit';
        }
        $query = Cast::select(DB::raw($column));
        $query->join('users', 'users.id', '=', 'casts.user_id');


        if (!empty($search_param['free_word'])) {
            $query->where('casts.name', 'like BINARY', "%".$search_param['free_word']."%");
        }
        if (!empty($search_param['category'])) {
            $query->whereIn('casts.category', $search_param['category']);
        }
        if (!empty($search_param['status'])) {
            $query->whereIn('casts.status', $search_param['status']);
        }
        if (!empty($search_param['company_id'])) {
            $query->where('casts.company_id', $search_param['company_id']);
        }


        if (!empty($search_param["min_price"]) && empty($search_param["max_price"])) {
            $query->where('casts.price', '<=', $search_param["min_price"]);
        }
        if (empty($search_param["min_price"]) && !empty($search_param["max_price"])) {
            $query->where('casts.price', '>=', $search_param["max_price"]);
        }
        if (!empty($search_param["min_price"]) && !empty($search_param["max_price"])) {
            $query->whereBetween('casts.price', [$search_param["min_price"], $search_param["max_price"]]);
        }


        if (!empty($search_param["min_total_post"]) && empty($search_param["max_total_post"])) {
            $query->where('casts.total_post', '<=', $search_param["min_total_post"]);
        }
        if (empty($search_param["min_total_post"]) && !empty($search_param["max_total_post"])) {
            $query->where('casts.total_post', '>=', $search_param["max_total_post"]);
        }
        if (!empty($search_param["min_total_post"]) && !empty($search_param["max_total_post"])) {
            $query->whereBetween('casts.total_post', [$search_param["min_total_post"], $search_param["max_total_post"]]);
        }


        if (!empty($search_param["min_get_coin"]) && empty($search_param["max_get_coin"])) {
            $query->where('casts.get_coin', '<=', $search_param["min_get_coin"]);
        }
        if (empty($search_param["min_get_coin"]) && !empty($search_param["max_get_coin"])) {
            $query->where('casts.get_coin', '>=', $search_param["max_get_coin"]);
        }
        if (!empty($search_param["min_get_coin"]) && !empty($search_param["max_get_coin"])) {
            $query->whereBetween('casts.get_coin', [$search_param["min_get_coin"], $search_param["max_get_coin"]]);
        }

        if (!empty($search_param['free_word'])) {
            $query->orderBy('name_hit', 'desc');
        }
        if (!empty($search_param['sort_type'])) {
            if ($search_param['sort_type'] == 1) {
                $query->orderBy('get_coin', 'desc');
            } elseif ($search_param['sort_type'] == 2) {
                $query->orderBy('total_post', 'desc');
            } elseif ($search_param['sort_type'] == 3) {
                $query->orderBy('price', 'desc');
            }
        }

        $query->orderBy('score', 'desc');
        $cast_list = $query->get();
        if ($cast_list) {
            $cast_list = $cast_list->toArray();
        }
        return $cast_list;
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
    public function castAdminCreate(Request $request)
    {
        Log::debug("sssssssssssssssssssssss");
        Log::debug($request);
        return view('/cast_admin/cast/detail');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function castAdminEdit()
    {
        return view('/cast_admin/cast/edit');
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
