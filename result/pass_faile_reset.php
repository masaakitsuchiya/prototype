<?php

session_start();
include("../function/function.php");
login_check();
kanri_check();//管理者のみ実行できる
//入力チェック(受信確認処理追加)


if(
  !isset($_GET["interview_id"]) || $_GET["interview_id"]==""
){
  exit('ParamError');
}

//1. GETデータ取得
$interview_id      = $_GET["interview_id"];

$stage_flg = 3;//stage_flgに合否判定前＝面接日時確定をセット
$t_r_reason = "";//合否の理由を削除
$fix_time = "";//合否確定時間を削除
//2. DB接続します(エラー処理追加)
$pdo = db_con();

$stmt = $pdo->prepare("UPDATE interview SET stage_flg=:stage_flg,t_r_reason=:t_r_reason,fix_time=:fix_time WHERE id=:id");
$stmt->bindValue(':id', $interview_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':stage_flg', $stage_flg, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':t_r_reason', $t_r_reason, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':fix_time', $fix_time, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
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
