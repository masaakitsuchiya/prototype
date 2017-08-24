<?php
//1. POSTデータ取得
session_start();
include("../function/function.php");
include("../sendgrid/sendgrid_send.php");
include("../template/csrf_confirm.php");

$concel_reason = $_POST["cancel_reason"];
$comment = $_POST["comment"];
$interview_id = $_POST["interview_id"];

//2. DB接続します
$pdo = db_con();

try{
$pdo->beginTransaction();//transaction 開始
//最初に対象のinterview_reserve_timeをすべて削除します。
$stmt = $pdo->prepare("DELETE FROM interview_reserve_time WHERE interview_id =:interview_id");
$stmt->bindValue(':interview_id', $_SESSION["interview_id"]);
$status = $stmt->execute();


//次に対象のinterviewer_listをすべて削除します。
$stmt2 = $pdo->prepare("DELETE FROM interviewer_list WHERE interview_id =:interview_id");
$stmt2->bindValue(':interview_id', $_SESSION["interview_id"]);
$status2 = $stmt2->execute();

//次に対象のinterviewのstage_flgを6[再調整に]変更します。
$stmt3 = $pdo->prepare("UPDATE interview SET stage_flg = :stage_flg, interview_style = :interview_style WHERE id=:interview_id");
$stmt3->bindValue(':interview_id', $_SESSION["interview_id"]);
$stmt3->bindValue(':interview_style', NULL);
$stmt3->bindValue(':stage_flg', 6);

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

//メール送信

$stmt_interviewee_info= $pdo->prepare("SELECT * FROM interviewee_info INNER JOIN interview ON interviewee_info.id = interview.interviewee_id where interview.id=:interview_id");
$stmt_interviewee_info->bindValue(':interview_id',$interview_id, PDO::PARAM_INT);
$status_interviewee_info = $stmt_interviewee_info->execute();
if($status_interviewee_info==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interviewee_info->errorInfo();
  exit("ErrorQuery_interviewee_info:".$error[2]);
}else{
  $res_interviewee_info = $stmt_interviewee_info->fetch();
  }

$url_path = path_for_mail();
$to_s = kanri_users_mails();
$subject_text = "[InterFree]".h($res_interviewee_info["interviewee_name"])."様より面接についてご連絡";
$text = "";
$text .= h($res_interviewee_info["interviewee_name"])."様より以下の内容で面接再調整の要望が参りました。".PHP_EOL;
$text .= "再調整の理由:".h($concel_reason).PHP_EOL;
$text .= "コメント:".h($comment).PHP_EOL;
$text .= "以下URLよりに日程の再調整ができます。".PHP_EOL;
$text .= $url_path.'setting/interview_resetting.php?interview_id='.$interview_id;
$res_send = send_email_by_sendgrid($to_s,$subject_text,$text);




//リダイレクト
  header("Location: interview_reset_thanks.php?cancel_reason=".$concel_reason."&comment=".$comment);//location: のあとに必ずスペースが入る
  exit;

?>
