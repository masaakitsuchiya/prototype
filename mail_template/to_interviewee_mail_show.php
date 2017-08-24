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
.mail_text{
  background:#fff;
  padding:20px;
  border-radius:5px;
}

.button_area{
  margin-top:60px;
}

</style>
</head>
<body>

<!-- Head[Start] -->
<?php include("../template/nav.php") ?>


<h2 class="text-center">候補者宛メールテキスト</h3>

<div class="container">
  <div calss="row">

  </div>
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="text-center button_area">
        <a type="button" class="btn btn-default" href="to_interviewee_mail_detail.php">編集</a>
      </div>
      <form class="form-group form-horizontal" action="#" method="">
        <div class="form-group">
          <label class="control-label col-sm-2" for="video_interview_invite">ウェブ面接招待メール</label>
          <div class="col-sm-10">
            <p id="video_interview_invite" class="form-control-static mail_text" name="video_interview_invite"><?= h($res["video_interview_invite"]); ?></p>
          </div>
          <!-- <script>
          CKEDITOR.replace('nonvideo_interview_invite');
          </script> -->
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="nonvideo_interview_invite">通常面接招待メール</label>
          <div class="col-sm-10">
            <p id="nonvideo_interview_invite" class="form-control-static mail_text" name="nonvideo_interview_invite"><?= h($res["nonvideo_interview_invite"]); ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="nonvideo_interview_invite">面接確定メール</label>
          <div class="col-sm-10">
            <p id="interview_time_fix" class="form-control-static mail_text" name="interview_time_fix"><?= h($res["interview_time_fix"]); ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="re_video_interview_adjusting">ウェブ面接再調整の案内</label>
          <div class="col-sm-10">
            <p id="re_video_interview_adjusting" class="form-control-static mail_text" name="re_video_interview_adjusting"><?= h($res["re_video_interview_adjusting"]); ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="re_non_video_interview_adjusting">ウェブ面接再調整の案内</label>
          <div class="col-sm-10">
            <p id="re_non_video_interview_adjusting" class="form-control-static mail_text" name="re_non_video_interview_adjusting"><?= h($res["re_non_video_interview_adjusting"]); ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="cancel_while_adjusting">面接キャンセル通知(調整中)</label>
          <div class="col-sm-10">
            <p id="cancel_while_adjusting" class="form-control-static mail_text" name="cancel_while_adjusting"><?= h($res["cancel_while_adjusting"]); ?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="cancel_after_fixed">面接キャンセル通知（日程確定後）</label>
          <div class="col-sm-10">
            <p id="cancel_after_fixed" class="form-control-static mail_text" name="cancel_after_fixed"><?= h($res["cancel_after_fixed"]); ?></p>
          </div>
        </div>
        <div class="text-center C">
          <a type="button" class="btn btn-default" href="to_interviewee_mail_detail.php">編集</a>
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
