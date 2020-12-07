<?php

namespace App\Services;
use App\RequestList;
use App\ManageRequestMessage;
use DB;
use Log;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Arr;

class RequestListService
{
    public function __construct(
    ) {
    }

    public function requestCount()
    {
        $request_list = RequestList::get();
        return $request_list->count();
    }

    public function arrOnly($request)
    {
        $request = Arr::only($request, [
            'user_id',
            'cast_id',
            'status',
            'to_name',
            'request_detail',
        ]);
        return $request;
    }

    public function requestDetail($request_list_id)
    {
        $column = 'request_lists.*';
        $column .= ', users.name as viewer_name, cast_admins.name as cast_name';
        $query = RequestList::select(DB::raw($column));
        $query->join('users', 'users.id', '=', 'request_lists.user_id');
        $query->join('cast_admins', 'cast_admins.id', '=', 'request_lists.cast_id');
        $query->where('request_lists.id', $request_list_id);
        $detail = $query->first();

        $query->leftjoin('manage_request_messages', 'manage_request_messages.request_list_id', '=', 'request_lists.id');

        $column = 'manage_request_messages.*';
        $request_check_message = ManageRequestMessage::select(DB::raw($column))
            ->where('request_list_id', $request_list_id)
            ->orderBy('created_at', 'desc')
            ->get();

            if ($request_check_message) {
            $detail['request_check_message'] = $request_check_message->toArray();
        } else {
            $detail['request_check_message'] = [];
        }

        return $detail;
    }
    
    public function requestSearch($search_param)
    {
        $column = 'request_lists.*';
        $column .= ', users.name as viewer_name, cast_admins.name as cast_name, cast_admins.period';
        if (!empty($search_param['free_word'])) {
            $column.= ', CASE WHEN request_lists.message like "%' . $search_param['free_word'] . '%" THEN 1 ELSE 0 END as name_hit';
        }
        $query = RequestList::select(DB::raw($column));
        $query->join('users', 'users.id', '=', 'request_lists.user_id');
        $query->join('cast_admins', 'cast_admins.id', '=', 'request_lists.cast_id');
        if (!empty($search_param['free_word'])) {
            $query->where('request_lists.message', 'like BINARY', "%".$search_param['message']."%");
        }
        if (!empty($search_param['cast_id'])) {
            $query->whereIn('request_lists.cast_id', [$search_param['cast_id']]);
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

        if (!empty($search_param['sort_column']) && !empty($search_param['sort_order'])) {
            $query->orderBy('request_lists.'. $search_param['sort_column'] , $search_param['sort_order']);
        }
        if (!empty($search_param['limit'])) {
            $query->limit($search_param['limit']);
            if ($search_param['page_no'] != 1) {
                $page_no = $search_param['page_no'] - 1;
                $offset = ($search_param['limit'] * $page_no);
                $query->offset($offset);
            }
        }

        $request_list = $query->get();
        if ($request_list) {
            $request_list = $request_list->toArray();
        }
        return $request_list;
    }



}
