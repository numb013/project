<?php

namespace App\Services;
use App\Notice;
use App\User;
use App\Cast;
use DB;
use Vinkla\Hashids\Facades\Hashids;

class NoticeService
{
    public function __construct(
        BankBranchMst $bank_branch_mst
    ) {
        $this->bankBranchMst = $bank_branch_mst;
    }
    public function addNotice($param)
    {
        //リクエスト動画の場合
        if ($param['type'] == 1) {
            $cast = Cast::find($param['cast_id']);
            $viewer = User::find($param['user_id']);

            if ($param['state'] == 2) {
                $message = $viewer->name . "さんへのリクエスト動画の再提出お願いします";
                $notice_data = [
                    'cast_id' => $param['cast_id'],
                    'type' => 1,
                    'confirmed' => 0,
                    'category' => 1,
                    'message' => $message,
                ];
                Notice::create($notice_data);
            } else if ($param['state'] == 3) {
                $message = $viewer->cast . "さんからリクエスト頂きました動画が届きました。";
                $notice_data = [
                    'user_id' => $param['user_id'],
                    'type' => 1,
                    'confirmed' => 0,
                    'category' => 1,
                    'message' => $message,
                ];
                Notice::create($notice_data);

                $message = $viewer->name . "さんへのリクエスト頂きました動画が届しました。";
                $notice_data = [
                    'cast_id' => $param['cast_id'],
                    'type' => 1,
                    'confirmed' => 0,
                    'category' => 1,
                    'message' => $message,
                ];
                Notice::create($notice_data);
            }
        } 
    }
}
