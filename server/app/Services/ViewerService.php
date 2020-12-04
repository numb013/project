<?php

namespace App\Services;
use App\User;
use DB;
use Illuminate\Support\Arr;
use Vinkla\Hashids\Facades\Hashids;

class ViewerService
{

    protected $user;

    public function __construct(
        User $user
    ) {
        $this->user = $user;
    }

    public function viewerCount($param)
    {
        $viewer_list = User::get();
        return $viewer_list->count();
    }

    public function arrOnly($request)
    {
        $request = Arr::only($request, [
            'hash_id',
            'name',
            'status',
            'email',
            'password',
            'barthbay',
            'sex',
            'coin',
        ]);
        return $request;
    }

    public function viewerSearch($search_param)
    {
        $column = 'users.id, hash_id,name,status,email,password,barthbay,sex,coin';
        if (!empty($search_param['free_word'])) {
            $column.= ', CASE WHEN users.name like "%' . $search_param['free_word']. '%" THEN 1 ELSE 0 END as name_hit';
        }
        $query = $this->user->select(DB::raw($column));
        if (!empty($search_param['freeword'])) {
            $freeword = $search_param['freeword'];
            $query->where(function ($query) use ($freeword) {
                if (!empty($freeword)) {
                   $word = $this->double_explode(" ", "　", $freeword);
                    for ($i=0; $i < count($word); $i++) {
                        if ($i == 0) {
                            $search_word = str_replace(array(" ", "　"), "", $word[$i]);
                            $query->where('users.name', 'like BINARY', "%$search_word%");
                        } else {
                            $search_word = str_replace(array(" ", "　"), "", $word[$i]);
                            $query->orwhere('users.name', 'like BINARY', "%$search_word%");
                        }
                    }
                }
            });
        }

        if (!empty($search_param['status'])) {
            $query->whereIn('users.status', $search_param['status']);
        }
        if (!empty($search_param['email'])) {
            $query->whereIn('users.email', $search_param['email']);
        }
        if (!empty($search_param['barthbay'])) {
            $query->whereIn('users.barthbay', $search_param['barthbay']);
        }
        if (!empty($search_param['sex'])) {
            $query->whereIn('users.sex', $search_param['sex']);
        }
        if (!empty($search_param['coin'])) {
            $query->whereIn('users.coin', $search_param['coin']);
        }

        if (!empty($search_param['free_word'])) {
            $query->orderBy('name_hit', 'desc');
        }
        if (!empty($search_param['sort_column']) && !empty($search_param['sort_order'])) {
            $query->orderBy('users.'. $search_param['sort_column'] , $search_param['sort_order']);
        }

        if (!empty($search_param['limit'])) {
            $query->limit($search_param['limit']);
            if ($page_no != 1) {
                $page_no = $page_no - 1;
                $offset = ($search_param['limit'] * $page_no);
                $query->offset($offset);
            }
        }
        $query->orderBy('created_at', 'desc');
        $list = $query->get();
        if ($list) {
            $list = $list->toArray();
        }
        return $list;
    }

    // キャストの詳細画面の情報の更新
    public function update(array $params = []): void
    {
        DB::transaction(function () use ($params) {
            $this->castAdmin->find($params['cast_id'])
                ->fill($params)
                ->save();

            if (isset($params['user_state'])) {
                $params['state'] = $params['user_state'];
                unset($params['user_state']);
            }

            $this->castAdmin->find($params['cast_id'])
                ->fill($params)
                ->save();
        });
    }

}
