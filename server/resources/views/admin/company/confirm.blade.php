@extends('layouts.app_admin')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">事務所確認</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i>
                    事務所確認
                    <a href="/admin/cast/list">戻る</a>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" method="post" action="{{action('CompanyController@adminComplete')}}" class="form">
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
                                                <td>名前 </td>
                                                <td>{{ $input_data['name'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>email </td>
                                                <td>{{ $input_data['email'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>password </td>
                                                <td>{{ $input_data['password'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>料金 </td>
                                                <td>{{ $input_data['price'] }}</td>
                                            </tr>
                                            <tr>
                                                <td>権限</td>
                                                <td>{{ config('const.authority')[$input_data['authority']] }}</td>
                                            </tr>

                                            <tr>
                                                <td>ジャンル </td>
                                                <td>
                                                    @foreach ($input_data['genre'] as $key => $value)
                                                        {{ config('const.genre')[$value] }}
                                                    @endforeach
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>期間 </td>
                                                <td>{{ config('const.period')[$input_data['period']] }}</td>
                                            </tr>
                                            <tr>
                                                <td>説明 </td>
                                                <td>{{ $input_data['descript'] }}</td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" name="name" value="{{ $input_data['name'] }}">
                                <input type="hidden" name="email" value="{{ $input_data['email'] }}">
                                <input type="hidden" name="password" value="{{ $input_data['password'] }}">
                                <input type="hidden" name="price" value="{{ $input_data['price'] }}">
                                <input type="hidden" name="authority" value="{{ $input_data['authority'] }}">
                                @foreach ($input_data['genre'] as $key => $value)
                                    <input type="hidden" name="genre[]" value="{{ $value }}">
                                @endforeach
                                <input type="hidden" name="period" value="{{ $input_data['period'] }}">
                                <input type="hidden" name="descript" value="{{ $input_data['descript'] }}">
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
