<?php
session_start();
include("../function/function.php");
login_check();

$interviewee_id = $_GET["target_interviewee_id"];

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

$stmt = $pdo->prepare("SELECT * FROM interviewee_info where id=$interviewee_id");
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
  </style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">面接設定</h3>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="text-right back_to_select">
        <a class="btn btn-default" href="interviewee_select.php">候補者一覧に戻る</a>
      </div>
      <form class="form-group form-horizontal" action="interview_insert.php" method="post">
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewee_name">候補者名</label><div class="col-sm-10"><p class="form-control-static"><?= h($res["interviewee_name"]); ?></p></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interview_type">選考ステップ</label>
          <div class="col-sm-10">
            <select class="form-control" name="interview_type">
              <option value="0">書類選考</option>
              <option value="1">1次面接</option>
              <option value="2">2次面接</option>
              <option value="3">3次面接</option>
            </select>
          </div>
        </div>
          <input type="hidden" name="interviewee_id" value="<?= h($interviewee_id); ?>">
        <div class="form-group">
          <label class="control-label col-sm-2" for="interview_date_time">選考日時</label>
          <div class="col-sm-5"><input class="form-control" type="date" name="interview_date" value=""></div>
          <div class="col-sm-5"><input class="form-control" type="time" name="interview_time" value=""></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interview_id">選考担当者</label>
          <div class="col-sm-10">
            <select class="form-control" name="interviewer_id[]" multiple>
              <?= $view ?>
            </select>
          </div>
        </div>
        <div class="text-center">
          <input class="btn btn-default" type="submit" value="登録">
        </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>

<?php include("../template/footer.html") ?>

</body>
</html>
