<?php

session_start();
include("../function/function.php");
include("../function/setting.php");
include("../template/csrf_confirm.php");
login_check();

$corp_info_array = corp_info_array();//from setting.php
$corp_name = h($corp_info_array["corp_name"]);
$corp_address = h($corp_info_array["address"]);
$corp_mail = h($corp_info_array["corp_mail"]);
$corp_tel = h($corp_info_array["tel"]);


include("../template/interview_setting_insert.php");


include("../sendgrid/sendgrid_send.php");
$url_path = path_for_mail();
$to_s = $toSubmit;
$subject_text = "[InterFree]".$corp_name."より面接時間調整のお知らせ";
$text = "";
// $text .= $anchet_message.;
$text .= h($res_interviewee_info["interviewee_name"])."様".PHP_EOL;
$text .= h($corp_name)."様より面接時間調整のご連絡が届いております。".PHP_EOL.PHP_EOL;
$text .= "【".h($corp_name)."様からのメッセージ】".PHP_EOL;
$text .="================================================================================================================".PHP_EOL.PHP_EOL;
$text .= h($mail_text).PHP_EOL.PHP_EOL;
$text .= h($corp_name).PHP_EOL;
$text .= h($corp_address).PHP_EOL;
$text .= "tel:".h($corp_tel).PHP_EOL;
$text .= "mail:".h($corp_mail).PHP_EOL;
$text .="================================================================================================================".PHP_EOL;
$text .= PHP_EOL;
$text .= $url_path.'forinterviewee/interview_date_time_select01.php?interview_id='.$interview_id.PHP_EOL.PHP_EOL;
$text .= "なるべく早めに面接日時の候補のご確認をお願いいたします。".PHP_EOL;
$text .= "確認に時間がかかりますと面接時間の候補日時が過ぎてしまうことがありますのでご注意ください。".PHP_EOL;
$text .= PHP_EOL;
$text .= "<<".servise_name().">>";
$text .= PHP_EOL;



$res_send = send_email_by_sendgrid($to_s,$subject_text,$text);

// var_dump($res_send);



header("Location: interviewee_select.php");//location: のあとに必ずスペースが入る
exit;


?>
