<?php

namespace App\Http\Controllers;

use App\Enums\BankAccountType;
use App\Enums\CoinHistoryState;
use App\Services\BankService;
use App\Services\CoinHistoryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class WithdrawController extends Controller
{
    private $coinHistoryService;
    private $bankService;

    public function __construct(CoinHistoryService $coinHistoryService, BankService $bankService)
    {
        $this->coinHistoryService = $coinHistoryService;
        $this->bankService = $bankService;
    }

    /**
     * 検索
     */
    public function search(Request $request)
    {
        $result = $this->coinHistoryService->search($request->all());
        return response()->json($result);
    }

    /**
     * 詳細
     */
    public function detailYearMonth(Request $request)
    {
        $params = Validator::make($request->all(), [
            'withdraw_year_month' => 'required|string|size:6',
            'pageNo'              => 'nullable|integer',
            'pageSize'            => 'nullable|integer',
        ])->validate();

        $result = $this->coinHistoryService->detailYearMonth($params);
        return response()->json($result);
    }

    /**
     * 出金ステートの更新
     */
    public function updateWithdrawState(Request $request)
    {
        $params = Validator::make($request->all(), [
            'coin_history_id' => 'required|integer',
            'withdraw_state'  => [
                'required',
                Rule::in([CoinHistoryState::Normal, CoinHistoryState::Failure]),
            ]
        ])->validate();
        $result = $this->coinHistoryService->updateWithdrawState($params);
        return $this->jsonResponseOkAndResult($result);
    }

    /**
     * 銀行サジェスト
     */
    public function suggestBank(Request $request)
    {
        $params = Validator::make($request->all(), [
            'bank_name' => 'required|string|min:1',
        ])->validate();
        $bankList = $this->bankService->suggestBank($params['bank_name']);
        return response()->json($bankList);
    }

    /**
     * 銀行支店サジェスト
     */
    public function suggestBankBranch(Request $request)
    {
        $params = Validator::make($request->all(), [
            'bank_code'        => 'required|string',
            'bank_branch_name' => 'required|string|min:1',
        ])->validate();
        $bankBranchList = $this->bankService->suggestBankBranch($params['bank_code'], $params['bank_branch_name']);
        return response()->json($bankBranchList);
    }

    /**
     * 口座情報の更新
     */
    public function updateBankAccount(Request $request)
    {
        $params = Validator::make($request->all(), [
            'user_id'          => 'required|integer',
            'bank_code'        => 'required|string',
            'bank_branch_code' => 'required|string',
            'account_no'       => 'required|string|between:5,7',
            'account_name'     => 'required|string|max:100',
            'accouont_type'    => 'required|integer',
        ])->validate();
        $this->coinHistoryService->updateBankAccount($params);
        return $this->jsonResponseOk();
    }

    /**
     * CSVダウンロード
     */
    public function downloadCsv(Request $request)
    {
        $params = Validator::make($request->all(), [
            'withdraw_year_month' => 'required|string|size:6',
            'transfer_date'       => 'required|string|size:8',
        ])->validate();
        $list = $this->coinHistoryService->detailForCsv($params['withdraw_year_month']);
        data_set($list, '*.transfer_date', $params['transfer_date']);

        return response()->streamDownload(
            function () use ($list, $params) {
                // 出力バッファをopen
                $stream = fopen('php://output', 'w');
                // 文字コードをShift-JISに変換
                // FIXME: サーバ上でエラーになる
                // FIXME: sjisへの変換が正常にできない
                // stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');
                // データ
                foreach ($list as $row) {
                    // mb_convert_variables('SJIS-win', 'UTF-8', $row);
                    if ($row['accouont_type'] === BankAccountType::Checking) {
                        $accouontType = 2; // 当座口座は2
                    } else {
                        $accouontType = 1; // 普通口座は1
                    }
                    fputcsv($stream, [
                        '21',                     // 固定で21
                        $params['transfer_date'], // 振り込み日
                        $row['converted_transfer_name'], // 振り込み名義
                        $row['bank_code'],        // 銀行コード
                        $row['bank_branch_code'], // 支店コード
                        $accouontType,            // 口座種別
                        $row['account_no'],       // 口座番号
                        $row['converted_account_name'], // 名義人
                        $row['coin'],             // 金額
                    ]);
                }
                fclose($stream);
            },
            'list.csv',
            [
                'Content-Type' => 'application/octet-stream',
            ]
        );
    }
}
