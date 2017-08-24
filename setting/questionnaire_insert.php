<?php
session_start();
include("../function/function.php");
login_check();
kanri_check();

//1. POSTデータ取得
$anchet_message = $_POST["anchet_message"];
$deadline = $_POST["deadline"];
$corp_info_array = corp_info_array();//from setting.php
$corp_name = h($corp_info_array["corp_name"]);
$pdo = db_con();

//anchet テーブルに情報登録
//ほんとうならupdate文

$stmt_anchet = $pdo->prepare("INSERT INTO anchet(anchet_id,interviewee_id,form_id,anchet_message,send_date,deadline,stage_flg
)VALUES(NULL,:interviewee_id,:form_id,:anchet_message,sysdate(),:deadline,:stage_flg)");
$stmt_anchet->bindValue(':interviewee_id', $_SESSION["interviewee_id"], PDO::PARAM_INT);
$stmt_anchet->bindValue(':form_id', $_SESSION["form_id"], PDO::PARAM_INT);
$stmt_anchet->bindValue(':anchet_message', $anchet_message, PDO::PARAM_INT);
$stmt_anchet->bindValue(':deadline', $deadline, PDO::PARAM_STR);
$stmt_anchet->bindValue(':stage_flg', 1, PDO::PARAM_INT);
$status_anchet = $stmt_anchet->execute();

if($status_anchet == false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt_status_anchet->errorInfo();
    exit("QueryError_status_anchet:".$error[2]);
}
$anchet_id = $pdo->lastInsertId();

//ここからメール送信
include("../sendgrid/sendgrid_send.php");

//送信先名前、メールアドレス抽出
$stmt_interviewee_mail = $pdo->prepare("SELECT interviewee_name,mail FROM interviewee_info where id=:interviewee_id");
$stmt_interviewee_mail->bindValue(':interviewee_id',$_SESSION["interviewee_id"], PDO::PARAM_INT);
$status_interviewee_mail = $stmt_interviewee_mail->execute();
if($status_interviewee_mail==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interviewee_mail->errorInfo();
  exit("ErrorQuery_interviewee_mail:".$error[2]);
}else{
  $res_interviewee_mail = $stmt_interviewee_mail->fetch();
  }

$url_path = path_for_mail();
$to_s = $res_interviewee_mail["mail"];
$subject_text = "[smartinterview]".$corp_name."よりアンケート送信のお知らせ";
$text = "";
// $text .= $anchet_message.;
$text .= $res_interviewee_mail["interviewee_name"]."様".PHP_EOL;
$text .= $corp_name."よりアンケート回答のご依頼が届いております。".PHP_EOL;
$text .= "選考をスムーズに行うため、以下URLよりご回答をお願いいたします。".PHP_EOL;
$text .= "なお、回答期限が ".$deadline."に設定されております。".PHP_EOL;
$text .= "期限までにご回答のほどよろしくお願いいたします。".PHP_EOL;
$text .= $url_path.'forinterviewee/reply_anchet.php?anchet_id='.$anchet_id;
$res_send = send_email_by_sendgrid($to_s,$subject_text,$text);

var_dump($res_send);


header("Location: interviewee_select.php");
exit;
// }

?>
