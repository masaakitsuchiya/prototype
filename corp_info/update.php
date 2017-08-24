<?php

session_start();
include("../function/function.php");
login_check();
kanri_check();
//入力チェック(受信確認処理追加)


//1. POSTデータ取得
$info_text      = $_POST["info_text"];
$corp_url       = $_POST["corp_url"];
$address        = $_POST["address"];
$tel            = $_POST["tel"];



//1.アップロードが正常に行われたかチェック
//isset();でファイルが送られてきてるかチェック！そしてErrorが発生してないかチェック
if(isset($_FILES['catch_photo_f']) && $_FILES['catch_photo_f']['error']==0){

    //2. アップロード先とファイル名を作成
    $catch_photo = "../corp_info_data/".$_FILES["catch_photo_f"]["name"];

    // アップロードしたファイルを指定のパスへ移動
    //move_uploaded_file("一時保存場所","成功後に正しい場所に移動");
    if (move_uploaded_file($_FILES["catch_photo_f"]['tmp_name'],$catch_photo)){

        //パーミッションを変更（ファイルの読み込み権限を付けてあげる）
        chmod($catch_photo,0644);//チェンジモディファイ
    }
}

if(isset($_FILES['up_pdf_f']) && $_FILES['up_pdf_f']['error']==0){

    //2. アップロード先とファイル名を作成
    $up_pdf = "../pdfjs/".$_FILES["up_pdf_f"]["name"];

    // アップロードしたファイルを指定のパスへ移動
    //move_uploaded_file("一時保存場所","成功後に正しい場所に移動");
    if (move_uploaded_file($_FILES["up_pdf_f"]['tmp_name'],$up_pdf)){

        //パーミッションを変更（ファイルの読み込み権限を付けてあげる）
        chmod($up_pdf,0644);//チェンジモディファイ
    }
}

if(isset($_FILES['company_video_f']) && $_FILES['company_video_f']['error']==0){

    //2. アップロード先とファイル名を作成
    $company_video = "../corp_info_data/".$_FILES["company_video_f"]["name"];

    // アップロードしたファイルを指定のパスへ移動
    //move_uploaded_file("一時保存場所","成功後に正しい場所に移動");
    if (move_uploaded_file($_FILES["company_video_f"]['tmp_name'],$company_video)){

        //パーミッションを変更（ファイルの読み込み権限を付けてあげる）
        chmod($company_video,0644);//チェンジモディファイ
    }
}

//2. DB接続します(エラー処理追加)
$pdo = db_con();

//３．データ登録SQL作成
//３．データ登録SQL作成
if(isset($info_text)){
$stmt = $pdo->prepare("UPDATE corp_info SET info_text=:info_text WHERE id=:id");
$stmt->bindValue(':id', 1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':info_text', $info_text, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status_info_text = $stmt->execute();
}
if(isset($corp_url)){
$stmt = $pdo->prepare("UPDATE corp_info SET corp_url=:corp_url WHERE id=:id");
$stmt->bindValue(':id', 1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':corp_url', $corp_url, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status_corp_url = $stmt->execute();
}
if(isset($address)){
$stmt = $pdo->prepare("UPDATE corp_info SET address=:address WHERE id=:id");
$stmt->bindValue(':id', 1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':address', $address, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status_address = $stmt->execute();
}

if(isset($tel)){
$stmt = $pdo->prepare("UPDATE corp_info SET tel=:tel WHERE id=:id");
$stmt->bindValue(':id', 1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':tel', $tel, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status_tel = $stmt->execute();
}
if(isset($catch_photo)){
$stmt = $pdo->prepare("UPDATE corp_info SET catch_photo=:catch_photo WHERE id=:id");
$stmt->bindValue(':id', 1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':catch_photo', $catch_photo, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status_catch_photo = $stmt->execute();
}
if(isset($up_pdf)){
$stmt = $pdo->prepare("UPDATE corp_info SET up_pdf=:up_pdf WHERE id=:id");
$stmt->bindValue(':id', 1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':up_pdf', $up_pdf, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status_up_pdf = $stmt->execute();
}
if(isset($company_video)){
$stmt = $pdo->prepare("UPDATE corp_info SET company_video=:company_video WHERE id=:id");
$stmt->bindValue(':id', 1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':company_video', $company_video, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status_company_video = $stmt->execute();
}


if(isset($info_text) AND $status_info_text==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_info_text:".$error[2]);
}
if(isset($corp_url) AND $status_corp_url==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_corp_url:".$error[2]);
}
if(isset($address) AND $status_address==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_address:".$error[2]);
}
if(isset($tel) AND $status_tel==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_tel:".$error[2]);
}
if(isset($catch_photo) AND $status_catch_photo==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_catch_photo:".$error[2]);
}
if(isset($up_pdf) AND $status_up_pdf==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_up_pdf:".$error[2]);
}
if(isset($company_video) AND $status_company_video==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError_company_video:".$error[2]);
}

//４．データ登録処理後



  //５．index.phpへリダイレクト
  header("Location:index.php");
  exit;

?>
