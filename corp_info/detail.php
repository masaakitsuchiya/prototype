<?php
session_start();
include("../function/function.php");
login_check();
kanri_check();

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM corp_info where id=:id");
$stmt->bindValue(':id',1,PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res_corp_info = $stmt->fetch();
}
$html_title = '無料から使えるクラウド採用管理、面接システム InterFree';
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<script src="../ckeditor/ckeditor.js"></script>
<style>
h3{
  margin-bottom:30px;
}
.video-responsive{
  max-width: 100%;
  height: auto;
}
.pdf_thumbnail{
  max-width: 100%;
  height: auto;
}
</style>
</head>
<body>

<?php include("../template/nav.php") ?>

<h3 class="text-center">候補者向け会社紹介情報入力</h3>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form-group form-horizontal" action="update.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label class="control-label col-sm-2" for="info_text">会社名</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="corp_name" value="<?php if($res_corp_info["corp_name"]){echo h($res_corp_info["corp_name"]); } ?>" maxlength = "100">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="info_text">会社紹介テキスト</label>
          <div class="col-sm-10">
            <textArea id="info_text" class="form-control" name="info_text" rows="10" cols="80"><?php if($res_corp_info["info_text"]){echo hd($res_corp_info["info_text"]); }?></textArea>
          </div>
          <script>
          CKEDITOR.replace('info_text');
          </script>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="corp_url">会社サイトURL</label><div class="col-sm-10"><input type="text" class="form-control" name="corp_url" value="<?php if($res_corp_info["corp_url"]){echo h($res_corp_info["corp_url"]); }?>"></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="address">会社住所</label><div class="col-sm-10"><input type="text" class="form-control" name="address" value="<?php if($res_corp_info["address"]){echo h($res_corp_info["address"]); }?>"></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="corp_mail">問い合わせ先メールアドレス</label><div class="col-sm-10"><input type="text" class="form-control" name="corp_mail" value="<?php if($res_corp_info["corp_mail"]){echo h($res_corp_info["corp_mail"]); }?>"></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="tel">会社電話番号</label><div class="col-sm-10"><input type="text" class="form-control" name="tel" value="<?php if($res_corp_info["tel"]){echo h($res_corp_info["tel"]); }?>"></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="profile_flg">面接官プロフィール</label>
          <div class="col-sm-10">
            <label class="radio-inline">
            <input type="radio" name="profile_flg" value="0" <?php if($res_corp_info["profile_flg"] == 0){echo "checked"; } ?> aria-describedby="profile-help">非公開
            </label>
            <label class="radio-inline">
            <input type="radio" name="profile_flg" value="1" <?php if($res_corp_info["profile_flg"] == 1){echo "checked"; } ?> aria-describedby="profile-help">公開
            </label>
             <p id="profile-help" class="help-block">候補者に面接官のプロフィールを公開するか否か</p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="catch_photo_f">メイン画像</label>
          <div class="col-sm-4"><input id="catch_photo_f" type="file" class="form-control" name="catch_photo_f" accept=“image/*” capture=“camera”></div>
          <div class="col-sm-6"><img id="catch_photo_thumbnail" class="img-responsive" src="<?php if($res_corp_info["catch_photo"]){echo h($res_corp_info["catch_photo"]); }?>"></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="up_pdf_f">紹介資料（pdf形式)</label>
          <div class="col-sm-4"><input id="up_pdf_f" type="file" class="form-control" name="up_pdf_f" accept=application/pdf”></div>
          <div class="col-sm-6 pdf_thumbnail"><object id="pdf_thumbnail" data="<?php if($res_corp_info["up_pdf"]){echo h($res_corp_info["up_pdf"]); }?>" type="application/pdf" width="100%" height="auto"></object></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="company_video">紹介動画</label>
          <div class="col-sm-4"><input id="company_video" type="file" class="form-control" name="company_video_f" accept=“video/*” capture=“camera”>
          </div><div class="col-sm-6"><video id="video_thumbnail" class="video-responsive" src="<?php if($res_corp_info["company_video"]){echo h($res_corp_info["company_video"]); }?>" alt="" preload="auto" onclick="this.play()" controls></div>
        </div>
        <div class="text-center">
          <input class="btn btn-default" type="submit" value="更新">
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
