<?php

session_start();
include("../function/function.php");
login_check();
//入力チェック(受信確認処理追加)


if(
  !isset($_POST["corp_name"]) || $_POST["corp_name"]=="" ||
  !isset($_POST["corp_name_en"]) || $_POST["corp_name_en"]=="" ||
  !isset($_POST["main_title_text"]) || $_POST["main_title_text"]=="" ||
  !isset($_POST["corp_color"]) || $_POST["corp_color"]=="" ||
  !isset($_POST["main_lead_text"]) || $_POST["main_lead_text"]==""
){
  exit('ParamError');
}

//1. POSTデータ取得
$corp_name        = $_POST["corp_name"];
$corp_name_en  = $_POST["corp_name_en"];
$main_title_text  = $_POST["main_title_text"];
$main_lead_text    = $_POST["main_lead_text"];
$corp_color = $_POST["corp_color"];

//1.アップロードが正常に行われたかチェック
//isset();でファイルが送られてきてるかチェック！そしてErrorが発生してないかチェック
if(isset($_FILES['corp_logo_f']) && $_FILES['corp_logo_f']['error']==0){

    //2. アップロード先とファイル名を作成
    $corp_logo = "./img/".$_FILES["corp_logo_f"]["name"];

    // アップロードしたファイルを指定のパスへ移動
    //move_uploaded_file("一時保存場所","成功後に正しい場所に移動");
    if (move_uploaded_file($_FILES["corp_logo_f"]['tmp_name'],$corp_logo)){

        //パーミッションを変更（ファイルの読み込み権限を付けてあげる）
        chmod($corp_logo,0644);//チェンジモディファイ
    }
}

if(isset($_FILES['main_photo_f']) && $_FILES['main_photo_f']['error']==0){

    //2. アップロード先とファイル名を作成
    $main_photo = "./img/".$_FILES["main_photo_f"]["name"];

    // アップロードしたファイルを指定のパスへ移動
    //move_uploaded_file("一時保存場所","成功後に正しい場所に移動");
    if (move_uploaded_file($_FILES["main_photo_f"]['tmp_name'],$main_photo)){

        //パーミッションを変更（ファイルの読み込み権限を付けてあげる）
        chmod($main_photo,0644);//チェンジモディファイ
    }
}


//2. DB接続します(エラー処理追加)
$pdo = db_con();

//３．データ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO corp_apply(id, corp_name, corp_name_en, main_title_text, main_lead_text, main_photo, corp_logo,corp_color
)VALUES(NULL, :corp_name, :corp_name_en, :main_title_text, :main_lead_text, :main_photo, :corp_logo,:corp_color)");
$stmt->bindValue(':corp_name', $corp_name, PDO::PARAM_STR);
$stmt->bindValue(':corp_name_en', $corp_name_en, PDO::PARAM_STR);
$stmt->bindValue(':main_title_text', $main_title_text, PDO::PARAM_STR);
$stmt->bindValue(':main_lead_text', $main_lead_text, PDO::PARAM_STR);
$stmt->bindValue(':main_photo', $main_photo, PDO::PARAM_STR);
$stmt->bindValue(':corp_logo', $corp_logo, PDO::PARAM_STR);
$stmt->bindValue(':corp_color', $corp_color, PDO::PARAM_STR);
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  queryError($stmt);

}else{
  //５．index.phpへリダイレクト
  header("Location: ../top/index.php");
  exit;
}
?>
