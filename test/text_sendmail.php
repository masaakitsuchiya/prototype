<?php

include("../sendgrid/sendgrid_send.php");
$to_s = "zenmon.tiger.koumon.wolf@gmail.com,gyouza1976jp@yahoo.co.jp";
$subject_text = "[smartinterview]テストのお知らせ";
$text = "";
$text .= "こんにちわ\r\n ";
$text .= "この度はご応募ありがとうございます。\r\n ";
$res_send = send_email_by_sendgrid($to_s,$subject_text,$text);

var_dump($res_send);

?>
