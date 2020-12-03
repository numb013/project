<?php

namespace App\Services;
use App\Video;
use DB;
use Vinkla\Hashids\Facades\Hashids;
use App\Services\GoogleCloudService;

class VideoService
{

    private $googleCloudService;

    public function __construct(
        GoogleCloudService $googleCloudService
    ) {
        $this->googleCloudService = $googleCloudService;
    }
    public function videoUpdate($param)
    {
        Video::where('id', $param['video_id'])->update(['state' => $param['state']]);
    }

    public function videoUpload($request, $user_hash_id)
    {
        if ($request->file('video_file')->isValid([])) {

            $file = $request->file('video_file');
            $tmp_name = $file->path();
            $upload_name = $file->getClientOriginalName();
            $video['size'] = $file->getClientSize();
            $video['type'] = $file->getClientMimeType();
            // $tmp_name = $_FILES['video_file']['tmp_name'];
            // $upload_name = $_FILES['video_file']['name'];
            // $video['size'] = $_FILES['video_file']['size'];
            // $video['type'] = $_FILES['video_file']['type'];

            $ext = substr($upload_name, strrpos($upload_name, '.') + 1);
            //ファイルチェック（mov、mp4、m2tsのみ）
            $file_error = $this->fileValidate($video);
            if (!empty($file_error)) {
                // $error_info = $this->checkService->errorCheck($error);
                // return response()->json($error_info);
                return false;
            }

            //ビデオ情報の取得
            $ffprobe =  FFMpeg\FFProbe::create();
            $ffmpeg =  FFMpegAdd::create([], Log::getLogger());

            $is_sound = $ffprobe->streams($tmp_name)->audios()->count();

            //メディアファイルの検証（ffmpegで処理可能かどうか確認）
            // $video['valid'] = $ffprobe->isValid($tmp_name); // returns bool
            //ビデオ長さ取得
            $video['duration'] = $ffprobe
                ->streams($tmp_name) // extracts streams informations
                ->videos()                      // filters video streams
                ->first()                       // returns the first video stream
                ->get('duration');

            //ビデオ縦横取得
            $video_dimesions = $ffprobe
                ->streams($tmp_name) // extracts streams informations
                ->videos()                      // filters video streams
                ->first()                       // returns the first video stream
                ->getDimensions();

                //綺麗な動画アップの仕様決まるまでコメントアウト
            // $video['bit_rate'] = $ffprobe
            //     ->streams($tmp_name) // extracts streams informations
            //     ->videos()                      // filters video streams
            //     ->first()                       // returns the first video stream
            //     ->get('bit_rate');

            $video['width'] = $video_dimesions->getWidth();
            $video['height'] = $video_dimesions->getHeight();

            //ビデオハッシュID取得
            $video['hash_val'] = hash_file('md5', $tmp_name);
            //ビデオバリデーション
            $error = $this->videoValidate($video);
            if (!empty($error)) {
                // $error_info = $this->checkService->errorCheck($error);
                // return response()->json($error_info);
                return false;
            }

            //フォルダ内に動画が既にある場合、削除する。
            $exist_file = Storage::files($user_hash_id);
            if (!empty($exist_file)) {
                Storage::deleteDirectory($user_hash_id);
            }
            if (!is_dir(storage_path('app/'.$user_hash_id))) {
                mkdir(storage_path('app/'.$user_hash_id));
            }
            //一旦strageフォルダに保存
            $url = Storage::disk('local')->put($user_hash_id, $file);
            $origin_movie_path = storage_path('app/'.$url);
            $copy_movie_path = storage_path('app/'.$user_hash_id.'/movie.mp4');
            $storage_video = $ffmpeg->open($origin_movie_path);
            $format = new X264();
            $format->setAudioCodec('copy');
            $format->setVideoCodec('copy');
            $storage_video->save($format, $copy_movie_path);

            // バケットの名前を入力
            $bucket_name = config('filesystems.gcs.upload_bucket_name');
            //動画PATH
            $path = array();
            $path[] = $origin_movie_path;
            $path[] = $copy_movie_path;
            $options = array();
            $total_seconds_hash = md5(time());
            $options[] = ['name' => $hash_id.'/up/'.$total_seconds_hash.'/original.'.$ext];
            $options[] = ['name' => $hash_id.'/up/'.$total_seconds_hash.'/view.mp4'];
            $this->googleCloudService->GoogleStorageMultiple($bucket_name, $path, $options);
            //NASから一時ファイルを削除
            Storage::deleteDirectory($user_hash_id);

            $gcs_storage_url = 'https://storage.googleapis.com/';
            $data = [
                // 'video_url' => secure_url('/').'/upload/'.$options[1]['name'],
                // 'original_video_url' => secure_url('/').'/upload/'.$options[0]['name'],
                'video_url' => $gcs_storage_url.$bucket_name.'/'.$options[1]['name'],
                'original_video_url' => $gcs_storage_url.$bucket_name.'/'.$options[0]['name'],
                'origin_width' => $video['width'],
                'origin_height' => $video['height'],
                'movie_length' => (int)$video['duration'],
                'is_sound' => $is_sound,
                'file_size' => $video['size'],
            ];

            $add_like = Video::firstOrCreate([
              'cast_id' => $user_id, 
              'hash_id' => $mes,
              'updated_at' => now()
            ], [
              'cast_id' => $user_id, 
              'hash_id' => $mes,
              'target_type' => 1,
              'state' => 1,
              'created_at' => now(),
              'updated_at' => now()
            ]);


        } else {
            // return redirect()
            //     ->back()
            //     ->withInput()
            //     ->withErrors(['file' => 'アップロードされていないか不正なデータです。']);
            return false;
        }
        return $data;
    }
}
