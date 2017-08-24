<?php
session_start();
include("../function/function.php");
login_check();

$id     = $_GET["interview_id"];
// $interviewee_name =$_GET["interviewee_name"];
//2. DB接続します(エラー処理追加)
$pdo = db_con();

$stmt = $pdo->prepare("SELECT * FROM interview where id=:id");
$stmt->bindValue(':id',$id,PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res = $stmt->fetch();
}



try{
  $pdo->beginTransaction();
  $stmt1 = $pdo->prepare("DELETE FROM interview WHERE id=:id");
  $stmt1->bindValue(':id', $id);
  $status1 = $stmt1->execute();
  $stmt2 = $pdo->prepare("DELETE FROM interviewer_list WHERE interview_id=:id");
  $stmt2->bindValue(':id', $id);
  $status2 = $stmt2->execute();
  $pdo->commit();

}catch (PDOException $e) {
    $pdo->rollback();
    echo "とちゅうでとまりました";
    exit;
}

//４．データ登録処理後
if($status1==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error1 = $stmt1->errorInfo();
  exit("QueryError:".$error1[2]);
}
if($status2==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error2 = $stmt2->errorInfo();
  exit("QueryError:".$error2[2]);
}
header("Location: interview_detail_select.php?target_interviewee_id=".$res["interviewee_id"]);
exit;

?>
