<?php

session_start();
include("../function/function.php");
login_check();
kanri_check();//管理者のみ実行できる
//入力チェック(受信確認処理追加)


if(
  !isset($_POST["stage_flg"]) || $_POST["stage_flg"]=="" ||
  !isset($_POST["t_r_reason"]) || $_POST["t_r_reason"]=="" ||
  !isset($_GET["interview_id"]) || $_GET["interview_id"]==""
){
  exit('ParamError');
}

//1. POSTデータ取得
$stage_flg       = $_POST["stage_flg"];
$t_r_reason       = $_POST["t_r_reason"];
$interview_id      = $_GET["interview_id"];

//2. DB接続します(エラー処理追加)
$pdo = db_con();

$stmt = $pdo->prepare("UPDATE interview SET stage_flg=:stage_flg,t_r_reason=:t_r_reason,fix_time=sysdate() WHERE id=:id");
$stmt->bindValue(':id', $interview_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':stage_flg', $stage_flg, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':t_r_reason', $t_r_reason, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}else{
  //５．index.phpへリダイレクト
  header("Location: output_data.php?interview_id=".$interview_id);
  exit;
}
?>
