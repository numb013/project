<?php

namespace App\Services;
use App\ProfileImage;
use DB;
use Vinkla\Hashids\Facades\Hashids;
use App\Services\GoogleCloudService;
use Illuminate\Support\Facades\Storage;
class ProfileImageService
{

    private $googleCloudService;

    public function __construct(
        GoogleCloudService $googleCloudService
    ) {
        $this->googleCloudService = $googleCloudService;
    }
    public function imageUpload($request, $user_hash_id)
    {
        if ($request->file('file')->isValid([])) {
            $mes = $request->file('file')->getClientOriginalName();
            $filename = $request->file->storeAs(
                'public/files', $mes
            );

            $path[] = storage_path('app/'. $filename);
            $options[] = [
                'name' => $user_hash_id.'/profile/'. $mes,
                'metadata' => ['cacheControl' => 'public,max-age=604800, no-transform']
            ];
            $this->googleCloudService->GoogleStorageMultiple($path, $options);

            $add_like = ProfileImage::firstOrCreate([
              'cast_id' => $user_id, 
              'hash_id' => $mes,
              'updated_at' => now()
            ], [
              'cast_id' => $user_id, 
              'hash_id' => $mes,
              'created_at' => now(),
              'updated_at' => now()
            ]);

            // NASから一時ファイルを削除
            Storage::deleteDirectory($filename);

        } else {
            report($e);
            $dir = storage_path('app/error_image_'.$user_hash_id);
            // フォルダなければ作成
            if(!file_exists($dir)){
                mkdir($dir, 0700);
            }
            copy($tmp_name, storage_path('app/error_image_'.$user_hash_id . '/'. date("YmdHis") .'.jpg'));
            return false;
        }
        return true;
    }



    public function topImage($profile_image = null) {
        $image = null;
        foreach ($profile_image as $key => $value) {
          if ($value['sort'] == 0) {
              $image = $value;
              break;
          }
        }
        return $image;
    }
}
