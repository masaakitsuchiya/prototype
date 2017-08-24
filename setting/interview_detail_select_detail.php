<?php
session_start();
include("../function/function.php");
login_check();
$interview_id=$_GET["interview_id"];
$interviewee_name = $_GET["interviewee_name"];
//1.  DB接続します
$pdo = db_con();

//interview情報取得
$stmt = $pdo->prepare("SELECT * FROM interview where id=:id");
$stmt->bindValue(':id',$interview_id, PDO::PARAM_INT);
$status = $stmt->execute();



if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res = $stmt->fetch();
  // var_dump($res);
  // exit;
  }

$interview_date_time = explode(" ",$res["interview_date_time"]);
$interview_date = $interview_date_time[0];
$interview_time = $interview_date_time[1];

// var_dump($interview_time_int);

//面接者リスト取得（selected用)
$stmt = $pdo->prepare("SELECT * FROM interviewer_list where interview_id=:interview_id");
$stmt->bindValue(':interview_id',$res["id"], PDO::PARAM_INT);
$status = $stmt->execute();
$interviewer_list = array();
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
array_push($interviewer_list,$result["interviewer_id"]);
  }
}

// var_dump($interviewer_list);
//
// exit;


//全面接者リスト情報取得(optionタグ用)

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
    if(in_array($result["id"],$interviewer_list)){
      $view .='<option value="'.h($result["id"]).'" selected>'.h($result["interviewer_name"]).'</option>';
    }else{
      $view .='<option value="'.h($result["id"]).'">'.h($result["interviewer_name"]).'</option>';
    }
  }
}
//
// $stmt = $pdo->prepare("SELECT * FROM interviewee_info where id=$interviewee_id");
// $status2 = $stmt->execute();
//
// //３．データ表示
// if($status2==false){
//   //execute（SQL実行時にエラーがある場合）
//   $error = $stmt->errorInfo();
//   exit("ErrorQuery:".$error[2]);
// }else{
//   $res = $stmt->fetch();
//   }


$html_title = servise_name();
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
      <form class="form-horizontal" action="interview_detail_select_update.php" method="post">
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewee_name">候補者名</label><div class="col-sm-10"><p class="form-control-static"><?= h($interviewee_name); ?></p></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interview_type">選考ステップ</label>
          <div class="col-sm-10">
            <select class="form-control" name="interview_type">
              <option value="0"<?php if($res["interview_type"]==0){echo "selected";} ?>>書類選考</option>
              <option value="1"<?php if($res["interview_type"]==1){echo "selected";} ?>>1次面接</option>
              <option value="2"<?php if($res["interview_type"]==2){echo "selected";} ?>>2次面接</option>
              <option value="3"<?php if($res["interview_type"]==3){echo "selected";} ?>>3次面接</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interview_date_time">選考日時</label>
          <div class="col-sm-5"><span>日付</span><input class="form-control" type="date" name="interview_date" value="<?=$interview_date?>"></div>
          <div class="col-sm-5"><span>時間<span><input class="form-control" type="time" name="interview_time" value="<?=$interview_time?>"></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewer_id">選考担当者</label>
          <div class="col-sm-10">
            <select class="form-control" name="interviewer_id[]" multiple>
              <?= $view ?>
            </select>
          </div>
        </div>
        <div class="text-center">
          <input type="hidden" name="id" value="<?=$interview_id?>">
          <input class="btn btn-default" type="submit" value="修正">
        </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>

<?php include("../template/footer.html") ?>

</body>
</html>
