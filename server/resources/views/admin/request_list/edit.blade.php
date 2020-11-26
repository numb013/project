@extends('layouts.app_admin')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">リクエスト</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i>
                    未完了リクエスト
                    <a href="/admin/request_list/list">戻る</a>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <tbody>
                                        <tr>
                                            <td>キャスト名 </td>
                                            <td>{{ $detail['cast_name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>視聴者名 </td>
                                            <td>{{ $detail['viewer_name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>カテゴリー </td>
                                            <td>{{ $detail['category'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>宛名</td>
                                            <td>{{ $detail['to_name'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>メッセージ</td>
                                            <td>{{ $detail['message'] }}</td>
                                        </tr>
                                        <tr>
                                            <td>動画</td>
                                            <td>
                                                <video src="https://d3el26csp1xekx.cloudfront.net/v/wm-KH4D17A8X.mp4" controls></video>

                                            <form role="form" method="post" action="{{action('RequestListController@adminUpdate')}}" class="form">
                                                {{ csrf_field() }}
                                                    <div class="form-group">
                                                        <label>状態</label>
                                                        <label class="radio-inline">
                                                            <?php
                                                                $checked = "";
                                                                if ($detail['status'] == 2) {
                                                                    $checked = "checked";
                                                                }
                                                            ?>
                                                            <input type="radio" name="status" value="2" <?php echo $checked; ?>>再提出
                                                        </label>
                                                        <label class="radio-inline">
                                                            <?php
                                                                $checked = "";
                                                                if ($detail['status'] == 3) {
                                                                    $checked = "checked";
                                                                }
                                                            ?>
                                                            <input type="radio" name="status" value="3" <?php echo $checked; ?>>完了
                                                        </label>
                                                        <label class="radio-inline">
                                                            <?php
                                                                $checked = "";
                                                                if ($detail['status'] == 4) {
                                                                    $checked = "checked";
                                                                }
                                                            ?>
                                                            <input type="radio" name="status" value="4" <?php echo $checked; ?>>その他
                                                        </label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>再提出の場合のメッセージ</label>
                                                        @if($errors->has('check_memo'))
                                                            {{ $errors->first('check_memo') }}<br>
                                                        @endif 
                                                        <textarea name="descript" class="form-control">{{ old('check_memo') }}</textarea>
                                                    </div>
                                                    <input type="hidden" name="id" value="{{ $detail['id'] }}">
                                                    <input class="btn btn-primary" type="submit" value="送信" />
                                            </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.col-lg-8 (nested) -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.panel-body -->
            </div>
        </div>
    </div>
</div>
@endsection
