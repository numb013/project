<?php

namespace App\Http\Controllers;

use App\CategoryMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Services\CategoryMasterService;

class CategoryMasterController extends Controller
{

    private $categoryMasterService;

    public function __construct(
        CategoryMasterService $categoryMasterService
    ) {
        $this->categoryMasterService = $categoryMasterService;
    }

    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * 管理者
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    public function adminCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'unique:category_master,title|string|max:30'
        ])->validate();

        $insert_data = [
            'title' => $request->title,
            'type' => $request->type,
            'is_offcial' => 1,
        ];
        CategoryMaster::create($insert_data);

        return redirect('/admin/category_master/list');
    }

    public function adminUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'unique:category_master,title|string|max:30'
        ])->validate();
        $update_data = Arr::only($request, [
            'title',
            'order',
            'is_ng',
            'type',
            'is_offcial',
        ]);
        CategoryMaster::where('id', $request->id)->update($update_data);
        return redirect('/admin/category_master/list');
    }

    public function adminOrderChange(Request $request)
    {

    }


    public function adminCastCategoryList(Request $request)
    {
        $search_param = Arr::only($request, [
            'title',
            'is_ng',
            'is_offcial',
            'sort_column',
            'sort_oder',
            'page_no',
        ]);
        $search_param['limit'] = 100;

        $search_param['type'] = 1;
        $list['cast_list'] = $this->categoryMasterService->CategoryMasterSearch($search_param);
        return redirect('/admin/category_master/cast_list', compact('list'));
    }

    public function adminRequestCategoryList(Request $request)
    {
        $search_param = Arr::only($request, [
            'title',
            'is_ng',
            'is_offcial',
            'sort_column',
            'sort_oder',
            'page_no',
        ]);
        $search_param['limit'] = 100;
        $search_param['type'] = 2;
        $list['request_list'] = $this->categoryMasterService->CategoryMasterSearch($search_param);
        return redirect('/admin/category_master/request_list', compact('list'));
    }
}
