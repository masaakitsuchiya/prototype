<?php

//エスケープ
function h($str){
    $str = htmlspecialchars($str,ENT_QUOTES);
    return $str;//関数の外に出す
}
//
// html から<scritp>と<style>を削除
function hd($str){
  $dom = new DOMDocument();
  libxml_use_internal_errors(true);
  $dom->loadHTML(mb_convert_encoding($str, 'HTML-ENTITIES', 'utf-8'));
  libxml_clear_errors();

  while (($r = $dom->getElementsByTagName("script")) && $r->length) {
    $r->item(0)->parentNode->removeChild($r->item(0));
  }

  while (($r = $dom->getElementsByTagName("style")) && $r->length) {
    $r->item(0)->parentNode->removeChild($r->item(0));
  }

  $html = mb_convert_encoding($dom->saveHTML(), 'HTML-ENTITIES', 'utf-8');
  return $html;
}

//login されているかの確認のチェック
function login_check(){
  if(!isset($_SESSION["chk_ssid"])||($_SESSION["chk_ssid"])!=session_id()){
    echo "Login error";
    header("Location: /dh/login_out/login.php");
    exit();
    }else{
      session_regenerate_id(true);
      $_SESSION["chk_ssid"] = session_id();
    }
}

//kanri_flg checker

function kanri_check(){
  if(!isset($_SESSION["kanri_flg"])||$_SESSION["kanri_flg"] == 0){
    echo "You are not authorized to access this page";
    header("Location: ../top/index.php");
  }
}
//dbに接続
//local
function db_con(){
  $dbname='for_dh';
  try {
    $pdo = new PDO('mysql:dbname='.$dbname.';charset=utf8;host=localhost','root','');
  } catch (PDOException $e) {
    exit('DbConnectError:'.$e->getMessage());
  }
  return $pdo;
}

//sakura
//function db_con(){
//  $dbname='m-tsuchiya_seisaku';
//  try {
//    $pdo = new PDO('mysql:dbname='.$dbname.';charset=utf8;host=mysql537.db.sakura.ne.jp','m-tsuchiya','muramura1');
//  } catch (PDOException $e) {
//    exit('DbConnectError:'.$e->getMessage());
//  }
//  return $pdo;
//}



//SQL処理エラー
function queryError($stmt){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}

function interview_type($interview_type_num){
$interview_type = array("書類選考","1次面接","2次面接","3次面接");
return $interview_type[$interview_type_num];
}
function skyway_key(){
  return "68ea836f-c243-4b68-b5c9-ee68fcf48c97";
  //c87b073d-a677-409a-8b04-3323574b50e1 sakura
  //68ea836f-c243-4b68-b5c9-ee68fcf48c97 local
}

function url_folder_name_remove($spacer,$url){
  $url_array = explode($spacer,$url);
  $url_array[1] = "..".$url_array[1];
  return $url_array[1];
}

//秒数を切り捨てる関数
function remove_second($date_time){
  $removed_second_time = mb_substr($date_time,0,-3);
  return $removed_second_time;
}
//時間を切り捨てて日付のみにする関数
function remove_time($date_time){
  $removed_time = mb_substr($date_time,0,-9);
  return $removed_time;
}

//phpの変数をjavascriptに渡すとき安全にjsonエンコードする
function json_safe_encode($data){
    return json_encode($data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}



//メールにわたすURLの絶対パスを切り替える。
function path_for_mail(){
  // local
  return "http://localhost/prototype/";

  // sakura
  //return "https://m-tsuchiya.sakura.ne.jp/prototype/";
}

function kanri_users_mails(){//管理ユーザーのメールアドレスのリスト
  $pdo = db_con();
  //２．データ登録SQL作成 kanri user のメールアドレス抽出
  $stmt_mails = $pdo->prepare("SELECT * FROM interviewer_info WHERE kanri_flg = :kanri_flg");
  $stmt_mails->bindValue(':kanri_flg',1, PDO::PARAM_INT);
  $status_mails = $stmt_mails->execute();

  $mail_list = "";
  //３．データ表示
  if($status_mails==false){
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt_mails->errorInfo();
    exit("ErrorQuery_mails:".$error[2]);
  }else{
    //Selectデータの数だけ自動でループしてくれる
    while( $result_mails = $stmt_mails->fetch(PDO::FETCH_ASSOC)){
    $mail_list .= $result_mails["interviewer_mail"].',';
    }
    $mail_list = rtrim($mail_list, ',');
  }
  return $mail_list;
}

function before_after_30minute($target_time_str){// ターゲット時間の３０分前と３０分後を配列で取得
  $target_time = strtotime($target_time_str);//日時の文字列をタイムスタンプに変換
  $b30_and_a30[] = date('Y-m-d H:i:s', strtotime('30 minute', $target_time)); //設定時間の３０分後を取得
  $b30_and_a30[] = date('Y-m-d H:i:s', strtotime('-30 minute', $target_time)); //設定時間の３０分前を取得

  return $b30_and_a30;
}

function corp_name(){
  return "test";
}
//csrf用のトークン生成
function get_csrf_token() {
  $TOKEN_LENGTH = 16;//16*2=32バイト
  $bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);
  return bin2hex($bytes);
}

function servise_name(){
  return "無料から使えるクラウド採用管理、面接システム InterFree";
}

?>
