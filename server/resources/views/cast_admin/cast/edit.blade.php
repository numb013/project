@extends('layouts.app_cast_admin')

@section('content')
<div class="container-fluid">
    <div class="row" style="margin-top: 40px">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    プロフィール編集フォーム
                    <a href="/cast_admin/">戻る</a>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <form method="post" action="{{action('CastController@castAdminCreate')}}" class="form">
                            {{ csrf_field() }}
                            <div class="col-lg-6">
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
                                <div class="form-group">
                                    <label>名前</label>
                                    <input class="form-control" name="name" placeholder="名前" value="{{ old('name') }}">
                                </div>
                                <div class="form-group slider-container" style="width: 100%; display: grid; margin: 0 auto;">
                                    <label>料金</label>
                                    <input type="range" name="price" id="range" min="0" max="10000" step="100" value="0" class="form-control">
                                    <p><span id="value">0</span><span>コイン</span></p>
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
                            </div>
                            <div class="col-lg-6">
                                <div data-toggle='buttons' id='menu' class="form-group">
                                    <label>ジャンル</label>
                                    @foreach (config('const.genre') as $key => $value)
                                        <label class='btn btn-default' for="{{ 'check'.$key }}" style="margin: 2px;">
                                        <input  class="form-control" id="{{ 'check'.$key }}" type="checkbox" name="genre[]" value="{{ $value }}"{{ is_array(old("checkbox")) && in_array("$value", old("checkbox"), true)? ' checked' : '' }}>{{ $value }}
                                        </label>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-primary">送信</button>
                                <button type="reset" class="btn btn-default">リセット</button>
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
</script>@endsection
