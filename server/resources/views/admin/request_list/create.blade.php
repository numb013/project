@extends('layouts.app_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">リクエスト作成</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    作成<a href="/admin/request_list/list">戻る</a>
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



                        <form role="form" method="post" action="{{action('RequestListController@adminComplete')}}" class="form">
                            {{ csrf_field() }}
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>ユーザーID</label>
                                    <input class="form-control" name="user_id" placeholder="user_id" value="{{ old('user_id') }}">
                                </div>

                                <div class="form-group">
                                    <label>cast_id</label>
                                    <input class="form-control" name="cast_id" placeholder="cast_id" value="{{ old('cast_id') }}">
                                </div>

                                <div class="form-group">
                                    <label>to_name</label>
                                    <input class="form-control" name="to_name" placeholder="to_name" value="{{ old('to_name') }}">
                                </div>
                                <div class="form-group">
                                    <label>status</label>
                                    <select name="status" class="form-control">
                                        @foreach (config('const.authority') as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                            </div>
                            <div class="col-lg-6">

                                <div data-toggle='buttons' id='menu' class="form-group">
                                    <label>ジャンル</label>
                                    @foreach (config('const.genre') as $key => $value)
                                        <label class='btn btn-default' for="{{ 'check'.$key }}" style="margin: 2px;">
                                        <input id="{{ 'check'.$key }}" type="checkbox" name="genre[]" value="{{ $key }}"{{ is_array(old("checkbox")) && in_array("$value", old("checkbox"), true)? ' checked' : '' }}>{{ $value }}
                                        </label>
                                    @endforeach
                                </div>


                                <div class="form-group">
                                    <label>説明</label>
                                    <textarea name="request_detail" class="form-control">{{ old('request_detail') }}</textarea>
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
