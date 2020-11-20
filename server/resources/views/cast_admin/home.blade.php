@extends('layouts.app_cast_admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!

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
                                                <td><a href="/cast_admin/request_list/list?id=1">編集</a></td>
                                            </tr>
                                            <tr>
                                                <td>aaa</td>
                                                <td>bbbb</td>
                                                <td>ccccc</td>
                                                <td><a href="/cast_admin/request_list/list?id=1">編集</a></td>
                                            </tr>
                                            <tr>
                                                <td>aaa</td>
                                                <td>bbbb</td>
                                                <td>ccccc</td>
                                                <td><a href="/cast_admin/request_list/list?id=1">編集</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.col-lg-8 (nested) -->
                        </div>
                    </div>










<video src="https://d3el26csp1xekx.cloudfront.net/v/wm-KH4D17A8X.mp4" controls></video>

{!! Form::open(['url' => 'cast_admin/request_list/movie_upload', 'method' => 'post', 'files' => true]) !!}
    {{--成功時のメッセージ--}}
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    {{-- エラーメッセージ --}}
    @if ($errors->any())
        <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        </div>
    @endif
    <div class="form-group">
        {!! Form::label('file', 'ファイルアップロード', ['class' => 'control-label']) !!}
        {!! Form::file('file') !!}
    </div>
    <div class="form-group">
        {!! Form::submit('アップロード', ['class' => 'btn btn-default']) !!}
    </div>
{!! Form::close() !!}
 
{!! Form::open(['url' => '/download', 'method' => 'post', 'files' => true]) !!}
    <div class="form-group">
        <input type="hidden" name="userid" value="12345">
        <input name="loadfile" type="hidden" value="tomosoft.vi">
        {!! Form::submit('ダウンロード', ['class' => 'btn btn-default']) !!}
    </div>
{!! Form::close() !!}



                </div>
            </div>
        </div>
    </div>
</div>
@endsection
