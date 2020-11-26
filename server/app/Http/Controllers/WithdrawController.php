<?php

namespace App\Http\Controllers;

use App\CoinHistory;
use App\FavoriteUser;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminList()
    {
        $search_param = [];
        $list = $this->withdrawSearch($search_param);
        return view('/admin/withdraw/list', compact('list'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminSearch()
    {
        $search_param = [];
        $list = $this->withdrawSearch($search_param);
        return view('/admin/withdraw/list', compact('list'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminDetail()
    {
        //
    }

    public function withdrawSearch($search_param)
    {
        $column = '*';
        if (!empty($search_param['free_word'])) {
            $column.= ', CASE WHEN cast_admins.name like "%' . $search_param['free_word']. '%" THEN 1 ELSE 0 END as name_hit';
        }
        $query = CoinHistory::select(DB::raw($column));
        $query->join('users', 'users.id', '=', 'cast_admins.user_id');

        if (!empty($search_param['free_word'])) {
            $query->where('cast_admins.name', 'like BINARY', "%".$search_param['free_word']."%");
        }
        if (!empty($search_param['category'])) {
            $query->whereIn('cast_admins.category', $search_param['category']);
        }
        if (!empty($search_param['status'])) {
            $query->whereIn('cast_admins.status', $search_param['status']);
        }
        if (!empty($search_param['company_id'])) {
            $query->where('cast_admins.company_id', $search_param['company_id']);
        }

        if (!empty($search_param["min_price"]) && empty($search_param["max_price"])) {
            $query->where('cast_admins.price', '<=', $search_param["min_price"]);
        }
        if (empty($search_param["min_price"]) && !empty($search_param["max_price"])) {
            $query->where('cast_admins.price', '>=', $search_param["max_price"]);
        }
        if (!empty($search_param["min_price"]) && !empty($search_param["max_price"])) {
            $query->whereBetween('cast_admins.price', [$search_param["min_price"], $search_param["max_price"]]);
        }


        if (!empty($search_param["min_total_post"]) && empty($search_param["max_total_post"])) {
            $query->where('cast_admins.total_post', '<=', $search_param["min_total_post"]);
        }
        if (empty($search_param["min_total_post"]) && !empty($search_param["max_total_post"])) {
            $query->where('cast_admins.total_post', '>=', $search_param["max_total_post"]);
        }
        if (!empty($search_param["min_total_post"]) && !empty($search_param["max_total_post"])) {
            $query->whereBetween('cast_admins.total_post', [$search_param["min_total_post"], $search_param["max_total_post"]]);
        }


        if (!empty($search_param["min_get_coin"]) && empty($search_param["max_get_coin"])) {
            $query->where('cast_admins.get_coin', '<=', $search_param["min_get_coin"]);
        }
        if (empty($search_param["min_get_coin"]) && !empty($search_param["max_get_coin"])) {
            $query->where('cast_admins.get_coin', '>=', $search_param["max_get_coin"]);
        }
        if (!empty($search_param["min_get_coin"]) && !empty($search_param["max_get_coin"])) {
            $query->whereBetween('cast_admins.get_coin', [$search_param["min_get_coin"], $search_param["max_get_coin"]]);
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


}
