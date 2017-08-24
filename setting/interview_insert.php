<?php

session_start();
include("../function/function.php");
login_check();

//1. POSTデータ取得 from interview_setting
$interview_type =  $_POST["interview_type"];
$interviewee_id =  $_POST["interviewee_id"];
$interview_date = $_POST["interview_date"];
$interview_time = $_POST["interview_time"];
$interviewer_id = $_POST["interviewer_id"];

$interviewer_id_count = count($interviewer_id);
// var_dump($interviewer_id_count);
// exit;

$interview_date_time = $interview_date." ".$interview_time;

//2. DB接続します
$pdo = db_con();
//複数のテーブルにデータをインサートするのはどうやるの？

//３．データ登録SQL作成
//interview の設定


try{
$pdo->beginTransaction();//transaction 開始

$stmt = $pdo->prepare("INSERT INTO interview(id, interview_type, interviewee_id, interview_date_time
)VALUES(NULL, :interview_type, :interviewee_id, :interview_date_time)");
$stmt->bindValue(':interview_type', $interview_type, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':interviewee_id', $interviewee_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':interview_date_time', $interview_date_time, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();


$interview_id = $pdo->lastInsertId();
$stmt = $pdo->prepare("INSERT INTO interviewer_list(id, interview_id, interviewer_id
)VALUES(NULL, :interview_id, :interviewer_id)");
$stmt->bindParam(':interview_id', $interview_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
for($i = 0 ; $i < $interviewer_id_count; $i++){
  $stmt->bindValue(':interviewer_id', $interviewer_id[$i], PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
  $status2 = $stmt->execute();
}

$pdo->commit();

}catch (PDOException $e) {
  $pdo->rollback();
  echo "とちゅうでとまりました";
  exit;
}
if($status==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
}
if($status2==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
}

header("Location: interview_detail_select.php?target_interviewee_id=".$interviewee_id);//location: のあとに必ずスペースが入る
exit;


?>
