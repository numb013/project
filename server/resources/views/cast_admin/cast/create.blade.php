@extends('layouts.app_cast_admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">リクエスト詳細</div>
                <div class="panel-body">
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped">
                                        <tbody>
                                            <tr>
                                                <td style="width: 20%">ニックネーム</td>
                                                <td>aaa</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 20%">メッセージネーム</td>
                                                <td>bbbb</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 20%">カテゴリー</td>
                                                <td>お祝い</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 20%">カテゴリータイプ</td>
                                                <td>受験合格</td>
                                            </tr>
                                            <tr>
                                                <td style="width: 20%">依頼内容</td>
                                                <td>
                                                    以下 Laravel 5.5 実施時の情報になります。
                                                    インターネット上で写真や情報をやりとりできるような
                                                    『Webアプリ』や『Webサービス』では、
                                                    使う人(ユーザー)がログインして、
                                                    自分専用の管理画面から記事を投稿したり、写真をアップできるようになっています。
                                                    PHPのてんこもりフレームワーク『Laravel(ララベル)』では、
                                                    使う人(ユーザー)がログインだけの機能であれば、
                                                    ものの1分でつくる事ができるようになっています。
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.col-lg-8 (nested) -->
                        </div>
                    </div>





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
@endsection
