<?php
function send_email_by_sendgrid($to_s,$subject_text,$set_text){
require 'vendor/autoload.php';
$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$api_key           = $_ENV['API_KEY'];
$from              = $_ENV['FROM'];
$tos               = explode(',', $to_s);
$subject = $subject_text;
$text= $set_text;

$sendgrid = new SendGrid($api_key, array("turn_off_ssl_verification" => true));
$email    = new SendGrid\Email();
$email->setSmtpapiTos($tos)->
       setFrom($from)->
       setFromName("smartinterview system mail")->
       setSubject($subject)->
       setText($text)->
      //  setHtml("<strong> %familyname% さんは何をしていますか？</strong><br />彼は%place%にいます。")->
      //  addSubstitution("%fullname%", array("田中 太郎", "佐藤 次郎", "鈴木 三郎"))->
      //  addSubstitution("%familyname%", array("田中", "佐藤", "鈴木"))->
      //  addSubstitution("%place%", array("%office%", "%home%", "%office%"))->
      //  addSection('%office%', '中野')->
      //  addSection('%home%', '目黒')->
      //  addCategory('category1')->
       addHeader('X-Sent-Using', 'SendGrid-API');      //  addAttachment('./gif.gif', 'owl.gif');

$response = $sendgrid->send($email);
return ($response);
}
?>
