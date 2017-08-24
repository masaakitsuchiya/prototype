<?php
$toSubmit = $_POST["toSubmit_address"];
$mail_text = h($_POST["mail_text"]);

$interview_style = $_POST["interview_style"];

//2. DB接続します
$pdo = db_con();

try{
$pdo->beginTransaction();//transaction 開始

$stmt = $pdo->prepare("INSERT INTO interview(id, interview_type, interview_style, interviewee_id, stage_flg
)VALUES(NULL, :interview_type, :interview_style, :interviewee_id, :stage_flg)");
$stmt->bindValue(':interview_type', $_SESSION["interview_type_num"], PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':interviewee_id', $_SESSION["interviewee_id"], PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':interview_style', $interview_style, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':stage_flg', 1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();


$interview_id = $pdo->lastInsertId();

$stmt = $pdo->prepare("INSERT INTO interviewer_list(id, interview_id, interviewer_id
)VALUES(NULL, :interview_id, :interviewer_id)");
$stmt->bindParam(':interview_id', $interview_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
foreach($_SESSION["interviewer_id"] as $interviewerId){
  $stmt->bindValue(':interviewer_id', $interviewerId, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
  $status2 = $stmt->execute();
}

$stmt = $pdo->prepare("INSERT INTO interview_reserve_time(id, interview_id, interview_reserve_time
)VALUES(NULL, :interview_id, :interview_reserve_time)");
$stmt->bindParam(':interview_id', $interview_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
foreach($_SESSION["interview_date_time_reserves"] as $interview_reserve_time){
  $stmt->bindValue(':interview_reserve_time', $interview_reserve_time, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
  $status3 = $stmt->execute();
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
    exit("QueryError1:".$error[2]);
}
if($status2==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError2:".$error[2]);
}
if($status3==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError3:".$error[2]);
}

//ここからメール送信設定
$stmt_interviewee_info= $pdo->prepare("SELECT interviewee_name,mail FROM interviewee_info where id=:interviewee_id");
$stmt_interviewee_info->bindValue(':interviewee_id',$_SESSION["interviewee_id"], PDO::PARAM_INT);
$status_interviewee_info = $stmt_interviewee_info->execute();
if($status_interviewee_info==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interviewee_info->errorInfo();
  exit("ErrorQuery_interviewee_info:".$error[2]);
}else{
  $res_interviewee_info = $stmt_interviewee_info->fetch();
  }
  ?>
