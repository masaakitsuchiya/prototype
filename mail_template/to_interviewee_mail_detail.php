<?php
session_start();
include("../function/function.php");
login_check();

$pdo = db_con();

$stmt = $pdo->prepare("SELECT * FROM default_mail_text where mail_text_id=:id");
$stmt->bindValue(':id',1,PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  queryError($stmt);
}else{
  $res = $stmt->fetch();
}
$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<script src="../ckeditor/ckeditor.js"></script>
<style>
.form-group{
  margin-top:60px;
}
</style>
</head>
<body>

<!-- Head[Start] -->
<?php include("../template/nav.php") ?>


<h2 class="text-center">候補者宛メールテキスト</h3>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form-group form-horizontal" action="to_interviewee_mail_update.php" method="post">
        <div class="form-group">
          <label class="control-label col-sm-2" for="video_interview_invite">ウェブ面接招待メール</label>
          <div class="col-sm-10">
            <textArea id="video_interview_invite" class="form-control" name="video_interview_invite" rows="10" cols="80" required ><?= h($res["video_interview_invite"]); ?></textArea>
          </div>
          <!-- <script>
          CKEDITOR.replace('nonvideo_interview_invite');
          </script> -->
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="nonvideo_interview_invite">通常面接招待メール</label>
          <div class="col-sm-10">
            <textArea id="nonvideo_interview_invite" class="form-control" name="nonvideo_interview_invite" rows="10" cols="80" required ><?= h($res["nonvideo_interview_invite"]); ?></textArea>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="nonvideo_interview_invite">面接確定メール</label>
          <div class="col-sm-10">
            <textArea id="interview_time_fix" class="form-control" name="interview_time_fix" rows="10" cols="80" required ><?= h($res["interview_time_fix"]); ?></textArea>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="re_video_interview_adjusting">ウェブ面接再調整の案内</label>
          <div class="col-sm-10">
            <textArea id="re_video_interview_adjusting" class="form-control" name="re_video_interview_adjusting" rows="10" cols="80"  required ><?= h($res["re_video_interview_adjusting"]); ?></textArea>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="re_non_video_interview_adjusting">ウェブ面接再調整の案内</label>
          <div class="col-sm-10">
            <textArea id="re_non_video_interview_adjusting" class="form-control" name="re_non_video_interview_adjusting" rows="10" cols="80"  required ><?= h($res["re_non_video_interview_adjusting"]); ?></textArea>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="cancel_while_adjusting">面接キャンセル通知(調整中)</label>
          <div class="col-sm-10">
            <textArea id="cancel_while_adjusting" class="form-control" name="cancel_while_adjusting" rows="10" cols="80" required ><?= h($res["cancel_while_adjusting"]); ?></textArea>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="cancel_after_fixed">面接キャンセル通知（日程確定後）</label>
          <div class="col-sm-10">
            <textArea id="cancel_after_fixed" class="form-control" name="cancel_after_fixed" rows="10" cols="80" required ><?= h($res["cancel_after_fixed"]); ?></textArea>
          </div>
        </div>
        <div class="text-center">
          <a type="button" class="btn btn-default" href="to_interviewee_mail_show.php">戻る</a>
          <input class="btn btn-primary" type="submit" value="修正">
        </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<!-- Main[End] -->
<?php include("../template/footer.html") ?>
</body>
</html>
