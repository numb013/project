<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Imagick;

class PostController extends Controller
{
    // private $postHashService;
    // private $googleCloudService;
    // private $checkService;

    public function __construct(
        // PostHashService $postHashService,
        // GoogleCloudService $googleCloudService,
        // CheckService $check_service
    ) {
        // $this->postHashService = $postHashService;
        // $this->googleCloudService = $googleCloudService;
        // $this->checkService = $check_service;
    }

    public function photoRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'photo_file' => 'required|array',
        ]);

        $user = Auth::user();
        $user_hash_id = $user->hash_id;
        $user_id = $user->id;
        $img_list = $request->photo_file;

        $detail = $request->detail;
        $detail = str_replace(array("\n\n\n"), "\n", $detail);



        $photo_num = 0;
        foreach ($img_list as $img) {
            $photo_num++;
            //文字列削除
            if (strpos($img, 'data:image/png;base64') !== false) {
                $img = str_replace('data:image/png;base64,', '', $img);
            } elseif (strpos($img, 'data:image/jpeg;base64') !== false) {
                $img = str_replace('data:image/jpeg;base64,', '', $img);
            }
            $img = str_replace(' ', '+', $img);

            $fileData = base64_decode($img);
            $tmp_path = storage_path('app/'.$user_hash_id.'/tmp'.$photo_num.'.jpg');
            $tmp_path_list[$photo_num] = $tmp_path;
            file_put_contents($tmp_path, $fileData);
        }

        try {
            //720px*720pxにリサイズ
            $storage_ffmpeg_path = storage_path('app/'.$user_hash_id);
            $width = 720;
            $height = 720;
            foreach ($tmp_path_list as $key => $tmp_name) {
                if (!(empty($tmp_name))) {
                    $image = new Imagick($tmp_name);
                    $profiles = $image->getImageProfiles("icc", true);
                    $image->setCompression(imagick::COMPRESSION_JPEG);
                    $image->setCompressionQuality(90);
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

            //サムネイル作成
            $thumbnail_width = 360;
            $thumbnail_height = 360;
            $thumbnail = new Imagick($tmp_path_list[1]);
            $thumbnail_profiles = $thumbnail->getImageProfiles("icc", true);
            $thumbnail->setCompression(imagick::COMPRESSION_JPEG);
            $thumbnail->setCompressionQuality(90);
            $thumbnail->stripImage();
            if (!empty($thumbnail_profiles)) {
                $thumbnail->profileImage("icc", $thumbnail_profiles['icc']);
            }
            $thumbnail_path = $storage_ffmpeg_path.'/thumbnail.jpg';
            $path[] = $thumbnail_path;
            $thumbnail->scaleImage($thumbnail_width, $thumbnail_height);
            $thumbnail->writeImage($thumbnail_path);

            //DB登録
            $created_at = !empty($request->created_at) ? $request->created_at : date('Y/m/d H:i:s');

            $data = [
                'user_id' => $user_id,
                'type' => PostType::Photo,
                'state' =>PostState::Publishing,
                'detail' => $detail,
                'photo_num' => $photo_num,
                'comment_disabled' => $request->comment_flg,
                'open_flg' => $request->open_status,
                'created_at' => $created_at,
            ];

            $post_hash_id = $this->postService->insertPost($data, $hash_val);

            if ($post_hash_id == false) {
                $error_info = $this->checkService->errorCheck(self::SERVER_CHECK);
                return response()->json($error_info);
            }
            if (!empty($post_hash_id)) {
                //googleCouldStorageに保存する名前作成
                $path_count = count($path);
                foreach ($path as $key => $path_value) {
                    $cnt = $key + 1;
                    if ($path_count == $cnt) {
                        $options[] = [
                            'name' => $user_hash_id.'/photo/'.$post_hash_id.'/thumbnail.jpg',
                            'metadata' => ['cacheControl' => 'public,max-age=86400, no-transform']
                        ];
                    } else {
                        $options[] = [
                            'name' => $user_hash_id.'/photo/'.$post_hash_id.'/'.$cnt.'.jpg',
                            'metadata' => ['cacheControl' => 'public,max-age=86400, no-transform']
                        ];
                    }
                }
                // バケットの名前を入力
                $bucket_name = config('filesystems.gcs.content_bucket_name');
                $this->googleCloudService->GoogleStorageMultiple($bucket_name, $path, $options);

                // 同時アップロードの処理
                if (!empty($request->blog_sync)) {
                    $blog_sync = json_decode($request->blog_sync);
                    $subject = $blog_sync[0]->title;
                    $emails = [];
                    foreach ($blog_sync[0]->blog_email_sync as $key => $value) {
                        if ($value->sync == 1) {
                            if(strpos($value->email,'cityheaven') === false){
                                $emails[] = $value->email;
                            } else {
                                $this->sendMailService->photoBlogSendMail($subject, $detail, $value->email, $tmp_path_list);
                            }
                        }
                    }
                    if (!empty($emails)) {
                        $this->sendMailService->photoBlogSendMail($subject, $detail, $emails, $tmp_path_list);
                    }
                }
                // NASから一時ファイルを削除
                Storage::deleteDirectory($user_hash_id);
            }
        } catch (\Exception $e) {
            report($e);
            $dir = storage_path('app/error_image_'.$user_hash_id);
            // フォルダなければ作成
            if(!file_exists($dir)){
                mkdir($dir, 0700);
            }
            copy($tmp_name, storage_path('app/error_image_'.$user_hash_id . '/'. date("YmdHis") .'.jpg'));
            $error_info = $this->checkService->errorCheck(self::SERVER_CHECK);
            return response()->json($error_info);
        }

        DB::beginTransaction();
        try {
            //同時投稿があればgirlテーブルにアドレス保存
            if (!empty($request->blog_sync)) {
                $blog_sync = json_decode($request->blog_sync);
                $blog_email_sync = json_encode($blog_sync[0]->blog_email_sync);
                Girl::find($user_id)->fill(['blog_mail_address' => $blog_email_sync])->save();
            }
            // 画像の投稿数加算
            Girl::where('id', $user_id)->increment('total_photo_count', 1);

            $post_info = Post::where('hash_id', '=', $post_hash_id)->where('state', '=', PostState::Publishing)->first();
            if ($post_info->created_at < date('Y-m-d H:i:s')) {
                $content = '投稿が完了しました。';
                Notification::create([
                    'to_user_id' => $user_id,
                    'confirmed' => NotificationsConfirmation::Unconfirmed,
                    'type' => NotificationsType::System,
                    'content' => $content
                ]);

                //視聴者に投稿お知らせ
                $post_id = $this->postService->decodePostHashid($post_hash_id);
                $name = Girl::find($user_id)->name;
                DB::insert('
                    insert into notifications(
                        from_user_id,
                        to_user_id,
                        confirmed,
                        post_id,
                        type,
                        content,
                        created_at
                    )
                    select
                        ' . $user_id .',
                        from_id,
                        ' . NotificationsConfirmation::Unconfirmed .',
                        ' . $post_id .',
                        ' . NotificationsType::NewPosts .',
                        "' . $name . 'さんが投稿しました。",
                        "' . now() . '"
                    from
                        follow
                    where
                        to_id = ' . $user_id .'
                    and subscribe_post = 1'
                );

                $ids = Follow::select('users.id')
                    ->join('users', 'users.id', '=', 'follow.from_id')
                    ->where('follow.to_id', '=', $user_id)
                    ->where('follow.subscribe_post', '=', 1)
                    ->get()->toArray();
                $user_ids = array_column($ids, 'id');
                $message = $name . 'さんが投稿しました。';
                $param = 'timeline/'. $user_hash_id . '?photo=' . $post_hash_id;
                $url = config('app.app_url') . $param;
                $this->notificationService->push_notice($user_ids, 'post', $message, $url);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            report($e);
            $error_info = $this->checkService->errorCheck(self::SERVER_CHECK);
            return response()->json($error_info);
        }
        //Pub/Subに送信
        $this->pubsubService->userPuSubUpdate($user_hash_id);

        return response()->json(['post_hash_id' => $post_hash_id]);
    }

    public function MovieUpload(Request $request) {
        $user = Auth::user();
        $hash_id = $user->hash_id;

        $validator = Validator::make($request->all(), [
            'video_file' => 'required|file'
        ]);
        // バリデーションエラーだった場合
        if ($validator->fails()) {
            $error_info = $this->checkService->errorCheck(self::REQUEST_CHECK);
            return response()->json($error_info);
        }

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
            $error_info = $this->checkService->errorCheck(self::CONTENT_CHECK);
            Log::debag('file_error');
            return response()->json($error_info);
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
            $error_info = $this->checkService->errorCheck($error);
            return response()->json($error_info);
        }

        //フォルダ内に動画が既にある場合、削除する。
        $exist_file = Storage::files($hash_id);
        if (!empty($exist_file)) {
            Storage::deleteDirectory($hash_id);
        }
        if (!is_dir(storage_path('app/'.$hash_id))) {
            mkdir(storage_path('app/'.$hash_id));
        }
        //一旦strageフォルダに保存
        $url = Storage::disk('local')->put($hash_id, $file);
        $origin_movie_path = storage_path('app/'.$url);
        $copy_movie_path = storage_path('app/'.$hash_id.'/movie.mp4');
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
        Storage::deleteDirectory($hash_id);
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
        return response()->json($data);
    }

    public function videoValidate($video)
    {
        $error = 0;
        // if ($video['valid'] == false) {
        //     $error = 1;
        // }
        if ($video['duration'] < 4 || $video['duration'] > 60) {
            //4秒以上もしくは60秒以内の動画
            $error = self::POST_CHECK_1;
        }

            //綺麗な動画アップの仕様決まるまでコメントアウト
//         if ($video['width'] < 640 && $video['height'] < 640) {
// Log::debug("2動画チェック動画チェック動画チェック動画チェック動画チェック");
// Log::debug($video['bit_rate']);
// Log::debug($video['width']);
// Log::debug(' : ');
// Log::debug($video['height']);
//             if ($video['bit_rate'] <= 1400000) {
//                 $error = self::POST_CHECK_5;
//             }
//         }
        if ($video['size'] > 314572800) {
            //動画のファイルサイズが300MBを超えている為アップロードできません
            $error = self::POST_CHECK_2;
        }
        $exists_hash_val = $this->postHashService->existsCheckHashVal($video['hash_val']);
        if ($exists_hash_val) {
            //同じ動画ファイルはアップロードできません
            $error = self::POST_CHECK_3;
        }
        return $error;
    }

    public function fileValidate($video)
    {
        $error = 0;
        $video_type_array = array('video/mp4','video/vnd.dlna.mpeg-tts','video/quicktime');
        if (!(in_array($video['type'], $video_type_array))) {
        //mp4もしくはmovのファイル形式で
            $error = self::POST_CHECK_4;
        }
        return $error;
    }

}
