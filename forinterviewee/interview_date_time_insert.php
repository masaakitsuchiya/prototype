<?php
//1. POSTデータ取得
session_start();
include("../function/function.php");
include("../template/csrf_confirm.php");

$interview_reserve_time = $_POST["interview_reserve_time"];

//2. DB接続します
$pdo = db_con();

try{
$pdo->beginTransaction();//transaction 開始
//最初に対象のinterview_reserve_timeをすべて削除します。
$stmt = $pdo->prepare("DELETE FROM interview_reserve_time WHERE interview_id =:interview_id");
$stmt->bindValue(':interview_id', $_SESSION["interview_id"]);
$status = $stmt->execute();


//選択された日時を新たに追加します。
$stmt2 = $pdo->prepare("INSERT INTO interview_reserve_time(id, interview_id, interview_reserve_time
)VALUES(NULL, :interview_id, :interview_reserve_time)");
$stmt2->bindValue(':interview_id', $_SESSION["interview_id"], PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt2->bindValue(':interview_reserve_time', $interview_reserve_time, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status2 = $stmt2->execute();

$stmt3 = $pdo->prepare("UPDATE interview SET stage_flg = 2 WHERE id=:interview_id");
$stmt3->bindValue(':interview_id', $_SESSION["interview_id"], PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status3 = $stmt3->execute();


$pdo->commit();
}catch (PDOException $e) {
  $pdo->rollback();
  echo "とちゅうでとまりました";
  exit;
}

//データ登録処理後 errorがあったらとまる
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}
if($status2==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error2 = $stmt2->errorInfo();
  exit("QueryError:".$error2[2]);
}
if($status3==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error3 = $stmt3->errorInfo();
  exit("QueryError:".$error3[2]);
}
//ここからメール送信
//候補者名検索

$stmt_interviewee_name = $pdo->prepare("SELECT interviewee_info.interviewee_name FROM interviewee_info INNER JOIN interview ON interviewee_info.id = interview.interviewee_id where interview.id = :interview_id");
$stmt_interviewee_name->bindValue(':interview_id',$_SESSION["interview_id"], PDO::PARAM_INT);
$status_interviewee_name = $stmt_interviewee_name->execute();
if($status_interviewee_name==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interviewee_name->errorInfo();
  exit("ErrorQuery_interviewee_name:".$error[2]);
}else{
  $res_interviewee_name = $stmt_interviewee_name->fetch();
  }

include("../sendgrid/sendgrid_send.php");
$to_s = kanri_users_mails();
$url_path = path_for_mail();
$subject_text = "[InterFree]".h($res_interviewee_name["interviewee_name"])."様より面接時間調整に関する返信が来ています。";
$text = "";
// $text .= $anchet_message.;
$text .= h($res_interviewee_name["interviewee_name"])."様より面接時間調整のご連絡が届いております。".PHP_EOL;
$text .= "以下URLよりにアクセスしていただき、なるべく早めに面接日時の確定をお願いいたします。".PHP_EOL;
$text .= "確定に時間がかかりますと再調整しなくてはならなくなりますのでご注意ください。".PHP_EOL;
$text .= $url_path.'setting/interview_confirm01.php?interview_id='.$_SESSION["interview_id"];
$res_send = send_email_by_sendgrid($to_s,$subject_text,$text);

// var_dump($res_send);


//リダイレクト
  header("Location: interview_date_time_select04.php");//location: のあとに必ずスペースが入る
  exit;

?>
