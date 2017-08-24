<?php
session_start();
include("../function/function.php");
login_check();

if(
  !isset($_GET["job_post_id"]) || $_GET["job_post_id"]=="" ||
  !isset($_GET["life_flg"]) || $_GET["life_flg"]==""
){
  exit('ParamError');
}

$job_post_id = $_GET["job_post_id"];
$life_flg = $_GET["life_flg"];

$pdo = db_con();

$stmt = $pdo->prepare("UPDATE job_post SET life_flg = :life_flg WHERE id=:id");
$stmt->bindValue(':id', $job_post_id, PDO::PARAM_INT);
$stmt->bindValue(':life_flg', $life_flg, PDO::PARAM_INT);
$status = $stmt->execute();

if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}else{
  header("Location: job_post_select0.php");
  exit;
}

 ?>
