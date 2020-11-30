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
                    未完了リクエスト
                    <a href="/admin/viewer/list">戻る</a>
                    <a href="/admin/viewer/edit?id={{ $detail['id'] }}">編集</a>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
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
                                            <td>name</td>
                                            <td>{{ $detail['name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>hash_id</td>
                                            <td>{{ $detail['hash_id'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>status</td>
                                            <td>{{ $detail['status'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>email</td>
                                            <td>{{ $detail['email'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>barthbay</td>
                                            <td>{{ $detail['barthbay'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>sex</td>
                                            <td>{{ $detail['sex'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>coin</td>
                                            <td>{{ $detail['coin'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>1111 </td>
                                            <td><video src="https://d3el26csp1xekx.cloudfront.net/v/wm-KH4D17A8X.mp4" controls></video></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
