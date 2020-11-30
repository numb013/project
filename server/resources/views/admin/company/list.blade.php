@extends('layouts.app_admin')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">事務所一覧</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    検索
                </div>
                <div class="panel-body">
                    <div class="row">
                        <form role="form" method="post" action="{{action('CastController@adminSearch')}}" class="form">
                            {{ csrf_field() }}
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>フリーワード</label>
                                    <input class="form-control" name="free_word" placeholder="タイトル　タイトル　依頼" value="{{ old('name') }}">
                                </div>

                                <div data-toggle='buttons' id='menu' class="form-group">
                                   @foreach (config('const.request_status') as $key => $value)
                                        <label class='btn btn-default' for="{{ 'check'.$key }}" style="margin: 2px;">
                                        <input id="{{ 'check'.$key }}" type="checkbox" name="checkbox[]" value="{{ $value }}"{{ is_array(old("checkbox")) && in_array("$value", old("checkbox"), true)? ' checked' : '' }}>{{ $value }}
                                        </label>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-default">送信</button>
                            </div>
                        </form>
                        <!-- /.col-lg-6 (nested) -->
                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->

        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i>未完了事務所
                    <a href="/admin/company/create">事務所新規作成</a>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>名前</th>
                                        <th>獲得コイン</th>
                                        <th>権限</th>
                                        <th>リクエスト数</th>                                        
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($list as $key => $value)
                                            <tr>
                                                <td>{{ $value['name'] }}</td>
                                                <td>{{ $value['get_coin'] }}</td>
                                                <td>{{ $value['authority'] }}</td>
                                                <td>{{ $value['total_post'] }}</td>
                                                <td><a href="/admin/company/detail?id={{ $value['id'] }}">詳細</a></td>
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
    </div>
</div>
@endsection
