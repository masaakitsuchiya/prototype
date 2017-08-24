<?php
session_start();
include("../function/function.php");
login_check();

$interview_result_id = $_POST["interview_result_id"];
$score_0 = $_POST["score_0"];
$score_1 = $_POST["score_1"];
$score_2 = $_POST["score_2"];
$score_3 = $_POST["score_3"];
$score_4 = $_POST["score_4"];
$score_5 = $_POST["score_5"];
$qualitative_0 = $_POST["qualitative_0"];
$qualitative_1 = $_POST["qualitative_1"];
$qualitative_2 = $_POST["qualitative_2"];
$qualitative_3 = $_POST["qualitative_3"];
$qualitative_4 = $_POST["qualitative_4"];
$qualitative_5 = $_POST["qualitative_5"];
$comment = $_POST["comment"];

//UPDATE gs_an_table SET name='ジーズ', email='e@e.com',naiyou='あ' WHERE id =3
//1.POSTでParamを取得

//2. DB接続します(エラー処理追加)
$pdo = db_con();


//３．データ登録SQL作成
$stmt = $pdo->prepare("UPDATE interview_result SET score_0=:score_0, score_1=:score_1, score_2=:score_2, score_3=:score_3, score_4=:score_4, score_5=:score_5, qualitative_0=:qualitative_0, qualitative_1=:qualitative_1, qualitative_2=:qualitative_2, qualitative_3=:qualitative_3, qualitative_4=:qualitative_4, qualitative_5=:qualitative_5, comment=:comment WHERE id=:id");
$stmt->bindValue(':id', $interview_result_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':score_0', $score_0, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':score_1', $score_1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':score_2', $score_2, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':score_3', $score_3, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':score_4', $score_4, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':score_5', $score_5, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':qualitative_0', $qualitative_0, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':qualitative_1', $qualitative_1, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':qualitative_2', $qualitative_2, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':qualitative_3', $qualitative_3, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':qualitative_4', $qualitative_4, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':qualitative_5', $qualitative_5, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}else{
  //５．index.phpへリダイレクト
  header("Location: input_data_select.php");
  exit;
}



//3.UPDATE gs_an_table SET ....; で更新(bindValue)
//　基本的にinsert.phpの処理の流れです。




?>
