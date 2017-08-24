<?php
include("../function/function.php");
//入力チェック(受信確認処理追加)


if(
  !isset($_POST["job_post_id"]) || $_POST["job_post_id"]=="" ||
  !isset($_POST["job_title"]) || $_POST["job_title"]=="" ||
  !isset($_POST["last_name"]) || $_POST["last_name"]=="" ||
  !isset($_POST["first_name"]) || $_POST["first_name"]=="" ||
  !isset($_POST["last_name_kana"]) || $_POST["last_name_kana"]=="" ||
  !isset($_POST["first_name_kana"]) || $_POST["first_name_kana"]=="" ||
  !isset($_POST["b_y"]) || $_POST["b_y"]=="" ||
  !isset($_POST["b_m"]) || $_POST["b_m"]=="" ||
  !isset($_POST["b_d"]) || $_POST["b_d"]=="" ||
  !isset($_POST["sex"]) || $_POST["sex"]=="" ||
  !isset($_POST["mail"]) || $_POST["mail"]=="" ||
  !isset($_POST["mail_confirm"]) || $_POST["mail_confirm"]=="" ||
  !isset($_POST["postcode0"]) || $_POST["postcode0"]=="" ||
  !isset($_POST["postcode1"]) || $_POST["postcode1"]=="" ||
  !isset($_POST["address0"]) || $_POST["address0"]=="" ||
  !isset($_POST["address1"]) || $_POST["address1"]=="" ||
  !isset($_POST["motivation"]) || $_POST["motivation"]==""
){
  exit('ParamError');
}

//1. POSTデータ取得
        // フォームから送信されたデータを各変数に格納
$job_post_id = $_POST["job_post_id"];
$job_title = $_POST["job_title"];
$last_name = $_POST["last_name"];
$first_name = $_POST["first_name"];
$last_name_kana = $_POST["last_name_kana"];
$first_name_kana = $_POST["first_name_kana"];
$b_y = $_POST["b_y"];
$b_m = $_POST["b_m"];
$b_d = $_POST["b_d"];
$sex = $_POST["sex"];
$mail = $_POST["mail"];
$mail_confirm = $_POST["mail_confirm"];
$postcode0 = $_POST["postcode0"];
$postcode1 = $_POST["postcode1"];
$address0 = $_POST["address0"];
$address1 = $_POST["address1"];
$address2 = $_POST["address2"];
$github = $_POST["github"];
$portfolio = $_POST["portfolio"];
$motivation  = $_POST["motivation"];


$resume0_url = "";
$resume1_url = "";
$resume2_url = "";
//1.アップロードが正常に行われたかチェック
//isset();でファイルが送られてきてるかチェック！そしてErrorが発生してないかチェック
if(isset($_FILES['resume0']) && $_FILES['resume0']['error']==0){

    //2. アップロード先とファイル名を作成
    $resume0_url = "../attached_data/".$_FILES["resume0"]["name"];

    // アップロードしたファイルを指定のパスへ移動
    //move_uploaded_file("一時保存場所","成功後に正しい場所に移動");
    if (move_uploaded_file($_FILES["resume0"]['tmp_name'],$resume0_url)){

        //パーミッションを変更（ファイルの読み込み権限を付けてあげる）
        chmod($resume0_url,0644);//チェンジモディファイ
    }
}
if(isset($_FILES['resume1']) && $_FILES['resume1']['error']==0){

    //2. アップロード先とファイル名を作成
    $resume1_url = "../attached_data/".$_FILES["resume1"]["name"];

    // アップロードしたファイルを指定のパスへ移動
    //move_uploaded_file("一時保存場所","成功後に正しい場所に移動");
    if (move_uploaded_file($_FILES["resume0"]['tmp_name'],$resume1_url)){

        //パーミッションを変更（ファイルの読み込み権限を付けてあげる）
        chmod($resume1_url,0644);//チェンジモディファイ
    }

}
if(isset($_FILES['resume2']) && $_FILES['resume2']['error']==0){

    //2. アップロード先とファイル名を作成
    $resume2_url = "../attached_data/".$_FILES["resume2"]["name"];

    // アップロードしたファイルを指定のパスへ移動
    //move_uploaded_file("一時保存場所","成功後に正しい場所に移動");
    if (move_uploaded_file($_FILES["resume2"]['tmp_name'],$resume2_url)){

        //パーミッションを変更（ファイルの読み込み権限を付けてあげる）
        chmod($resume2_url,0644);//チェンジモディファイ
    }

}

//姓名を連結
$interviewee_name = $last_name."　".$first_name;
//姓名かなを連結
$interviewee_name_kana = $last_name_kana."　".$first_name_kana;

//誕生日を連結
$birthday = $b_y."-".$b_m."-".$b_d;
//postcodeを連結
$postcode = $postcode0."-".$postcode1;

//住所を連結
$address = $address0."\n".$address1."\n".$address2;
//

//2. DB接続します(エラー処理追加)
$pdo = db_con();

//３．データ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO interviewee_info(id,interviewee_name,interviewee_name_kana,job_post_id,birthday,sex,mail,postcode,address,github,portfolio,motivation,resume0,resume1,resume2,indate
)VALUES(NULL,:interviewee_name,:interviewee_name_kana,:job_post_id,:birthday,:sex,:mail,:postcode,:address,:github,:portfolio,:motivation,:resume0,:resume1,:resume2,sysdate())");
$stmt->bindValue(':interviewee_name', $interviewee_name, PDO::PARAM_STR);
$stmt->bindValue(':interviewee_name_kana', $interviewee_name_kana, PDO::PARAM_STR);
$stmt->bindValue(':job_post_id', $job_post_id, PDO::PARAM_INT);
$stmt->bindValue(':birthday', $birthday, PDO::PARAM_STR);
$stmt->bindValue(':sex', $sex, PDO::PARAM_INT);
$stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
$stmt->bindValue(':postcode', $postcode, PDO::PARAM_STR);
$stmt->bindValue(':address', $address, PDO::PARAM_STR);
$stmt->bindValue(':github', $github, PDO::PARAM_STR);
$stmt->bindValue(':portfolio', $portfolio, PDO::PARAM_STR);
$stmt->bindValue(':motivation', $motivation, PDO::PARAM_STR);
$stmt->bindValue(':resume0', $resume0_url, PDO::PARAM_STR);
$stmt->bindValue(':resume1', $resume1_url, PDO::PARAM_STR);
$stmt->bindValue(':resume2', $resume2_url, PDO::PARAM_STR);

$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  queryError($stmt);

}else{
  //５．index.phpへリダイレクト
  header("Location: job_post_view_form_thanks.php");
  exit;
}
?>
