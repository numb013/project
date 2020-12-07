@extends('layouts.app_admin')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Dashboard</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel" style="background: #222323;color: #d8d8d8; border: 1px solid #ccc;">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-comments fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $list['count']['request_list'] }}</div>
                            <div>リクエスト数</div>
                        </div>
                    </div>
                </div>
                <a href="/admin/request_list/list" style="color: #b73363;">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel" style="background: #222323;color: #d8d8d8; border: 1px solid #ccc;">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-comments fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $list['count']['viewer'] }}</div>
                            <div>視聴者数</div>
                        </div>
                    </div>
                </div>
                <a href="/admin/viewer/list" style="color: #b73363;">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel" style="background: #222323;color: #d8d8d8; border: 1px solid #ccc;">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-comments fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $list['count']['cast'] }}</div>
                            <div>キャスト数</div>
                        </div>
                    </div>
                </div>
                <a href="/admin/cast/list" style="color: #b73363;">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel" style="background: #222323;color: #d8d8d8; border: 1px solid #ccc;">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-comments fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $list['count']['company'] }}</div>
                            <div>事務所数</div>
                        </div>
                    </div>
                </div>
                <a href="/admin/company/list" style="color: #b73363;">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>


    </div>
    <!-- /.row -->
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> 新着リクエスト
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>状態</th>
                                        <th>視聴者名</th>
                                        <th>キャスト名</th>
                                        <th>期限</th>
                                        <th>作成日</th>
                                        <th>詳細</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($list['new_list']['request_list'] as $key => $value)
                                        <tr>
                                            <td>{{ $value['status'] }}</td>
                                            <td>{{ $value['viewer_name'] }}</td>
                                            <td>{{ $value['cast_name'] }}</td>
                                            <td>{{ $value['period'] }}</td>
                                            <td>{{ $value['created_at'] }}</td>
                                            <td><a href="/admin/request_list/detail?id={{ $value['id'] }}">編集</a></td>
                                        </tr>
                                    @endforeach
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




            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> 新着視聴者
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>ニックネーム</th>
                                        <th>性別</th>
                                        <th>作成日</th>
                                        <th>#</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list['new_list']['viewer'] as $key => $value)
                                            <tr>
                                                <td>{{ $value['name'] }}</td>
                                                <td>{{ $value['sex'] }}</td>
                                                <td>{{ $value['created_at'] }}</td>
                                                <td><a href="/admin/viewer/detail?id={{ $value['id'] }}">編集</a></td>
                                            </tr>
                                        @endforeach
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
        <!-- /.col-lg-8 -->
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> 新規キャスト
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="list-group">
                        @foreach ($list['new_list']['cast'] as $key => $value)
                            <a href="/admin/cast/detail?id={{ $value['id'] }}" class="list-group-item">
                                <i class="fa fa-comment fa-fw"></i> {{ $value['name'] }}
                                <span class="pull-right text-muted small">
                                    <em>4 minutes ago</em>
                                </span>
                            </a>
                        @endforeach
                    </div>
                    <!-- /.list-group -->
                    <a href="/admin/cast/list" class="btn btn-default btn-block">View All Alerts</a>
                </div>
                <!-- /.panel-body -->
            </div>
        </div>
    <!-- /.row -->
    </div>
</div>


@endsection
