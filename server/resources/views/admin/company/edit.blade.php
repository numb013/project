@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">事務所編集</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    作成<a href="/admin/company/list">戻る</a>
                </div>
                <div class="panel-body">
                    <div class="row">
                        {{-- エラーメッセージ --}}
                        @if ($errors->any())
                            <div class="alert alert-danger" style="margin: 20px;">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form role="form" method="post" action="{{action('CompanyController@adminUpdate')}}" class="form">
                            {{ csrf_field() }}
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>名前</label>
                                    <input class="form-control" name="name" placeholder="名前" value="{{ $detail['name'] }}">
                                </div>

                                <div class="form-group">
                                    <label>email</label>
                                    <input class="form-control" name="email" placeholder="メールアドレス" value="{{ $detail['email'] }}">
                                </div>

                                <div class="form-group">
                                    <label>password</label>
                                    <input class="form-control" name="password" placeholder="パスワード" value="{{ old('password') }}">
                                </div>
                                <div class="form-group">
                                    <label>権限</label>
                                    <select name="authority" class="form-control">
                                        @foreach (config('const.authority') as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="form-group slider-container" style="width: 100%; display: grid; margin: 0 auto;">
                                    <label>料金</label>
                                    <?php
                                        $price = 0;
                                        if (!empty($detail['price'])) {
                                            $price = $detail['price'];
                                        }                                    
                                    ?>
                                    <input type="range" name="price" id="range" min="0" max="10000" step="100" value="{{ $price }}" class="form-control">
                                    <p><span id="value">{{ $price }}</span><span>コイン</span></p>
                                </div>

                                <div class="form-group">
                                    <div class="form-group">
                                        {!! Form::label('file', '動画アップロード', ['class' => 'control-label']) !!}
                                        {!! Form::file('file') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">

                                <div class="form-group">
                                    <label>所属事務所</label>
                                    <select name="authority" class="form-control">
                                        <option value="0">未選択</option>
                                        @foreach ($company as $key => $value)
                                            <option value="{{ $value['id'] }}">{{ $value['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div data-toggle='buttons' id='menu' class="form-group">
                                    <label>ジャンル</label>
                                    @foreach (config('const.genre') as $key => $value)
                                        <label class='btn btn-default' for="{{ 'check'.$key }}" style="margin: 2px;">
                                        <input id="{{ 'check'.$key }}" type="checkbox" name="genre[]" value="{{ $key }}"{{ is_array(old("checkbox")) && in_array("$value", old("checkbox"), true)? ' checked' : '' }}>{{ $value }}
                                        </label>
                                    @endforeach
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
                                    <textarea name="descript" class="form-control">{{ $detail['descript'] }}</textarea>
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
<script>
  var elem = document.getElementById('range');
  var target = document.getElementById('value');
  var rangeValue = function (elem, target) {
    return function(evt){
      target.innerHTML = elem.value;
    }
  }
  elem.addEventListener('input', rangeValue(elem, target));
</script>
@endsection
