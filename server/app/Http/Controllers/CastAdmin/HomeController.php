<?php
namespace App\Http\Controllers\CastAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RequestList;
use App\Notice;
use Auth;
use Log;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:cast_admin');  //変更
    }
 
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cast = Auth::user();
        $request_list = RequestList::select()
        ->where('cast_id', $cast->id)
        ->where('status', 0)
        ->get();
        if ($request_list) {
            $request_list = $request_list->toArray();
        }
        
        $column = "count('id') as notice_count";
        $notice_list = Notice::select(DB::raw($column))
        ->where('user_id', $cast->id)
        ->where('confirmed', 0)
        ->get();
        if ($notice_list) {
            $notice_list = $notice_list->toArray();
        }
        return view('cast_admin.home', compact('cast','request_list', 'notice_list'));
    }
}