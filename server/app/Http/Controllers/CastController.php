<?php

namespace App\Http\Controllers;

use DB;
use Log;
use Auth;
use App\User;
use App\Notice;
use App\CastAdmin;
use App\Company;
use App\CategoryMaster;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CastService;
use App\Services\ProfileImageService;
use App\Services\CategoryMasterService;


class CastController extends Controller
{
    private $castService;
    private $profileImageService;
    private $categoryMasterService;

    public function __construct(
        CastService $castService,
        ProfileImageService $profileImageService,
        CategoryMasterService $categoryMasterService
    ) {
        // $this->middleware('auth');
        // $this->middleware('auth:cast_admin');
        $this->castService = $castService;
        $this->profileImageService = $profileImageService;
        $this->categoryMasterService = $categoryMasterService;
    }




    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * API
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */
    public function apiCastList(Request $request)
    {
        $category_search_param = [
            'is_ng' => 1,
            'is_offcial' => 1,
            'type' => 1,
            'limit' => 5,
            'sort_column' => 'order',
            'sort_oder' => 'desc',
            'page_no' => 1,
        ];
        $cast_category_list = $this->categoryMasterService->CategoryMasterSearch($search_param);
        $cast_category_ids = array_column($cast_category_list, 'id');

        $cast_search_param = [
            'sort_column' => 'created_at',
            'sort_order' => 'desc',
            'limit' => '8',
            'page_no' => 1,
        ];
        foreach ($cast_category_ids => $key => $category_id) {
            $cast_search_param['category'] = $category_id;
            $list['cast'][$cast_category_list[$key]] = $this->castService->castSearch($cast_search_param);
        }
        //ピックアップカテゴリーpage2にする事でリスト以降のカテゴリ取得
        $category_search_param = [];
        $category_search_param = [
            'is_ng' => 1,
            'is_offcial' => 1,
            'type' => 1,
            'limit' => 5,
            'sort_column' => 'order',
            'sort_oder' => 'desc',
            'page_no' => 2,
        ];
        $list['pick_up_category'] = $this->categoryMasterService->CategoryMasterSearch($category_search_param);

        return response()->json($list);
    }

    //キャスト一覧
    public function apiSearch(Request $request)
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
        $list = $this->castService->castSearch($search_param);

