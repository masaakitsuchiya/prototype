<?php
session_start();
include("../function/function.php");
include("../template/csrf_confirm.php");
login_check();


if(
  !isset($_GET["id"]) || $_GET["id"]=="" ||
  !isset($_POST["interviewer_name"]) || $_POST["interviewer_name"]=="" ||
  !isset($_POST["lid"]) || $_POST["lid"]=="" ||
  !isset($_POST["interviewer_mail"]) || $_POST["interviewer_mail"]=="" ||
  !isset($_POST["kanri_flg"]) || $_POST["kanri_flg"]=="" ||
  !isset($_POST["life_flg"]) || $_POST["life_flg"]==""
){
  exit('ParamError');
}

if(
  $_POST["lpw_confirm"] != $_POST["lpw"]
){
  exit('パスワードが一致しません');
}

//1.POSTでParamを取得
$interviewer_id     = $_GET["id"];
$interviewer_name   = $_POST["interviewer_name"];
$lid                = $_POST["lid"];
$pw           = $_POST["lpw"];
$interviewer_mail   = $_POST["interviewer_mail"];
$kanri_flg          = $_POST["kanri_flg"];
$life_flg           = $_POST["life_flg"];

$interviewer_profile = $_POST["interviewer_profile"];
$department = $_POST["department"];
$title = $_POST["title"];

if(isset($_FILES['interviewer_img_file']) && $_FILES['interviewer_img_file']['error']==0){

    //2. アップロード先とファイル名を作成
    $interviewer_img = "../interviewer_data/".$_FILES["interviewer_img_file"]["name"];

    // アップロードしたファイルを指定のパスへ移動
    //move_uploaded_file("一時保存場所","成功後に正しい場所に移動");
    if (move_uploaded_file($_FILES["interviewer_img_file"]['tmp_name'],$interviewer_img)){

        //パーミッションを変更（ファイルの読み込み権限を付けてあげる）
        chmod($interviewer_img,0644);//チェンジモディファイ
    }
}


if(isset($_FILES['interviewer_video_file']) && $_FILES['interviewer_video_file']['error']==0){

    //2. アップロード先とファイル名を作成
    $interviewer_video = "../interviewer_data/".$_FILES["interviewer_video_file"]["name"];

    // アップロードしたファイルを指定のパスへ移動
    //move_uploaded_file("一時保存場所","成功後に正しい場所に移動");
    if (move_uploaded_file($_FILES["interviewer_video_file"]['tmp_name'],$interviewer_video)){

        //パーミッションを変更（ファイルの読み込み権限を付けてあげる）
        chmod($interviewer_video,0644);//チェンジモディファイ
    }
}

//2. DB接続します(エラー処理追加)
$pdo = db_con();


//３．データ登録SQL作成
$stmt = $pdo->prepare("UPDATE interviewer_info SET interviewer_name=:interviewer_name,lid=:lid,interviewer_mail=:interviewer_mail,kanri_flg=:kanri_flg,life_flg=:life_flg WHERE id=:id");
$stmt->bindValue(':id', $interviewer_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':interviewer_name', $interviewer_name, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':lid', $lid, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':interviewer_mail', $interviewer_mail, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':kanri_flg', $kanri_flg, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':life_flg', $life_flg, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status_must = $stmt->execute();

if(isset($department)){
  $stmt = $pdo->prepare("UPDATE interviewer_info SET department=:department WHERE id=:id");
  $stmt->bindValue(':id', $interviewer_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':department', $department, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
  $status_department = $stmt->execute();
}
if(isset($title)){
  $stmt = $pdo->prepare("UPDATE interviewer_info SET title=:title WHERE id=:id");
  $stmt->bindValue(':id', $interviewer_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':title', $title, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
  $status_title = $stmt->execute();
}
if(isset($interviewer_profile)){
  $stmt = $pdo->prepare("UPDATE interviewer_info SET interviewer_profile=:interviewer_profile WHERE id=:id");
  $stmt->bindValue(':id', $interviewer_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':interviewer_profile', $interviewer_profile, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
  $status_interviewer_profile = $stmt->execute();
}

if(isset($interviewer_img)){
  $stmt = $pdo->prepare("UPDATE interviewer_info SET interviewer_img=:interviewer_img WHERE id=:id");
  $stmt->bindValue(':id', $interviewer_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':interviewer_img', $interviewer_img, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
  $status_interviewer_img = $stmt->execute();
}
if(isset($interviewer_video)){
  $stmt = $pdo->prepare("UPDATE interviewer_info SET interviewer_video=:interviewer_video WHERE id=:id");
  $stmt->bindValue(':id', $interviewer_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':interviewer_video', $interviewer_video, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
  $status_interviewer_video = $stmt->execute();
}
if(isset($pw)){
  $pw_hash = password_hash($pw,PASSWORD_DEFAULT);
  $stmt = $pdo->prepare("UPDATE interviewer_info SET lpw=:lpw WHERE id=:id");
  $stmt->bindValue(':id', $id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
  $stmt->bindValue(':lpw', $pw_hash, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
  $status_pw = $stmt->execute();
}



//４．データ登録処理後
if($status_must==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_must:".$error[2]);
}
if(isset($department) AND $status_department==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_department:".$error[2]);
}
if(isset($title) AND $status_title==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_title:".$error[2]);
}
if(isset($interviewer_profile) AND $status_interviewer_profile==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_profile:".$error[2]);
}
if(isset($interviewer_img) AND $status_interviewer_img==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_interviewer_img:".$error[2]);
}
if(isset($interviewer_video) AND $status_interviewer_video==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_interviewer_video:".$error[2]);
}
if(isset($pw) AND $status_pw==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_interviewer_pw:".$error[2]);
}

  //５．index.phpへリダイレクト
header("Location: interviewer_detail.php?id=".$interviewer_id);
exit;







?>
