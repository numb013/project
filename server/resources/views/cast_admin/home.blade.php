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
                                        <strong class="primary-font">名前 : </strong>
                                    </div>
                                    <div class="header">
                                        <strong class="primary-font">獲得コイン : </strong>
                                    </div>
                                    <div class="header">
                                        <strong class="primary-font">設定料金 : </strong>
                                    </div>
                                    <div class="header">
                                        <strong class="primary-font">リクエスト総数 : </strong>
                                    </div>
                                    <div class="header">
                                        <strong class="primary-font">期間 : </strong>
                                    </div>
                                    <p>
                                        Lorem ipsum dolor sit amet, <br>consectetur adipiscing elit. Curabitur bibendum
                                        ornare dolor, quis ullamcorper
                                    </p>
                                </div>
                            </li>
                        </ul>
                        <a href="/cast_admin/cast/edit" class="btn btn-default btn-block">プロフィール編集</a>
                        <br>
                        <div class="list-group">
                            <p>やる事リスト</p>
                            <a href="#" class="list-group-item">
                                <i class="fa fa-comment fa-fw"></i> New Comment
                                <span class="pull-right text-muted small"><em>4 minutes ago</em></span>
                            </a>
                            <a href="#" class="list-group-item">
                                <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                <span class="pull-right text-muted small"><em>12 minutes ago</em></span>
                            </a>
                            <a href="#" class="list-group-item">
                                <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                <span class="pull-right text-muted small"><em>12 minutes ago</em></span>
                            </a>
                            <a href="#" class="list-group-item">
                                <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                <span class="pull-right text-muted small"><em>12 minutes ago</em></span>
                            </a>
                            <a href="#" class="list-group-item">
                                <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                <span class="pull-right text-muted small"><em>12 minutes ago</em></span>
                            </a>
                        </div>
                        <a href="#" class="btn btn-default btn-block">すべて見る</a>
                        <!-- /.list-group -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
