<?php
session_start();
include("../function/function.php");
if(isset($_GET["plan"])){
$plan = $_GET["plan"];
}else{
$plan = 0;
}
$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head_for_login.php") ?>
<style>


h3{
  margin-top:100px;
  margin-bottom:30px;
}
p.control-label{
  font-weight:bold;
}
.form-control{
  margin-bottom:30px;
}

.navbar-default {
background-color: rgba(51,122,183,0.8);
border-color:;

}
.navbar-default .navbar-nav > li > a {
color: #ffffff;
}
.navbar-default .navbar-brand {
color: #ffffff;
}
.navbar-right{
  padding-right:10px;
}

footer{
background-color: rgba(51,122,183,1);
  height:50px;
  width:100%;
}
.footer_text{
  padding-top:10px;
  padding-bottom:10px;
  color:#fff;
  font-size:1.2em
}

</style>
</head>
<body>
  <nav class="navbar navbar-default bg-primary navbar-fixed-top">
  <div class="navbar-header">
    <button class="navbar-toggle" data-toggle="collapse" data-target=".target">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#" style="font-family:'Cabin+Condensed',serif;"><i class="fa fa-id-card" aria-hidden="true"></i> InterFree</a>
  </div>

  <div class="collapse navbar-collapse target">
    <ul class="nav navbar-nav navbar-left">
    </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="login.php" role="button">戻る</a>
        </li>
      </ul>

  </div>
  </nav>

<h3 class="text-center">新規登録</h3>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form-group form-horizontal" action="create_insert.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label class="control-label col-sm-3" for="corp_name">会社名</label>
          <div class="col-sm-9">
            <input type="text" name="corp_name" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-3" for="corp_name_kana">会社名カナ</label>
          <div class="col-sm-9">
            <input type="text" name="corp_name_kana" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-3" for="corp_url">会社サイトURL</label><div class="col-sm-9"><input type="text" class="form-control" name="corp_url"></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-3" for="corp_address">会社住所</label><div class="col-sm-9"><input type="text" class="form-control" name="corp_address" value=""></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-3" for="corp_tel">会社電話番号</label><div class="col-sm-9"><input type="text" class="form-control" name="corp_tel" value=""></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-3" for="admin_user">お名前（管理ユーザー）</label><div class="col-sm-9"><input type="text" class="form-control" name="admin_user" value=""></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-3" for="admin_user_kana">ふりがな（管理ユーザー）</label><div class="col-sm-9"><input type="text" class="form-control" name="admin_user_kana" value=""></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-3" for="admin_user_address">メールアドレス</label><div class="col-sm-9"><input type="text" class="form-control" name="admin_user_address" value=""></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-3" for="admin_user_address_confirm">メールアドレス(確認）
          </label><div class="col-sm-9"><input type="text" class="form-control" name="admin_user_address_confrim" value=""></div>
        </div>

        <div class="form-group">
          <p class="control-label col-sm-3">プラン選択</p>
          <div class="radio_area col-sm-9">
            <div class="radio">
              <label><input type="radio" name="plan" value="1" <?php if($plan==1){echo "checked";}?>>無料プラン</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="plan" value="2" <?php if($plan==2){echo "checked";}?>>ベーシックプラン</label>
            </div>
            <div class="radio">
              <label><input type="radio" name="plan" value="3" <?php if($plan==3){echo "checked";}?>>エンタープライズ</label>
            </div>
          </div>
        </div>

        <div class="text-center">
          <input class="btn btn-default" type="submit" value="登録" disabled>
        </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<!-- Main[End] -->
<?php include("../template/footer.html") ?>
</body>
<script>
$('#catch_photo_f').change(function(){
  if(this.files.length > 0){
    var file = this.files[0];

    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(){
      $('#catch_photo_thumbnail').attr('src',reader.result);
    }
  }
});
$('#up_pdf_f').change(function(){
  if(this.files.length > 0){
    var file = this.files[0];
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(){
      $('#pdf_thumbnail').attr('data',reader.result);
    }
  }
});
$('#company_video').change(function(){
  if(this.files.length > 0){
    var file = this.files[0];
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(){
      $('#video_thumbnail').attr('src',reader.result);
    }
  }
});
</script>
</html>
