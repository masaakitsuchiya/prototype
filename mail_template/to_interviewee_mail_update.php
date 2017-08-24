<?php
session_start();
include("../function/function.php");
login_check();

if(
  !isset($_POST["nonvideo_interview_invite"]) || $_POST["nonvideo_interview_invite"]=="" ||
  !isset($_POST["video_interview_invite"]) || $_POST["video_interview_invite"]=="" ||
  !isset($_POST["interview_time_fix"]) || $_POST["interview_time_fix"]=="" ||
  !isset($_POST["re_video_interview_adjusting"]) || $_POST["re_video_interview_adjusting"]=="" ||
  !isset($_POST["re_non_video_interview_adjusting"]) || $_POST["re_non_video_interview_adjusting"]=="" ||
  !isset($_POST["cancel_while_adjusting"]) || $_POST["cancel_while_adjusting"]=="" ||
  !isset($_POST["cancel_after_fixed"]) || $_POST["cancel_after_fixed"]==""
){
  exit('ParamError');
}



$nonvideo_interview_invite        = $_POST["nonvideo_interview_invite"];
$video_interview_invite           = $_POST["video_interview_invite"];
$interview_time_fix               = $_POST["interview_time_fix"];
$re_video_interview_adjusting     = $_POST["re_video_interview_adjusting"];
$re_non_video_interview_adjusting = $_POST["re_non_video_interview_adjusting"];
$cancel_while_adjusting           = $_POST["cancel_while_adjusting"];
$cancel_after_fixed               = $_POST["cancel_after_fixed"];


// var_dump($nonvideo_interview_invite);
// var_dump($video_interview_invite);
// var_dump($interview_time_fix);
// var_dump($re_video_interview_adjusting);
// var_dump($re_non_video_interview_adjusting);
// var_dump($cancel_while_adjusting);
// var_dump($cancel_after_fixed);
// exit;


//2. DB接続します(エラー処理追加)
$pdo = db_con();


//３．データ登録SQL作成
$stmt = $pdo->prepare("UPDATE default_mail_text SET nonvideo_interview_invite=:nonvideo_interview_invite, video_interview_invite=:video_interview_invite, interview_time_fix=:interview_time_fix, re_video_interview_adjusting=:re_video_interview_adjusting, re_non_video_interview_adjusting=:re_non_video_interview_adjusting, cancel_while_adjusting=:cancel_while_adjusting, cancel_after_fixed=:cancel_after_fixed WHERE mail_text_id=:mail_text_id");
$stmt->bindValue(':mail_text_id', 1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':nonvideo_interview_invite', $nonvideo_interview_invite, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':video_interview_invite', $video_interview_invite, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':interview_time_fix', $interview_time_fix, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':re_video_interview_adjusting', $re_video_interview_adjusting, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':re_non_video_interview_adjusting', $re_non_video_interview_adjusting, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':cancel_while_adjusting', $cancel_while_adjusting, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':cancel_after_fixed', $cancel_after_fixed, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}else{
  //５．index.phpへリダイレクト
  header("Location: to_interviewee_mail_show.php");
  exit;
}



//3.UPDATE gs_an_table SET ....; で更新(bindValue)
//　基本的にinsert.phpの処理の流れです。




?>
