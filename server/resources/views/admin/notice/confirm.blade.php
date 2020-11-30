@extends('layouts.app_admin')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">キャスト詳細</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i>
                    お知らせ
                    <a href="/admin/cast/list">戻る</a>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" method="post" action="{{action('NoticeController@adminSearch')}}" class="form">
                                {{ csrf_field() }}
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                        <tr>
                                            <th style="width: 20%">#</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>タイトル </td>
                                                <td>{{ $input_data['title'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>送信ユーザー </td>
                                                <td>{{ $input_data['send_user_type'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>メッセージ </td>
                                                <td>{{ $input_data['message'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" name="title" value="{{ $input_data['title'] }}">
                                <input type="hidden" name="message" value="{{ $input_data['message'] }}">
                                <input type="hidden" name="send_type" value="{{ $input_data['send_user_type'] }}">
                                <input class="btn btn-primary" type="submit" value="送信" />
                            </from>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.col-lg-8 (nested) -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.panel-body -->
            </div>
        </div>
    </div>
</div>
@endsection
