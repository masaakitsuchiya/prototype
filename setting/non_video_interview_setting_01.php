<?php
session_start();
include("../function/function.php");
login_check();

$_SESSION["interviewee_id"] = $_GET["target_interviewee_id"];
$_SESSION["interview_type_num"] = $_GET["interview_type_num"];

$interview_type_str = interview_type($_SESSION["interview_type_num"]);

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

$stmt = $pdo->prepare("SELECT * FROM interviewee_info where id= :interviewee_id");
$stmt->bindValue(':interviewee_id', $_SESSION["interviewee_id"], PDO::PARAM_INT);
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
.container{
  margin-top:30px;
  margin-bottom:30px;
}
</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">通常面接予約</h3>
<!-- 一覧に戻るボタン -->
<?php include("../template/back_to_interviewee_select.php"); ?>
<!-- プログレス表示 -->
<div class="container">
<?php $_GET['progress']=1;include("../template/interview_setting_progress.php"); ?>
</div>

<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form-group form-horizontal" action="non_video_interview_setting_02.php" method="post">
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewee_name">候補者名</label><div class="col-sm-10"><p class="form-control-static"><?= h($res["interviewee_name"]); ?></p></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interview_type">選考ステップ</label>
          <div class="col-sm-10"><p class="form-control-static"><?= h($interview_type_str); ?></p></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewer_id">選考担当者</label>
          <div class="col-sm-10">
            <span class="help-block">面接に出席する人を選択してください。複数名出席する場合はcommond(Mac)/ctrol(win)を押しながら選択してください。</span>
            <select class="form-control" name="interviewer_id[]" multiple>
              <?= $view ?>
            </select>
          </div>
        </div>
        <div class="text-center">
          <a class="btn btn-default" href="interview01_setting.php?interview_type_num=1&target_interviewee_id=<?php echo($_SESSION["interviewee_id"]);?>">戻る</a>
          &emsp;
          <input class="btn btn-info" type="submit" value="次へ">
        </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>

<?php include("../template/footer.html") ?>

</body>
</html>
