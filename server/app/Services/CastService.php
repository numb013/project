<?php

namespace App\Services;
use App\CastAdmin;
use DB;
use Illuminate\Support\Arr;
use Vinkla\Hashids\Facades\Hashids;

class CastService
{
    public function __construct(
    ) {
    }

    public function arrOnly($request)
    {
        $request = Arr::only($request, [
            'name', 
            'authority',
            'company_id',
            'category',
            'can_type',
            'price',
            'period',
            'descript',
            'total_post',
            'get_coin',
            'get_coin',
            'score',
            'email', 
            'password',
        ]);
        return $request;
    }

    public function castSearch($search_param)
    {
        $column = 'cast_admins.*, ';
        $column .= 'companies.*';
        if (!empty($search_param['free_word'])) {
            $column.= ', CASE WHEN cast_admins.name like "%' . $search_param['free_word']. '%" THEN 1 ELSE 0 END as name_hit';
        }
        $query = CastAdmin::select(DB::raw($column));
        $query->leftJoin('companies', 'companies.id', '=', 'cast_admins.id');
        if (!empty($search_param['freeword'])) {
            $freeword = $search_param['freeword'];
            $query->where(function ($query) use ($freeword) {
                if (!empty($freeword)) {
                   $word = $this->double_explode(" ", "　", $freeword);
                    for ($i=0; $i < count($word); $i++) {
                        if ($i == 0) {
                            $search_word = str_replace(array(" ", "　"), "", $word[$i]);
                            $query->where('cast_admins.name', 'like BINARY', "%$search_word%");
                        } else {
                            $search_word = str_replace(array(" ", "　"), "", $word[$i]);
                            $query->orwhere('cast_admins.name', 'like BINARY', "%$search_word%");
                        }
                        $search_word = str_replace(array(" ", "　"), "", $word[$i]);
                        $query->orwhere('companies.name', 'like BINARY', "%$search_word%");
                    }
                }
            });
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
