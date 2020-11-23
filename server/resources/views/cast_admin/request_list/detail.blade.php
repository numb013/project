@extends('layouts.app_cast_admin')

@section('content')
<div class="container-fluid">
    <div class="row" style="margin-top: 40px">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    リクエスト詳細
                    <a href="/cast_admin/request_list/list">戻る</a>
                </div>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <tbody>
                                        <tr>
                                            <td style="width: 20%">ニックネーム</td>
                                            <td>{{ $detail["viewer_id"] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%">メッセージネーム</td>
                                            <td>{{ $detail["to_name"] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%">カテゴリー</td>
                                            <td>{{ $detail["category"] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%">カテゴリータイプ</td>
                                            <td>{{ $detail["status"] }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 20%">依頼内容</td>
                                            <td>
                                                {{ $detail["message"] }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.col-lg-8 (nested) -->
                    </div>
                    <div>
                        動画の投稿のルール<br>
                        1:メッセージネームを必ず言う<br>
                        2:依頼内容をよく読む<br>
                        3:１分前後の動画を撮る<br><br>

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
                                {!! Form::label('file', '動画アップロード', ['class' => 'control-label']) !!}
                                {!! Form::file('file') !!}
                            </div>
                            <div class="form-group">
                                {!! Form::submit('アップロード', ['class' => 'btn btn-default']) !!}
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
