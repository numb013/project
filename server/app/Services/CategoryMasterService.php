<?php

namespace App\Services;
use App\CategoryMaster;
use DB;
use Vinkla\Hashids\Facades\Hashids;

class CategoryMasterService
{
    public function __construct(

    ) {

    }
    public function CategoryMasterSearch($search_param)
    {
        $query = CategoryMaster::select('*');
        if (!empty($search_param['title'])) {
            $query->where('title', 'like BINARY', "%$search_param['title']%");
        }
        if (!empty($search_param['is_ng'])) {
            $query->where('is_ng', $search_param['is_ng']);
        }
        if (!empty($search_param['is_offcial'])) {
            $query->where('is_offcial', $search_param['is_offcial']);
        }
        if (!empty($search_param['sort_column']) && !empty($search_param['sort_order'])) {
            $query->orderBy('request_lists.'. $search_param['sort_column'] , $search_param['sort_order']);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        if (!empty($search_param['limit'])) {
            $query->limit($search_param['limit']);
            if ($search_param['page_no'] != 1) {
                $page_no = $search_param['page_no'] - 1;
                $offset = ($search_param['limit'] * $page_no);
                $query->offset($offset);
            }
        }
        $list = $query->get()->toArray();
        return $list;
    }
}
