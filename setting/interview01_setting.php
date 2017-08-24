<?php
session_start();
include("../function/function.php");
login_check();

$interviewee_id = $_GET["target_interviewee_id"];
$interview_type_num = $_GET["interview_type_num"];
$interview_type_str = interview_type($interview_type_num);

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM interviewer_info");
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view .='<option value="'.h($result["id"]).'">'.h($result["interviewer_name"]).'</option>';
  }
}

$stmt = $pdo->prepare("SELECT * FROM interviewee_info where id =$interviewee_id");
$status2 = $stmt->execute();

//３．データ表示
if($status2==false){
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
/*html,body{
  height: 100%;
}*/
.container{
  margin-bottom:20px;
}
.mb_30{
  margin-bottom:30px;
}
</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">面接設定</h3>
<div class="container mb_30">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="text-right back_to_select">
        <a class="btn btn-default" href="interviewee_select.php">候補者一覧に戻る</a>
      </div>
      <form class="form-group form-horizontal mb_30" action="interview_insert.php" method="post">
        <div class="form-group">
          <div class="col-sm-3"></div>
          <label class="control-label col-sm-3" for="interviewee_name">候補者名</label><div class="col-sm-3"><p class="form-control-static"><?= h($res["interviewee_name"]); ?></p></div>
          <div class="col-sm-3"></div>
        </div>
        <div class="form-group">
          <div class="col-sm-3"></div>
          <label class="control-label col-sm-3" for="interview_type">選考ステップ</label>
          <div class="col-sm-3"><p class="form-control-static"><?= h($interview_type_str); ?></p></div>
          <div class="col-sm-3"></div>
        </div>
          <input type="hidden" name="interviewee_id" value="<?= h($interviewee_id); ?>">
      </form>
      <div class="text-center">
        <a class="btn btn-info" href="video_interview_setting_01.php?target_interviewee_id=<?= h($interviewee_id); ?>&interview_type_num=<?= h($interview_type_num); ?> " disabled>ビデオ面接予約</a>&emsp;
        <a class="btn btn-primary" href="non_video_interview_setting_01.php?target_interviewee_id=<?= h($interviewee_id); ?>&interview_type_num=<?= h($interview_type_num); ?>">通常面接予約</a>&emsp;　
        <a class="btn btn-default" href="#" disabled>日程直接入力</a>
      </div>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>

<?php include("../template/footer.html") ?>

</body>
</html>
