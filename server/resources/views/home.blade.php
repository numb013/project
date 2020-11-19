@extends('layouts.app')

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


<video src="../../storage/app/public/files/2019-09-27 T00-40-43.mp4" controls></video>

{!! Form::open(['url' => '/request_list/movie_upload', 'method' => 'post', 'files' => true]) !!}
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
