<?php
session_start();
include("../function/function.php");
include("../template/csrf_token_generate.php");
login_check();

$html_title = '無料から使えるクラウド採用管理、面接システム Smart Interview';
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
</style>
</head>
<body>
<?php include("../template/nav.php") ?>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form-group form-horizontal" action="interviewer_insert.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewer_name">名前※</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="interviewer_name" value="">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="lid">id※</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="lid" value="">
          </div>
        </div>
        <div class="form-group">
        <label class="control-label col-sm-2" for="lpw">pw※</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="lpw" value="">
          </div>
        </div>
        <div class="form-group">
        <label class="control-label col-sm-2" for="interviewer_mail">メールアドレス※</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="interviewer_mail" value="">
          </div>
        </div>
        <div class="form-group">
        <label class="control-label col-sm-2" for="department">部署</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="department" value="">
          </div>
        </div>
        <div class="form-group">
        <label class="control-label col-sm-2" for="title">役職</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="title" value="">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="kanri_flg">管理者※</label>
          <div class="col-sm-10">
            <select class="form-control" name="kanri_flg">
              <option value="0">一般</option>
              <option value="1">管理者</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="life_flg">使用中※</label>
          <div class="col-sm-10">
            <select class="form-control" name="life_flg">
              <option value="0">使用中</option>
              <option value="1">使用していない</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewer_profile">プロフィール</label>
          <div class="col-sm-10">
            <textarea class="form-control" name="interviewer_profile" placeholder="候補者に向けて自己紹介を入力してください。" rows="10"></textarea>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewer_img_file">顔写真</label>
          <div class="col-sm-4">
            <input id="up_image" type="file" class="form-control-file" name="interviewer_img_file" accept=“image/*” capture=“camera”>
          </div>
          <div class="col-sm-6">
            <img id="img_thumbnail" class="img-responsive" src="" alt="">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewer_video">自己紹介動画</label>
          <div class="col-sm-4">
            <input id="up_video" type="file" class="form-control-file" name="interviewer_video_file" accept=“video/*” capture=“camera”>
          </div>
          <div class="col-sm-6">
            <video id="video_thumbnail" class="video-responsive" src="" alt="" preload="none" onclick="this.play()" controls>
          </div>
        </div>
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <div class="text-center">
            <input class="btn btn-default" type="submit" value="登録">
        </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<?php include("../template/footer.html") ?>

<script>
$('#up_image').change(function(){
  if(this.files.length > 0){
    var file = this.files[0];

    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(){
      $('#img_thumbnail').attr('src',reader.result);
    }
  }
});
$('#up_video').change(function(){
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

</body>
</html>
