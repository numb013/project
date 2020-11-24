@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">お知らせ作成</h1>
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
                            <div class="col-lg-12">
                                <div data-toggle='buttons' id='menu' class="form-group">
                                    <label>送信ユーザー</label>
                                    @foreach (config('const.send_user_type') as $key => $value)
                                        <label class='btn btn-default' for="{{ 'check'.$key }}" style="margin: 2px;">
                                        <input id="{{ 'check'.$key }}" type="checkbox" name="checkbox[]" value="{{ $value }}"{{ is_array(old("checkbox")) && in_array("$value", old("checkbox"), true)? ' checked' : '' }}>{{ $value }}
                                        </label>
                                    @endforeach
                                </div>
                                <div class="form-group">
                                    <label>タイトル</label>
                                    <input class="form-control" name="title" placeholder="タイトル" value="{{ old('title') }}">
                                </div>
                                <div class="form-group">
                                    <label>説明</label>
                                    <textarea name="message" class="form-control">{{ old('message') }}</textarea>
                                </div>
                                <input class="btn btn-primary" type="submit" value="確認ページ" />
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
