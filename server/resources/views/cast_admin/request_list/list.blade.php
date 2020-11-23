@extends('layouts.app_cast_admin')

@section('content')
<div class="container-fluid">
    <div class="row" style="margin-top: 40px">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    検索
                </div>
                <div class="panel-body">
                    <div class="row">
                        <form role="form">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>フリーワード</label>
                                    <input class="form-control" placeholder="タイトル　タイトル　依頼">
                                </div>
                                <div class="form-group">
                                    <label>状態</label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox">未対応
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox">提出中
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox">再提出
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox">完了
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-default">Submit Button</button>
                                <button type="reset" class="btn btn-default">Reset Button</button>
                            </div>
                        </form>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    リクエストリスト
                    <a href="/cast_admin/home">戻る</a>
                </div>
                <div class="panel-body">
                    <!-- /.panel-heading -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($list as $key => $value) 
                                        <tr>
                                            <td>{{ $value["id"] }} </td>
                                            <td>{{ $value["to_name"] }}</td>
                                            <td>{{ $value["message"] }}</td>
                                            <td><a href="/cast_admin/request_list/detail?id={{ $value['id'] }}">編集</a></td>
                                        </tr>
                                    @endforeach

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
@endsection
