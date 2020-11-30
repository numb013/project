@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">視聴者編集</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    作成<a href="/admin/viewer/list">戻る</a>
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

                        <form role="form" method="post" action="{{action('ViewerController@adminUpdate')}}" class="form">
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
                                    <select name="status" class="form-control">
                                        @foreach (config('const.authority') as $key => $value)
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
