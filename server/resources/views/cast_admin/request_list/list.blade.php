@extends('layouts.app_cast_admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">リクエストリスト</div>
                <div class="panel-body">
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>aaa</td>
                                                <td>bbbb</td>
                                                <td>ccccc</td>
                                                <td><a href="/cast_admin/request_list/detail?id=1">編集</a></td>
                                            </tr>
                                            <tr>
                                                <td>aaa</td>
                                                <td>bbbb</td>
                                                <td>ccccc</td>
                                                <td><a href="/cast_admin/request_list/detail?id=1">編集</a></td>
                                            </tr>
                                            <tr>
                                                <td>aaa</td>
                                                <td>bbbb</td>
                                                <td>ccccc</td>
                                                <td><a href="/cast_admin/request_list/detail?id=1">編集</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.col-lg-8 (nested) -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
