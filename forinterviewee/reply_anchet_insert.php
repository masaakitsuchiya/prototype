<?php
session_start();
include("../function/function.php");

$anchet_id = $_GET["anchet_id"];
//1. POSTデータ取得 from interview_setting
$questions = $_POST["question"];
$answers = $_POST["answer"];

$pdo = db_con();

//anchet テーブルに情報登録
//ほんとうならupdate文
$stmt_anchet = $pdo->prepare("UPDATE anchet SET stage_flg=:stage_flg,recieved_date= sysdate() WHERE anchet_id=:anchet_id");
$stmt_anchet->bindValue(':anchet_id', $anchet_id, PDO::PARAM_INT);
// $stmt_anchet->bindValue(':recieved_date', sysdate();, PDO::PARAM_STR);
$stmt_anchet->bindValue(':stage_flg', 2, PDO::PARAM_INT);
$status_anchet = $stmt_anchet->execute();

if($status_anchet == false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt_status_anchet->errorInfo();
    exit("QueryError_status_anchet:".$error[2]);
}

//detail_question　tableに　questionの数だけ登録

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
    // if(!$answer||$answer=""){
    //   $answer = "回答なし";
    // }
    $stmt_detail_answer->bindValue(':answer', $answer, PDO::PARAM_STR);
    $status_detail_answer = $stmt_detail_answer->execute();
    if($status_detail_answer == false){
      //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
      $error = $stmt_detail_answer->errorInfo();
      exit("QueryError_detail_answer:".$error[2]);
    }


  }

}

//メール送信
$stmt_interviewee_name = $pdo->prepare("SELECT interviewee_info.interviewee_name FROM interviewee_info INNER JOIN anchet ON interviewee_info.id = anchet.interviewee_id where anchet.anchet_id = :anchet_id");
$stmt_interviewee_name->bindValue(':anchet_id',$anchet_id, PDO::PARAM_INT);
$status_interviewee_name = $stmt_interviewee_name->execute();
if($status_interviewee_name==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interviewee_name->errorInfo();
  exit("ErrorQuery_interviewee_name:".$error[2]);
}else{
  $res_interviewee_name = $stmt_interviewee_name->fetch();
  }

include("../sendgrid/sendgrid_send.php");
$url_path = path_for_mail();
$to_s = kanri_users_mails();
$subject_text = "[smartinterview]".$res_interviewee_name["interviewee_name"]."様よりアンケート回答のお知らせ";
$text = "";
// $text .= $anchet_message.;
// $text .= $res_interviewee_mail["interviewee_name"]."様".PHP_EOL;
$text .= $res_interviewee_name["interviewee_name"]."様よりアンケート回答のが届いております。".PHP_EOL;
$text .= "smartinterviewにログイン後,候補者一覧画面からご確認いただけます。".PHP_EOL;
$text .= $url_path.'setting/questionnaire_show.php?anchet_id='.$anchet_id;
$res_send = send_email_by_sendgrid($to_s,$subject_text,$text);

var_dump($res_send);

header("Location: reply_show.php?anchet_id=$anchet_id");
exit;
// }

?>
