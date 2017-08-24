<?php
session_start();
include("../function/function.php");
login_check();

if(
  !isset($_POST["job_post_id"]) || $_POST["job_post_id"]=="" ||
  !isset($_POST["life_flg"]) || $_POST["life_flg"]==""
){
  exit('ParamError');
}

$job_post_id = $_POST["job_post_id"];
$life_flg = $_POST["life_flg"];

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
  $data = "success";
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($data);
}

 ?>
