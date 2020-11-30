@extends('layouts.app_cast_admin')

@section('content')
<div class="container-fluid">
    <div class="row" style="margin-top: 40px">
        <div class="col-lg-12">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab-default-1">
                    <div class="col-lg-12">
                        <form role="form" method="post" action="{{action('CastController@adminConfirm')}}" class="form" enctype="multipart/form-data">
                                <div class="chat-body clearfix">
                                    <p style="font-weight: bold; font-size: 14px;">プロフィール画像</p>
                                    <div class="form-group" id="attachment" style="margin-top:15px;">
                                        <label>
                                            <input type="file" name="file" class="fileinput" id="myImage" accept="image/*">ファイルを添付する
                                        </label>
                                    </div>
                                    <p>ファイルを選択すると、下にプレビューを表示します。</p>
                                    <div style="display:inline-block;min-width:200px; min-height:200px; border:5px dashed #eee; padding:10px;">
                                        <img id="preview">
                                        <img src="{{ asset('images/boy.png') }}" class="img-circle"/>
                                        <p>
                                            <script>
                                            $('#myImage').on('change', function (e) {var reader = new FileReader();reader.onload = function (e) {$("#preview").attr('src', e.target.result);}; reader.readAsDataURL(e.target.files[0]);});
                                            </script>
                                        </p>
                                    </div>
                                </div>

                            <input class="btn btn-primary" type="submit" value="送信" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #preview {
        width: 60%;
    }
    #attachment label {
     /* ボタン部分の見た目（任意） */
     display: inline-block;
     position: relative;
     background: #222323;
     color:#fff;
     font-weight: initial;
     padding: 10px 18px;
     border-radius: 4px;
     transition: all 0.3s;
    }
    #attachment label:hover {
     background: #ccc;
      color:#000;
     transition: all 0.4s;
    }
    #attachment label input {
     /* 今回のポイント */
     position: absolute;
     left:0;
     top:0;
     opacity: 0;
     width: 100%;
     height: 100%;
    }
    #attachment .filename {
     font-weight: 16px;
     margin:0 0 0 10px;
    }
</style>

<script>
  var elem = document.getElementById('range');
  var target = document.getElementById('value');
  var rangeValue = function (elem, target) {
    return function(evt){
      target.innerHTML = elem.value;
    }
  }
  elem.addEventListener('input', rangeValue(elem, target));


  $('#myImage').on('change', function (e) {
      var reader = new FileReader();
      reader.onload = function (e) {
          $("#preview").attr('src', e.target.result);
      }
      reader.readAsDataURL(e.target.files[0]);
  });

</script>
@endsection
