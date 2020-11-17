<?php

namespace App\Http\Controllers;

use JWTAuth;
use DB;
use App\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use Log;

class UserController extends Controller
{
    private $userService;

    public function __construct(
        UserService $user_service
    )
    {
        $this->userService = $user_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|max:255|email|unique:users,login_id',
            'password' => 'required|min:7|max:20',
        ]);

        $login_id_check = User::select('id')->where('login_id', '=', $request->email)->first();
        if (!empty($login_id_check)) {
            $error_info = $this->checkService->errorCheck(self::USED_MAILADDRESS);
            return response()->json($error_info);
        }

        // バリデーションエラーだった場合
        if ($validator->fails()) {
            $error_info = $this->checkService->errorCheck(self::REQUEST_CHECK);
            return response()->json($error_info);
        }
        $email = $request->email;
        $password = bcrypt($request->password);
        $account = $this->userService->userCreate($email, $password);
        if ($account == false) {
            $error_info = $this->checkService->errorCheck(self::SERVER_CHECK);
            return response()->json($error_info);
        }
        $account = User::find($account->id);

        DB::beginTransaction();
        try {
            // メールアドレス確認メール送信
            $this->sendMailService->sendConfirmMailAddress($account);
            //メールアドレスでの取る奥の場合はお知らせに確認通知する
            $notice_add_data = [
                'to_user_id'   => $account->id,
                'from_user_id' => null,
                'post_id'      => null,
                'confirmed'    => 0,
                'type'         => NotificationsType::System,
                'present_transaction_id' => null,
                'content'      => '現在お客様の情報は、仮登録の状態となっております。本登録用のメールを送信していますのでご確認お願いします',
            ];
            $this->notificationService->addNotification($notice_add_data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            report($e);
            return;
        }

        // Json Web Tokenの発行
        $apiAccessToken = JWTAuth::fromUser($account);
        return response()->json(['access_token' => $apiAccessToken]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
