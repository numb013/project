<?php

namespace App\Services;
use App\Company;
use DB;
use Vinkla\Hashids\Facades\Hashids;

class CompanyService
{
    protected $company;

    public function __construct(
        Company $company
    ) {
        $this->company = $company;
    }

    public function companyCount()
    {
        $company_list = Company::get();
        return $company_list->count();
    }

    public function arrOnly($request)
    {
        $request = Arr::only($request, [
            'name',
            'category',
            'address',
            'tel',
            'email',
            'hp_url',
            'contact_name',
            'contact_tel',
            'contact_mail',
            'company_address',
            'company_tel',
            'accouont_type',
            'transfer_name',
        ]);
        return $request;
    }

    public function companySearch($search_param)
    {
        $column = 'companies.*';
        if (!empty($search_param['free_word'])) {
            $column.= ', CASE WHEN companies.name like "%' . $search_param['free_word']. '%" THEN 1 ELSE 0 END as name_hit';
        }
        $query = $this->company->select(DB::raw($column));
        if (!empty($search_param['freeword'])) {
            $freeword = $search_param['freeword'];
            $query->where(function ($query) use ($freeword) {
                if (!empty($freeword)) {
                   $word = $this->double_explode(" ", "　", $freeword);
                    for ($i=0; $i < count($word); $i++) {
                        if ($i == 0) {
                            $search_word = str_replace(array(" ", "　"), "", $word[$i]);
                            $query->where('companies.name', 'like BINARY', "%$search_word%");
                        } else {
                            $search_word = str_replace(array(" ", "　"), "", $word[$i]);
                            $query->orwhere('companies.name', 'like BINARY', "%$search_word%");
                        }
                    }
                }
            });
        }

        if (!empty($search_param['category'])) {
            $query->whereIn('companies.category', $search_param['category']);
        }
        if (!empty($search_param['status'])) {
            $query->whereIn('companies.status', $search_param['status']);
        }
        if (!empty($search_param['address'])) {
            $query->whereIn('companies.address', $search_param['address']);
        }
        if (!empty($search_param['tel'])) {
            $query->whereIn('companies.tel', $search_param['tel']);
        }
        if (!empty($search_param['hp_url'])) {
            $query->whereIn('companies.hp_url', $search_param['hp_url']);
        }
        if (!empty($search_param['contact_name'])) {
            $query->whereIn('companies.contact_name', $search_param['contact_name']);
        }
        if (!empty($search_param['contact_tel'])) {
            $query->whereIn('companies.contact_tel', $search_param['contact_tel']);
        }
        if (!empty($search_param['contact_mail'])) {
            $query->whereIn('companies.contact_mail', $search_param['contact_mail']);
        }
        if (!empty($search_param['company_address'])) {
            $query->whereIn('companies.company_address', $search_param['company_address']);
        }
        if (!empty($search_param['company_tel'])) {
            $query->whereIn('companies.company_tel', $search_param['company_tel']);
        }
        if (!empty($search_param['accouont_type'])) {
            $query->whereIn('companies.accouont_type', $search_param['accouont_type']);
        }
        if (!empty($search_param['transfer_name'])) {
            $query->whereIn('companies.transfer_name', $search_param['transfer_name']);
        }

        if (!empty($search_param['free_word'])) {
            $query->orderBy('name_hit', 'desc');
        }
        if (!empty($search_param['sort_type'])) {
            if ($search_param['sort_type'] == 1) {
                $query->orderBy('get_coin', 'desc');
            } elseif ($search_param['sort_type'] == 2) {
                $query->orderBy('total_post', 'desc');
            } elseif ($search_param['sort_type'] == 3) {
                $query->orderBy('price', 'desc');
            }
        }

        $cast_list = $query->get();
        if ($cast_list) {
            $cast_list = $cast_list->toArray();
        }
        return $cast_list;
    }

    // キャストの詳細画面の情報の更新
    public function update(array $params = []): void
    {
        DB::transaction(function () use ($params) {
            $this->castAdmin->find($params['cast_id'])
                ->fill($params)
                ->save();

            if (isset($params['user_state'])) {
                $params['state'] = $params['user_state'];
                unset($params['user_state']);
            }

            $this->castAdmin->find($params['cast_id'])
                ->fill($params)
                ->save();
        });
    }
}
