<?php

namespace App\Http\Controllers;

use DB;
use Log;
use App\User;
use Illuminate\Http\Request;
use App\Services\ViewerService;
use Illuminate\Support\Facades\Validator;
use App\Services\ProfileImageService;

class ViewerController extends Controller
{
    // const USER_CHECK = '1';
    // const SERVER_CHECK = '9';
    // private $girl;
    private $ViewerService;
    private $profileImageService;

    public function __construct(
        ViewerService $viewerService,
        ProfileImageService $profileImageService
    ) {
        // $this->middleware('auth');
        // $this->middleware('auth:cast_admin');
        $this->viewerService = $viewerService;
        $this->profileImageService = $profileImageService;
    }


    /**
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    * 視聴者API
    * ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    */ 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiCreate(Request $request)
    {
        $user_id = $request->input('id');
        $detail = User::select('*')->where('id', $user_id)->first();
        return response()->json($list);
    }

    public function apiMypage(Request $request)
    {
      $user = Auth::user();
      $user_id = $user->id;
      $detail = User::select('*')->where('id', $user_id)->first();
      return response()->json($list);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiEdit(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $detail = User::select('*')->where('id', $user_id)->first();
        return response()->json($list);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apiUpdate(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
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
        User::where('user_id', $user_id)->update($update_data);



        //画像の更新
        $input_data = [];
        if (!empty($request['image_main'])) {
            $input_data['image_main'] = $request['image_main'];
        }
        if (!empty($request['delete_image'])) {
            // $input_data['delete_image'] = $request['delete_image'];
            $delete_image = explode(',',$request['delete_image']);
        }
        if (!empty($request['profile_image'])) {
            $input_data['profile_image'] = $request['profile_image'];
            // $profile_image = explode(',',$input_data['profile_image']);
        }
        $delete_flag = 0;
        DB::beginTransaction();
        try {
            //初期画像登録
            $delete_array = [];
            if (!empty($delete_image)) {
                $image_path = $this->profileImageService->topImage($user->profile_image);

                foreach ($delete_image as $key => $str) {
                    $delete_path = substr($str, 0, strcspn($str, '?'));
                    if ($image_path['path'] != $delete_path) {
                        if (!empty($delete_path)) {
                          $delete_array[] = $delete_path;
                          ProfileImage::where('user_id', $user_id)->where('path', $delete_path)->delete();
                        }
                    } else {
                      $pos = strpos($delete_path, 'sample/');
                      if ($pos !== false) {//sampleがあれば
                        ProfileImage::where('user_id', $user_id)->where('path', $delete_path)->delete();
                      }
                    }
                }
                $this->googleCloudService->GoogleStorageDelete($delete_array);
                $delete_flag = 1;
            }
            $image_data = [];
            $main_image = "top";
            $main = 0;
            //編集画像登録
            if (!empty($request['image_main'])) {
                $main_image = $request['image_main'];
                $main = 1;
            }


            if (!empty($main_image)) {
              array_unshift($image_data, $main_image);
            }
            $end = 0;
            if (count($image_data) == 1 && $image_data[0] == 'top') {
              $end = 1;
            }

            //画像更新があれば
            if ($end != 1) {
              foreach ($image_data as $key => $img) {

                if ($img == 'top') {
                  $tmp_path_list[] = [];
                } else {
                  if (!empty($img)) {
                    $img = str_replace(' ', '+', $img);
                    $fileData = base64_decode($img);
                    // $tmp_path = storage_path('app/'. $user['hash_id'] .'/tmp'.$key.'.jpg');
                    $tmp_path = storage_path('app/'.$user_hash_id.'/tmp'.$key.'.jpg');
                    if (!is_dir(storage_path('app/'.$user_hash_id))) {
                        mkdir(storage_path('app/'.$user_hash_id));
                    }
                    $tmp_path_list[] = $tmp_path;
                    // $tmp_path_list[$key] = $tmp_path;
                    file_put_contents($tmp_path, $fileData);
                  }

                }
              }


              //360px*360pxにリサイズ
              $storage_ffmpeg_path = storage_path('app/'. $user_hash_id);
              $width = 360;
              $height = 360;


              foreach ($tmp_path_list as $key => $tmp_name) {
                if (!empty($tmp_name)) {
                    $image = new Imagick($tmp_name);
                    $profiles = $image->getImageProfiles("icc", true);
                    $image->setCompression(imagick::COMPRESSION_JPEG);
                    $image->setCompressionQuality(100);
                    $image->stripImage();
                    if (!empty($profiles)) {
                        $image->profileImage("icc", $profiles['icc']);
                    }
                    $image->scaleImage($width, $height);
                    $image_path = $storage_ffmpeg_path.'/'.$key.'.jpg';
                    $path[] = $image_path;
                    $image->writeImage($image_path);
                }
              }

              //googleCouldStorageに保存する名前作成
              foreach ($path as $key => $path_value) {
                  if (!empty($path_value)) {
                    //メイン画像変更の場合
                    if ($main == 1) {
                      $profile_image_top = $this->profileImageService->topImage($user->profile_image);
                      $image_name = $profile_image_top['name'];
                    } else {
                      $image_name = $user_hash_id . '_' . rand();
                    }
                    $options[] = [
                        'name' => $user_hash_id.'/profile/'.$image_name.'.jpg',
                        'metadata' => ['cacheControl' => 'public,max-age=604800, no-transform']
                    ];
                    $insert[] = [
                      'name' => $image_name,
                      'path' => $user_hash_id.'/profile/'.$image_name.'.jpg',
                    ];
                  }
              }

              // バケットの名前を入力
              // $bucket_name = config('filesystems.gcs.content_bucket_name');
              $this->googleCloudService->GoogleStorageMultiple($path, $options);

              // NASから一時ファイルを削除
              Storage::deleteDirectory($user_hash_id);

              foreach ($insert as $key => $value) {
                if (!empty($value)) {
                  if ($main == 1) {
                        $sort = 0;
                  }
                  ProfileImage::updateOrCreate([
                      "name" => $value["name"],
                  ], [
                      "user_id" => $user_id,
                      "user_hash_id" => $user_hash_id,
                      "name" => $value["name"],
                      "path" => $value["path"],
                      "sort" => $sort
                  ]);
                }
              }
            }

            DB::commit();
          } catch (\Exception $e) {
              report($e);
              DB::rollback();
              $this->checkService->errorLog(__FUNCTION__, $user->id);
              $error_info = $this->checkService->errorCheck(config('const.error_code.server'));
              return response()->json($error_info);
          }
          return response()->json($list);
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
    public function adminList(Request $request)
    {
        $search_param = [];
        $list = $this->viewerService->viewerSearch($search_param);
        return view('/admin/viewer/list', compact('list'));
    }

    //キャスト検索
    public function adminSearch(Request $request)
    {
        $search_param['hash_id'] = $request->hash_id;
        $search_param['name'] = $request->name;
        $search_param['status'] = $request->status;
        $search_param['email'] = $request->email;
        $search_param['barthbay'] = $request->barthbay;
        $search_param['sex'] = $request->sex;
        $search_param['coin'] = $request->coin;

        $list = $this->viewerService->viewerSearch($search_param);
        return view('/admin/viewer/list', compact('list'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminDetail(Request $request)
    {
        $user_id = $request->input('id');
        $detail = User::select('*')->where('id', $user_id)->first();
        return view('/admin/viewer/detail', compact('detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminEdit(Request $request)
    {
        $user_id = $request->input('id');
        $detail = User::select('*')->where('id', $user_id)->first();
        return view('/admin/viewer/edit', compact('detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminUpdate(Request $request)
    {
        $user_id = $request->id;
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
        User::where('user_id', $user_id)->update($update_data);
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
    public function castAdminDetail(Request $request)
    {
        $user_id = $request->input('id');
        $viewer_detail = Viewer::select('*')->where('id', $user_id)->first();
        return view('/cast_admin/viewer/detail', compact('viewer_detail'));
    }


}