        return response()->json($list);
    }

    //キャスト詳細
    public function apiDetail(Request $request)
    {
        $cast_id = $request->id;
        $detail = CastAdmin::select('*')->where('id', $cast_id)->first()->toArray();
        return response()->json($list);
    }
    











    public function index()
    {
        return view('/admin/cast/index');
    }

    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * 管理者
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */
    //キャスト作成
    public function adminCreate(Request $request)
    {
        $company = Company::select('id', 'name')->where('id', '>', 0)->get()->toArray();
        Log::debug($company);
        return view('/admin/cast/create', compact('company'));
    }

    //キャスト作成確認
    public function adminConfirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|max:100',
            'password' => 'required',
        ])->validate();

        $input_data = $request->all();

        $this->validate($request, [
            'file' => [
                // 必須
                'required',
                // アップロードされたファイルであること
                'file',
                // ファイルであること
                'image',
                // MIMEタイプを指定
                'mimes:jpeg,png',
                // 最小縦横120px 最大縦横400px
                'dimensions:min_width=120,min_height=120,max_width=400,max_height=400',
            ]
        ]);

        return view('/admin/cast/confirm', compact('input_data'));
    }

    //キャスト作成完了
    public function adminComplete(Request $request)
    {
        $insert_data = $this->castService->arrOnly($request->all());
        $insert_data['password'] = bcrypt($insert_data['password']);
        $insert_data['category'] = implode( ",", $request->genre );
        $insert_data['hash_id'] = str_random(6) . '_' . str_random(4);
        $insert_data['company_id'] = 1;

        $cast_admin_id = CastAdmin::create($insert_data);

       $this->profileImageService->imageUpload($request, $insert_data['hash_id']);


        return redirect('/admin/cast/list');
    }

    //キャスト一覧
    public function adminList(Request $request)
    {
        $search_param = [];
        $list = $this->castService->castSearch($search_param);
        return view('/admin/cast/list', compact('list'));
    }

    //キャスト検索
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
        $list = $this->castService->castSearch($search_param);
        return view('/admin/cast/list', compact('list'));
    }

    //キャスト詳細
    public function adminDetail(Request $request)
    {
        $cast_id = $request->input('id');
        $detail = CastAdmin::select('*')->where('id', $cast_id)->first()->toArray();
        return view('/admin/cast/detail', compact('detail'));
    }

    //キャスト編集
    public function adminEdit(Request $request)
    {
        $cast_id = $request->input('id');
        $detail = CastAdmin::select('*')->where('id', $cast_id)->first();
        $company = Company::select('id', 'name')->where('id', '>', 0)->get()->toArray();

        return view('/admin/cast/edit', compact('detail', 'company'));
    }

    //キャストアップデート
    public function adminUpdate(Request $request)
    {
        $cast_id = $request->id;
        $update_data = $this->castService->arrOnly($request->all());
        $insert_data['password'] = bcrypt($insert_data['password']);
        $insert_data['category'] = implode( ",", $request->genre );
        CastAdmin::where('user_id', $cast_id)->update($update_data);
        return view('/admin/cast/detail?id='.$cast_id);
    }



    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * キャスト管理
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */

    public function castAdminCreate(Request $request)
    {

        return view('/cast_admin/cast/detail');
    }


    public function castAdminProfileImage(Request $request)
    {
        $cast = Auth::user();
        $detail = CastAdmin::select('*')->where('id', $cast->id)->first()->toArray();
        Log::debug("sssssssssssssss");
        return view('/cast_admin/cast/profile_image', compact('detail'));
    }

    public function castAdminProfileImageUpdate(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'file' => [
                // 必須
                'required',
                // アップロードされたファイルであること
                'file',
                // ファイルであること
                'image',
                // MIMEタイプを指定
                'mimes:jpeg,png',
                // 最小縦横120px 最大縦横400px
                'dimensions:min_width=120,min_height=120,max_width=400,max_height=400',
            ]
        ]);
        $this->profileImageService->imageUpload($request, $user->hash_id);
        return view('/cast_admin/home');
    }

    public function castAdminEdit(Request $request)
    {
        $cast = Auth::user();
        $detail = CastAdmin::select('*')->where('id', $cast->id)->first()->toArray();
        return view('/cast_admin/cast/edit', compact('detail'));
    }

    public function castAdminDetail()
    {
        $cast = Auth::user();
        $detail = CastAdmin::select('*')->where('id', $cast->id)->first()->toArray();
        return view('/cast_admin/cast/detail', compact('detail'));
    }

    // public function passwordEdit(Request $request)
    // {
    //     $user = Auth::user();
    //     $user_id = $user->id;

    //     $validator = Validator::make($request->all(), [
    //         'new_password' => 'required|min:7|max:20',
    //         'currnet_password' => 'required|min:7|max:20',
    //     ]);
    //     // バリデーションエラーだった場合
    //     if ($validator->fails()) {
    //         $error_info = $this->checkService->errorCheck(self::REQUEST_CHECK);

    //         return response()->json($error_info);
    //     }
    //     //現在のパスワードチェック
    //     if (!Hash::check($request->currnet_password, $user->password)) {
    //         $error_info = $this->checkService->errorCheck(self::USED_PASSWORD_CHECK);

    //         return response()->json($error_info);
    //     }

    //     DB::beginTransaction();
    //     try {
    //         $reslut = User::where('id', '=', $user_id)->update(['password' => Hash::make($request->new_password)]);
    //         DB::commit();
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         report($e);
    //         $error_info = $this->checkService->errorCheck(self::SERVER_CHECK);
    //     }

    //     return response()->json(['status' => 'OK']);
    // }


}
