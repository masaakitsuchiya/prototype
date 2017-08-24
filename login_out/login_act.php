<?php
session_start();

//0.外部ファイル読み込み
include("../function/function.php");


if(
  !isset($_POST["lid"]) || $_POST["lid"]==""||
  !isset($_POST["lpw"]) || $_POST["lpw"]==""
){
 header("Location: login.php");
  exit();
}

//1.  DB接続します
$pdo = db_con();
//lidをもとにpwのハッシュを取得
$stmt_pw_hash = $pdo->prepare("SELECT lpw FROM interviewer_info where lid=:lid");
$stmt_pw_hash->bindValue(':lid',$_POST["lid"],PDO::PARAM_STR);
$status_pw_hash = $stmt_pw_hash->execute();
if($status_pw_hash==false){
  //execute（SQL実行時にエラーがある場合）
  $error_pw_hash = $stmt_pw_hash->errorInfo();
  exit("ErrorQuery_pw_hash:".$error_pw_hash[2]);
}else{
  $res_pw_hash = $stmt_pw_hash->fetch();
}
// $password_check = password_verify($_POST["lpw"],$res_pw_hash["lpw"]);
// var_dump($password_check);
// exit();
if(!password_verify($_POST["lpw"],$res_pw_hash["lpw"])){
  header("Location: login.php");
  exit();
}

//2. データ登録SQL作成
$sql = "SELECT * from interviewer_info WHERE lid=:lid AND lpw=:lpw AND life_flg=0";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':lid', $_POST["lid"]);
$stmt->bindValue(':lpw', $res_pw_hash["lpw"]);
$STATUS = $stmt->execute();

//3. SQL実行時にエラーがある場合
if($STATUS==false){
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}

//4. 抽出データ数を取得
//$count = $stmt->fetchColumn(); //SELECT COUNT(*)で使用可能()
$val = $stmt->fetch(); //1レコードだけ取得する方法

//5. 該当レコードがあればSESSIONに値を代入
if( $val["id"] != "" ){
  $_SESSION["chk_ssid"]          = session_id();
  $_SESSION["user_id"]    = $val['id'];
  $_SESSION["user_name"]   = $val['interviewer_name'];
  $_SESSION["kanri_flg"]         = $val['kanri_flg'];
  $_SESSION["life_flg"]          = $val['life_flg'];

header("Location: ../top/index.php");

}else{
  //logout処理を経由して全画面へ
 header("Location: logout.php");
}
exit();
?>
