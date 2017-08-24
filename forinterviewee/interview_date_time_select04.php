<?php

// /10/my_kaday/setting/interview_date_time_select01.php?interviewee_id=*&interview_id=*

session_start();
include("../function/function.php");

$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM interview,interviewee_info WHERE interview.id = :interview_id AND interview.interviewee_id = interviewee_info.id");
$stmt->bindValue(':interview_id', $_SESSION["interview_id"], PDO::PARAM_INT);
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
    $view .= h($result["interviewee_name"]);
  }
}

$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head_for_interviewee.php") ?>
<style>
h4.pg{
font-size:0.9em;
}
.gray{
  color:#aaa;
}

</style>



</head>
<body>
<?php include("../template/nav_for_interviewee.php") ?>
<div class="container-fruid">
  <div class="row">
      <div class="col-xs-2"></div>
      <h4 class="col-xs-2 pg text-center gray">1,規約同意</h4><h4 class="col-xs-2 pg text-center gray">2,動作検証</h4><h4 class="col-xs-2 pg text-center gray">3,面接日時選択</h4><h4 class=" col-xs-2 pg text-center">4,返信完了
      </h4>
      <div class="col-xs-2"></div>
  </div>
</div>
<div class="container-fruid">
  <div class="row">
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
    <h3 class="text-center">返信完了</h3>
    <!-- <h4 class="text-center"><?= $view ?>様</h4> -->
    <p>ご確認ありがとうございました。
まだ、面接日時は確定していません。面接担当者が面接日時を確認するまでしばらくお待ち下さい
面接日時が確定しましたらあらためてご案内をお送りしますので必ずご確認くださいますようお願い致します。</p>
    </div>
    <div class="col-xs-2"></div>
  </div>
</div>




<!-- <a href="../setting/interviewee_select.php">動作確認用　interviewee_selectにもどる</a> -->

<?php include("../template/footer_for_interviewee.html") ?>
<?php $_SESSION["interview_id"] =""; ?>
</body>
</html>
