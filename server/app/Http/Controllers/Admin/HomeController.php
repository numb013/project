<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\RequestListService;
use App\Services\CastService;
use App\Services\ViewerService;
use App\Services\CompanyService;
use Log;

class HomeController extends Controller
{
    private $requestListService;
    private $castService;
    private $viewerService;
    private $companyService;

    public function __construct(
        RequestListService $requestListService,
        CastService $castService,
        ViewerService $viewerService,
        CompanyService $companyService
    ) {
        // $this->middleware('auth');
        // $this->middleware('auth:cast_admin');
        $this->requestListService = $requestListService;
        $this->castService = $castService;
        $this->viewerService = $viewerService;
        $this->companyService = $companyService;
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $list['count'] = [
            'request_list' => $this->requestListService->requestCount(),
            'viewer' => $this->viewerService->viewerCount(),
            'cast' => $this->castService->castCount(),
            'company' => $this->companyService->companyCount(),
        ];
        $search_param = [
            'sort_column' => 'created_at',
            'sort_order' => 'desc',
            'limit' => '8',
            'page_no' => 1,
        ];

        $list['new_list'] = [
            'request_list' => $this->requestListService->requestSearch($search_param),
            'viewer' => $this->viewerService->viewerSearch($search_param),
            'cast' => $this->castService->castSearch($search_param),
            'company' => $this->companyService->companySearch($search_param),
        ];


Log::debug($list);

        return view('admin.home', compact('list'));  //変更
    }
}