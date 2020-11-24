@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">キャスト一覧</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    作成<a href="/admin/cast/list">戻る</a>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <form role="form" method="post" action="{{action('CastController@adminSearch')}}" class="form">
                            {{ csrf_field() }}
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>名前</label>
                                    <input class="form-control" name="name" placeholder="名前" value="{{ old('name') }}">
                                </div>

                                <div class="form-group">
                                    <label>email</label>
                                    <input class="form-control" name="email" placeholder="メールアドレス" value="{{ old('email') }}">
                                </div>

                                <div class="form-group">
                                    <label>password</label>
                                    <input class="form-control" name="password" placeholder="パスワード" value="{{ old('password') }}">
                                </div>

                                
                                <div class="slider-container" style="width: 90%; display: grid; margin: 0 auto;padding-top: 50px;">
                                    <label>料金</label>
                                    <input type="text" id="slider" class="slider" />
                                </div>

                                <div class="form-group">
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
                                </div>

                            </div>
                            <div class="col-lg-6">

                                <div data-toggle='buttons' id='menu' class="form-group">
                                    <label>ジャンル</label>
                                    @foreach (config('const.genre') as $key => $value)
                                        <label class='btn btn-default' for="{{ 'check'.$key }}" style="margin: 2px;">
                                        <input id="{{ 'check'.$key }}" type="checkbox" name="checkbox[]" value="{{ $value }}"{{ is_array(old("checkbox")) && in_array("$value", old("checkbox"), true)? ' checked' : '' }}>{{ $value }}
                                        </label>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label>権限</label>
                                    <select name="authority" class="form-control">
                                        @foreach (config('const.authority') as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>期間</label>
                                    <select name="period" class="form-control">
                                        @foreach (config('const.period') as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>説明</label>
                                    <textarea name="descript" class="form-control">{{ old('descript') }}</textarea>
                                </div>
                                <input class="btn btn-primary" type="submit" value="送信" />
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
    </div>
</div>
<link rel="stylesheet" href="{{ asset('css/rSlider.min.css') }}">
<script src="{{ asset('js/rSlider.min.js') }}"></script>
<script>
    (function () {
        'use strict';
        var init = function () {
            var slider = new rSlider({
                target: '#slider',
                values: {min: 0, max: 100000},
                step: 300,
                range: false,
                set: [0, 3000],
                scale: true,
                labels: false,
                onChange: function (vals) {
                    console.log(vals);
                }
            });
        };
        window.onload = init;
    })();
</script>
@endsection
