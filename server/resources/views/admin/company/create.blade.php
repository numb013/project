@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">事務所作成</h1>
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

                        <form role="form" method="post" action="{{action('CompanyController@adminConfirm')}}" class="form">
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
                                    <label>住所</label>
                                    <input class="form-control" name="address" placeholder="住所" value="{{ old('address') }}">
                                </div>
                                <div class="form-group">
                                    <label>電話</label>
                                    <input class="form-control" name="tel" placeholder="電話" value="{{ old('tel') }}">
                                </div>
                                <div class="form-group">
                                    <label>HP</label>
                                    <input class="form-control" name="hp_url" placeholder="hp_url" value="{{ old('hp_url') }}">
                                </div>
                                <div data-toggle='buttons' id='menu' class="form-group">
                                    <label>ジャンル</label>
                                    @foreach (config('const.genre') as $key => $value)
                                        <label class='btn btn-default' for="{{ 'check'.$key }}" style="margin: 2px;">
                                        <input id="{{ 'check'.$key }}" type="checkbox" name="genre[]" value="{{ $key }}"{{ is_array(old("checkbox")) && in_array("$value", old("checkbox"), true)? ' checked' : '' }}>{{ $value }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-lg-6">

                                <div class="form-group">
                                    <label>担当者名</label>
                                    <input class="form-control" name="contact_name" placeholder="担当者名" value="{{ old('contact_name') }}">
                                </div>

                                <div class="form-group">
                                    <label>権限</label>
                                    <select name="accouont_type" class="form-control">
                                        @foreach (config('const.authority') as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>                                
                                <div class="form-group">
                                    <label>転送名</label>
                                    <input class="form-control" name="transfer_name" placeholder="転送名" value="{{ old('contact_mail') }}">
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
