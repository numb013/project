@extends('layouts.app_admin')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">事務所詳細</h1>
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
                    <a href="/admin/company/list">戻る</a>
                    <a href="/admin/company/edit?id={{ $detail['id'] }}">編集</a>
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
                                            <td>company_id</td>
                                            <td>{{ $detail['company_id'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>authority</td>
                                            <td>{{ $detail['authority'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>category</td>
                                            <td>{{ $detail['category'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>can_type</td>
                                            <td>{{ $detail['can_type'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>price</td>
                                            <td>{{ $detail['price'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>period</td>
                                            <td>{{ $detail['period'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>descript</td>
                                            <td>{{ $detail['descript'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>total_post</td>
                                            <td>{{ $detail['total_post'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>get_coin</td>
                                            <td>{{ $detail['get_coin'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>score</td>
                                            <td>{{ $detail['score'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>email</td>
                                            <td>{{ $detail['email'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>created_at</td>
                                            <td>{{ $detail['created_at'] }}</td>
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
