<?php
session_start();
include("../function/function.php");
login_check();

//UPDATE gs_an_table SET name='ジーズ', email='e@e.com',naiyou='あ' WHERE id =3
//1.POSTでParamを取得
$id                     = $_POST["id"];
$interview_type         = $_POST["interview_type"];
$interview_date         = $_POST["interview_date"];
$interview_time         = $_POST["interview_time"];
$interviewer_id         = $_POST["interviewer_id"];

$interviewer_id_count = count($interviewer_id);
$interview_date_time = $interview_date." ".$interview_time;
//2. DB接続します(エラー処理追加)
$pdo = db_con();


//３．データ登録SQL作成

//interviewをアップデート

try{
  $pdo->beginTransaction();//transaction 開始

  $stmt1 = $pdo->prepare("UPDATE interview SET interview_type=:interview_type,interview_date_time=:interview_date_time WHERE id=:id");
  $stmt1->bindValue(':id', $id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
  $stmt1->bindValue(':interview_type', $interview_type, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
  $stmt1->bindValue(':interview_date_time', $interview_date_time, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
  $status1 = $stmt1->execute();

  //関連するinterviewer_listを一旦削除
  $stmt2 = $pdo->prepare("DELETE FROM interviewer_list WHERE interview_id=:id");
  $stmt2->bindValue(':id', $id);
  $status2 = $stmt2->execute();

  //interview_listをあらたに追加
  $stmt3 = $pdo->prepare("INSERT INTO interviewer_list(id, interview_id, interviewer_id
  )VALUES(NULL, :interview_id, :interviewer_id)");
  $stmt3->bindParam(':interview_id', $id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
    for($i = 0 ; $i < $interviewer_id_count; $i++){
      $stmt3->bindValue(':interviewer_id', $interviewer_id[$i], PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
      $status3 = $stmt3->execute();
    }

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
if($status3==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error3 = $stmt3->errorInfo();
  exit("QueryError:".$error3[2]);
}


//3.UPDATE gs_an_table SET ....; で更新(bindValue)
//　基本的にinsert.phpの処理の流れです。

$stmt4 = $pdo->prepare("SELECT * FROM interview where id=:id");
$stmt4->bindValue(':id',$id,PDO::PARAM_INT);
$status4 = $stmt4->execute();

//３．データ表示
if($status4==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt4->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res = $stmt4->fetch();
}

header("Location: interview_detail_select.php?target_interviewee_id=".h($res["interviewee_id"]));//location: のあとに必ずスペースが入る
exit;


?>
