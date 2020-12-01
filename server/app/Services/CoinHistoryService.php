<?php

namespace App\Services;

use App\Enums\CoinHistoryDrawn;
use App\Enums\CoinHistoryState;
use App\Enums\CoinHistoryType;
use App\Models\CoinHistory;
use App\Models\Girl;
use App\Models\UserBankAccount;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoinHistoryService
{
    const DEFAULT_PAGE_SIZE = 20;

    public function __construct(CoinHistory $coinHistory, UserBankAccount $userBankAccount)
    {
        $this->coinHistory = $coinHistory;
        $this->userBankAccount = $userBankAccount;
    }

    /**
     * 検索
     */
    public function search(array $params = []): array
    {
        $query = $this->coinHistory::query();

        $query->select([
            DB::raw('max(withdraw_year_month) as withdraw_year_month'), // 出金年月
            DB::raw('cast(sum(coin) as signed) as total_coin'), // 出金合計金額
            DB::raw('cast(sum(case when withdraw_state = 0 then 1 else 0 end) as signed) as applied'), // 申請済み件数
            DB::raw('cast(sum(case when withdraw_state = 1 then 1 else 0 end) as signed) as normal'), // 正常(成功)件数
            DB::raw('cast(sum(case when withdraw_state = 2 then 1 else 0 end) as signed) as failure'), // 失敗件数
            DB::raw('cast(sum(case when withdraw_state = 3 then 1 else 0 end) as signed) as withdraw'), // 退会済み件数
        ]);

        $query->where('type', CoinHistoryType::WithdrawalApplication);

        // 何ページ目を何件取得するか
        if (data_get($params, 'pageNo') !== null) {
            $pageNo = $params['pageNo'];
        } else {
            $pageNo = 1;
        }
        if (data_get($params, 'pageSize') !== null) {
            $pageSize = $params['pageSize'];
        } else {
            $pageSize = self::DEFAULT_PAGE_SIZE;
        }

        // ページング処理のためlimitする前の全件数を取得する
        $totalCount = $query
            ->groupBy("coin_history.withdraw_year_month")
            ->get()
            ->count();

        $coinHistories = $query
            ->orderBy("coin_history.withdraw_year_month", 'desc')
            ->offset(($pageNo - 1) * $pageSize)
            ->limit($pageSize)
            ->get();

        return [
            'data' => $coinHistories,
            'pageNo' => (int) $pageNo,
            "totalCount" => $totalCount,
        ];
    }

    /**
     * 年月の詳細
     */
    public function detailYearMonth(array $params = [])
    {
        $query = $this->coinHistory
            ->select([
                'coin_history.*',
                'users.hash_id as user_hash_id',
                'girls.name as girl_name',
                'shops.id as shop_id',
                'shops.name as shop_name',
                DB::raw('cast(user_bank_account.bank_code as char) as bank_code'),
                'bank_mst.name as bank_name',
                DB::raw('cast(user_bank_account.bank_branch_code as char) as bank_branch_code'),
                'bank_branch_mst.name as bank_branch_name',
                'user_bank_account.account_name as account_name',
                'user_bank_account.accouont_type as accouont_type',
                DB::raw('cast(user_bank_account.account_no as char) as account_no'),
                'deleted_users.name as deleted_user_name',
                'deleted_user_shop.name as deleted_user_shop_name',
            ])
            ->leftJoin('user_bank_account', 'coin_history.user_bank_id', '=', 'user_bank_account.id')
            ->leftJoin('bank_mst', 'user_bank_account.bank_code', '=', 'bank_mst.code')
            ->leftJoin('bank_branch_mst', function ($join) {
                $join->on('user_bank_account.bank_branch_code', '=', 'bank_branch_mst.branch_code');
                $join->on('bank_mst.code', '=', 'bank_branch_mst.bank_code');
            })
            ->leftJoin('girls', 'coin_history.user_id', '=', 'girls.id')
            ->leftJoin('shops', 'girls.shop_id', '=', 'shops.id')
            ->leftJoin('deleted_users', 'coin_history.user_id', '=', 'deleted_users.id')
            ->leftJoin('shops as deleted_user_shop', 'deleted_users.shop_id', '=', 'deleted_user_shop.id')
            ->leftJoin('users', 'coin_history.user_id', '=', 'users.id')
            ->where('coin_history.type', CoinHistoryType::WithdrawalApplication)
            ->where('withdraw_year_month', $params['withdraw_year_month']);

        // 何ページ目を何件取得するか
        if (data_get($params, 'pageNo') !== null) {
            $pageNo = $params['pageNo'];
        } else {
            $pageNo = 1;
        }
        if (data_get($params, 'pageSize') !== null) {
            $pageSize = $params['pageSize'];
        } else {
            $pageSize = self::DEFAULT_PAGE_SIZE;
        }

        // ページング処理のためlimitする前の全件数を取得する
        $totalCount = $query->count();

        $coinHistories = $query
            ->offset(($pageNo - 1) * $pageSize)
            ->limit($pageSize)
            ->get();

        return [
            'data' => $coinHistories,
            'pageNo' => $pageNo,
            "totalCount" => $totalCount,
        ];
    }

    /**
     * 出金ステートの更新
     */
    public function updateWithdrawState(array $params)
    {
        $coinHistory = $this->coinHistory->find($params['coin_history_id']);

        if ($coinHistory->withdraw_state !== CoinHistoryState::Applied) {
            throw new \Exception('ステートが申請済み以外の場合は変更できません。');
        }

        if ($params['withdraw_state'] === CoinHistoryState::Failure) {
            // 振り込み失敗の場合
            return DB::transaction(function () use ($params, $coinHistory) {
                $coinHistory->fill([
                    'withdraw_state' => $params['withdraw_state'],
                ])->save();

                // 女の子のウォレットにコインを戻す
                Girl::find($coinHistory->user_id)
                    ->increment('coin', $coinHistory->coin);
                return $coinHistory;
            });
        }

        DB::beginTransaction();
        try {
            if ($params['withdraw_state'] === CoinHistoryState::Normal) {
                logger('normal');
                $this->coinHistory
                    ->where('user_id', $coinHistory->user_id)
                    ->where('created_at', '<', $coinHistory->created_at)
                    ->whereIn('type', [
                        CoinHistoryType::Automatic,
                        CoinHistoryType::manual,
                        CoinHistoryType::Grant,
                    ])
                    ->where('drawn', CoinHistoryDrawn::NotWithdrawn)
                    ->update(['drawn' => CoinHistoryDrawn::Withdrawn]);
            }

            // 失敗以外のステートに更新する場合
            logger('$coinHistory: ', $coinHistory->toArray());
            $coinHistory->fill([
                'withdraw_state' => $params['withdraw_state'],
            ])->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $coinHistory;
    }

    /**
     * ユーザの口座情報編集
     */
    public function updateBankAccount(array $params)
    {
        $this->userBankAccount
            ->where('user_id', $params['user_id'])
            ->first()
            ->fill([
                'bank_code' => $params['bank_code'],
                'bank_branch_code' => $params['bank_branch_code'],
                'account_no' => $params['account_no'],
                'account_name' => $params['account_name'],
                'accouont_type' => $params['accouont_type'],
            ])
            ->save();
    }

    /**
     * CSV用のデータ取得
     */
    public function detailForCsv(string $withdrawYearMonth)
    {
        $coinHistories = $this->coinHistory
            ->select([
                DB::raw('coin_history.coin - 400 as coin'),
                'user_bank_account.bank_code',
                'user_bank_account.bank_branch_code',
                'user_bank_account.accouont_type',
                'user_bank_account.account_name',
                'user_bank_account.account_no',
                'user_bank_account.transfer_name',
            ])
            ->leftJoin('user_bank_account', 'coin_history.user_bank_id', '=', 'user_bank_account.id')
            ->where('withdraw_year_month', $withdrawYearMonth)
            ->where('coin_history.type', CoinHistoryType::WithdrawalApplication)
            ->where('withdraw_state', CoinHistoryState::Applied)
            ->get();

        $bankService = app('App\Services\BankService');
        $converedCoinHistories = $coinHistories->map(function ($item) use ($bankService) {
            $item->converted_transfer_name = $bankService->accountNameForBankRule($item->transfer_name);
            $item->converted_account_name = $bankService->accountNameForBankRule($item->account_name);
            return $item;
        });

        return $converedCoinHistories->toArray();
    }
}
