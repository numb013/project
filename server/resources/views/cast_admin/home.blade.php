@extends('layouts.app_cast_admin')

@section('content')
<div class="container-fluid">
    <div class="row" style="margin-top: 40px">
        <div class="col-lg-12">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab-default-1">
                    <div class="col-lg-12">
                        <ul style="list-style: none; margin-top: 10px; padding: 0px;">
                            <li class="left clearfix">
                                <span class="chat-img pull-left" style="margin-right: 30px;">
                                    <img src="{{ asset('images/boy.png') }}" class="img-circle"/>
                                </span>
                                <div class="chat-body clearfix">
                                    <div class="header">
                                        <strong class="primary-font">名前 : {{ $cast['name'] }}</strong>
                                    </div>
                                    <div class="header">
                                        <strong class="primary-font">獲得コイン : {{ $cast['get_coin'] }}</strong>
                                    </div>
                                    <div class="header">
                                        <strong class="primary-font">設定料金 : {{ $cast['price'] }}</strong>
                                    </div>
                                    <div class="header">
                                        <strong class="primary-font">リクエスト総数 : {{ $cast['total_post'] }}</strong>
                                    </div>
                                    <div class="header">
                                        <strong class="primary-font">期間 : {{ $cast['period'] }}</strong>
                                    </div>
                                    <p>
                                    {!! $cast['descript'] !!}
                                    </p>
                                </div>
                            </li>
                        </ul>
                        <a href="/cast_admin/cast/edit?id={{ $cast['id'] }}" class="btn btn-default btn-block">プロフィール編集</a>
                        <br>
                        <div class="list-group">
                            <p>やる事リスト</p>
                            @foreach ($request_list as $key => $value)
                            <a href="#" class="list-group-item">
                                <i class="fa fa-comment fa-fw"></i> {{ $value['user_name'] }}
                                <span class="pull-right text-muted small"><em>{{ $value['created_at'] }}</em></span>
                            </a>
                            @endforeach
                        </div>
                        <a href="/cast_admin/request_list/list?id={{ $cast['id'] }}" class="btn btn-default btn-block">すべて見る</a>
                        <!-- /.list-group -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
