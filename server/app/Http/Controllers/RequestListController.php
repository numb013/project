<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Log;

use App\RequestList;
use App\User;

class RequestListController extends Controller
{


    public function __construct() {
        $this->middleware('auth');
        $this->middleware('auth:cast_admin');  //変更
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function movieUpload(Request $request)
    {


Log::debug("wwwwwwwwwwwww");

        // ① フォームの入力値を取得
        $inputs = \Request::all();
     
        // ② デバッグ： $inputs の内容確認
        //    dd($inputs);
         
        // $this->validate($request, [
        //     'file' => [
        //         // 必須
        //         'required',
        //         // アップロードされたファイルであること
        //         'file',
        //         // ファイルであること
        //         'image',
        //         // MIMEタイプを指定
        //         'mimes:jpeg,png',
        //         // 最小縦横120px 最大縦横400px
        //         'dimensions:min_width=120,min_height=120,max_width=400,max_height=400',
        //     ]
        // ]);
     
        if ($request->file('file')->isValid([])) {
            $mes = $request->file('file')->getClientOriginalName();
            $filename = $request->file->storeAs(
                'public/files', $mes
            );
            $user = User::find(auth()->id());

Log::debug("user");
Log::debug($user);

            $user->avatar_filename = basename($filename);
            $user->save();
     
            return redirect('/cast_admin/home')->with('success', '保存しました。'.$mes);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['file' => 'アップロードされていないか不正なデータです。']);
        }

        return view('/cast_admin/home');

    }
}