<?php
session_start();
include("../function/function.php");
login_check();
include("../template/csrf_token_generate.php");

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM interviewer_info where id=:id");
$stmt->bindValue(':id',$_SESSION["user_id"],PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res = $stmt->fetch();
}
$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
.video-responsive{
  max-width: 100%;
  height: auto;
}
</style>
</head>
<body>
<?php include("../template/nav.php") ?>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form-group form-horizontal" action="my_interviewer_update.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewer_name">名前</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="interviewer_name" value="<?= h($res["interviewer_name"]); ?>">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="lid">id</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="lid" value="<?= h($res["lid"]); ?>">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-2"></div>
          <p id="pw-help" class="col-sm-10 help-block">パスワードを変更する場合は入力してください。</p>
          <label class="control-label col-sm-2" for="pw">pw</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="pw" value="" aria-describedby="pw-help">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-2"></div>
          <p id="confirm-help" class="col-sm-10 help-block">確認のためにパスワードを２度入力してください。</p>
          <label class="control-label col-sm-2" for="pw_confirm">pw(確認)</label>
          <div class="col-sm-10">
            <input class="form-control" type="text" name="pw_confirm" value="" aria-describedby="confirm-help">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewer_profile">プロフィール</label>
          <div class="col-sm-10">
            <textarea class="form-control" name="interviewer_profile" placeholder="候補者に向けて自己紹介を入力してください。" rows="10"><?php if($res["interviewer_profile"]){echo h($res["interviewer_profile"]);}?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewer_img_file">顔写真</label>
          <div class="col-sm-4">
            <input id="up_image" type="file" class="form-control-file" name="interviewer_img_file" accept=“image/*” capture=“camera”>
          </div>
            <?= $res["interviewer_img"] ? '<div class="col-sm-6"><img id="img_thumbnail" class="img-responsive" src="'.h($res["interviewer_img"]).'" alt=""></div>' : '<div class="col-sm-6"><img id="img_thumbnail" class="img-responsive" src="" alt=""></div>'?>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewer_video">自己紹介動画</label>
          <div class="col-sm-4">
            <input id="up_video" type="file" class="form-control-file" name="interviewer_video_file" accept=“video/*” capture=“camera”>
          </div>
            <?= $res["interviewer_video"] ? '<div class="col-sm-6"><video id="video_thumbnail" class="video-responsive" src="'.h($res["interviewer_video"]).'" alt="" preload="auto" onclick="this.play()" controls></div>' : '<div class="col-sm-6"><video id="video_thumbnail" class="video-responsive" src="" alt="" preload="none" onclick="this.play()" controls></div>'?>
        </div>
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <div class="text-center">
            <input class="btn btn-default" type="submit" value="更新">
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
