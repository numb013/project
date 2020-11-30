<?php

namespace App\Services;

// use Google\Cloud\Logging\LoggingClient;
use Google\Cloud\Storage\StorageClient;
use Log;

class GoogleCloudService
{
    public function __construct()
    {
    }

    // public function GoogleLog($data_json, $label)
    // {
    //     $pid = getmypid();
    //     $projectId = 'fuzoku-sns';
    //     $auth_key = base_path('iam/google_gce_default_app.json');
    //     $logging = new LoggingClient([
    //         'projectId' => $projectId,
    //         'keyFile' => json_decode(file_get_contents($auth_key, TRUE), true)
    //     ]);
    //     $logger = $logging->psrLogger($label, [
    //         'resource' => [
    //             'type' => config('logging.google_log.type'),
    //             'labels' => [
    //                 'instance_id' => config('logging.google_log.instance_id'),
    //             ]
    //         ],
    //         'clientConfig' => [
    //             'keyFilePath' => base_path('iam/google_gce_default_app.json'),// サービスアカウント作成時の認証情報jsonファイル
    //         ]
    //     ]);

    //     $data_json = $pid.$data_json;
    //     $logger->debug($data_json);
    // }


    // public function GoogleStorage($bucket_name, $path, $options)
    // {
    //     // プロジェクトIDを入力
    //     $projectId = 'fuzoku-sns';
    //     // 認証鍵までのディレクトリを入力
    //     $auth_key = base_path('iam/google_could_stroge.json');

    //     $storage = new StorageClient([
    //         'projectId' => $projectId,
    //         'keyFile' => json_decode(file_get_contents($auth_key, TRUE), true)
    //      ]);

    //      $bucket = $storage->bucket($bucket_name);

    //      $object = $bucket->upload(
    //          fopen("{$path}", 'r'),
    //          $options
    //      );
    // }


    // function GoogleStorageDelete($delete_data)
    // {
    //     // バケットの名前を入力
    //     $bucket_name = config('filesystems.gcs.content_bucket_name');
    //     // プロジェクトIDを入力
    //     $projectId = 'fuzoku-sns';
    //     // 認証鍵までのディレクトリを入力
    //     $auth_key = base_path('iam/google_could_stroge.json');

    //     $storage = new StorageClient([
    //         'projectId' => $projectId,
    //         'keyFile' => json_decode(file_get_contents($auth_key, TRUE), true)
    //     ]);

    //     $bucket = $storage->bucket($bucket_name);

    //     $post_type = $delete_data['type'] == PostType::Video ? "video" : "photo";
    //     $file_name = $delete_data['user_hash_id'] . "/" .$post_type . "/" . $delete_data['post_hash_id'] . "/";

    //     if ($delete_data['type'] == PostType::Video) {
    //         $post_name = $delete_data['post_hash_id'] . ".mp4";
    //         $delete_file_name = $file_name . $post_name;
    //         $object = $bucket->object($delete_file_name);
    //         $object->delete();

    //         $delete_file_name = $file_name . "cover.jpg";
    //         $object = $bucket->object($delete_file_name);
    //         $object->delete();

    //         $delete_file_name = $file_name . "preview.jpg";
    //         $object = $bucket->object($delete_file_name);
    //         $object->delete();

    //         $delete_file_name = $file_name . "thumbnail_r.gif";
    //         $object = $bucket->object($delete_file_name);
    //         $object->delete();

    //         $delete_file_name = $file_name . "thumbnail_s.gif";
    //         $object = $bucket->object($delete_file_name);
    //         $object->delete();
    //     } elseif($delete_data['type'] == PostType::Photo) {
    //         for ($i = 1; $i<$delete_data['photo_num'] + 1; $i++) {
    //             $post_name = $i . '.jpg';
    //             $delete_file_name = $file_name . $post_name;
    //             $object = $bucket->object($delete_file_name);
    //             $object->delete();
    //         } 
    //         $delete_file_name = $file_name . "thumbnail.jpg";
    //         $object = $bucket->object($delete_file_name);
    //         $object->delete();
    //     }
    // }


    public function GoogleStorageMultiple($path = array(), $options = array())
    {
        // プロジェクトIDを入力
        // $projectId = 'popo-katsu-266622';
        $projectId = env('GOOGLE_STOREGE_ID');

        // 認証鍵までのディレクトリを入力
        // $authy = bas_kee_path('iam/popo-katsu-iam.json');
        $auth_key = '../iam/popo-katsu-iam.json';

        $storage = new StorageClient([
            'projectId' => $projectId,
            'keyFile' => json_decode(file_get_contents($auth_key, TRUE), true)
        ]);
        // $bucket_name = "popokatsu-content";
        $bucket_name = env('BUCKET_NAME');
        
        $bucket = $storage->bucket($bucket_name);
        foreach ($path as $key => $value) {
            $object = $bucket->upload(
                fopen("{$path[$key]}", 'r'),
                $options[$key]
            );
        }
    }

    public function GoogleStorageRename($path = array()) {
        // プロジェクトIDを入力
        // $projectId = 'popo-katsu-266622';
        $projectId = env('GOOGLE_STOREGE_ID');
        // 認証鍵までのディレクトリを入力
        // $authy = bas_kee_path('iam/popo-katsu-iam.json');
        $auth_key = '../iam/popo-katsu-iam.json';
        $storage = new StorageClient([
            'projectId' => $projectId,
            'keyFile' => json_decode(file_get_contents($auth_key, TRUE), true)
        ]);
        // $bucket_name = "popokatsu-content";
        $bucket_name = env('BUCKET_NAME');
        $bucket = $storage->bucket($bucket_name);

        $object = $bucket->object($path[0]);
        $object->copy($path[1], ['name' => $path[1]]);
        $object->delete();
    }

    public function GoogleStorageDelete($delete_image) {
        Log::debug($delete_image);
        // プロジェクトIDを入力
        // $projectId = 'popo-katsu-266622';
        $projectId = env('GOOGLE_STOREGE_ID');
        // 認証鍵までのディレクトリを入力
        // $authy = bas_kee_path('iam/popo-katsu-iam.json');
        $auth_key = '../iam/popo-katsu-iam.json';
        $storage = new StorageClient([
            'projectId' => $projectId,
            'keyFile' => json_decode(file_get_contents($auth_key, TRUE), true)
        ]);
        // $bucket_name = "popokatsu-content";
        $bucket_name = env('BUCKET_NAME');

        $bucket = $storage->bucket($bucket_name);

        foreach ($delete_image as $key => $value) {
            $pos = strpos($value, 'sample/');
            if ($pos === false) {//sampleがなければ
                Log::debug("画像削除になります！！！！！！！！！！");
                Log::debug($value);
                $object = $bucket->object($value);
                $object->delete();
            }
        }
    }



}