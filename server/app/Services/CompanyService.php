<?php

namespace App\Services;
use App\Company;
use DB;
use Vinkla\Hashids\Facades\Hashids;

class CompanyService
{
    public function __construct(
        BankBranchMst $bank_branch_mst
    ) {
        $this->bankBranchMst = $bank_branch_mst;
    }
    public function getSearchBankList($freeword)
    {
        $column = 'code, name';
        $query = $this->bankMst->select(DB::raw($column));
        $query->where(function ($query) use ($freeword){
            if (!empty($freeword)) {
                $query->where('name', 'like', '%'.$freeword.'%');
                $query->orwhere('kana', 'like', '%'.$freeword.'%');
            }
        });
        $query->orderBy('name', 'asc')
            ->limit(config('business.shop_search_limit'));
        $bank_list = $query->get()->toArray();
        return $bank_list;
    }
}
