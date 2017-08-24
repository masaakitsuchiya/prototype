<?php
session_start();
include("../function/function.php");
login_check();
kanri_check();

//1. POSTデータ取得 from interview_setting
$questions = $_POST["question"];
$answers = $_POST["answer"];

echo('<pre>');
var_dump($questions);
// var_dump($answer);
var_dump($answers);
echo('</pre>');
// exit();

$pdo = db_con();

//anchet テーブルに情報登録
//ほんとうならupdate文

$stmt_anchet = $pdo->prepare("INSERT INTO anchet(anchet_id,interviewee_id,form_id,send_date,deadline,stage_flg
)VALUES(NULL,:interviewee_id,:form_id,sysdate(),:deadline,:stage_flg)");
$stmt_anchet->bindValue(':interviewee_id', 1, PDO::PARAM_INT);
$stmt_anchet->bindValue(':form_id', 8, PDO::PARAM_INT);
$stmt_anchet->bindValue(':deadline', "2015-12-11 12:00:00", PDO::PARAM_STR);
$stmt_anchet->bindValue(':stage_flg', 1, PDO::PARAM_INT);
$status_anchet = $stmt_anchet->execute();

if($status_anchet == false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt_status_anchet->errorInfo();
    exit("QueryError_status_anchet:".$error[2]);
}

//detail_question　tableに　questionの数だけ登録
$anchet_id = $pdo->lastInsertId();
$stmt_detail_question = $pdo->prepare("INSERT INTO detail_question(detail_question_id,anchet_id,question,question_order
)VALUES(NULL,:anchet_id,:question,:question_order)");
foreach($questions as $form_num_str=>$question){
  $stmt_detail_question->bindValue(':anchet_id', $anchet_id, PDO::PARAM_INT);
  $stmt_detail_question->bindValue(':question', $question, PDO::PARAM_STR);
  $question_order = intval(str_replace('form_','',$form_num_str));
  $stmt_detail_question->bindValue(':question_order',$question_order, PDO::PARAM_INT);
  $status_detail_question = $stmt_detail_question->execute();
  if($status_detail_question == false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt_detail_question->errorInfo();
    exit("QueryError_detail_question:".$error[2]);
  }
  //detail_answer tableに　answerの数だけ登録
  $detail_question_id = $pdo->lastInsertId();
  $stmt_detail_answer = $pdo->prepare("INSERT INTO detail_answer(detail_answer_id,detail_question_id,answer
  )VALUES(NULL,:detail_question_id,:answer)");
  $stmt_detail_answer->bindValue(':detail_question_id', $detail_question_id, PDO::PARAM_INT);
  foreach($answers[$form_num_str] as $answer){
    $stmt_detail_answer->bindValue(':answer', $answer, PDO::PARAM_STR);
    $status_detail_answer = $stmt_detail_answer->execute();
    if($status_detail_answer == false){
      //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
      $error = $stmt_detail_answer->errorInfo();
      exit("QueryError_detail_answer:".$error[2]);
    }


  }

}

header("Location: input.php");
exit;
// }

?>
