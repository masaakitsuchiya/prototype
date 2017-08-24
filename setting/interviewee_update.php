<?php
session_start();
include("../function/function.php");
include("../template/csrf_confirm.php");
login_check();

//UPDATE gs_an_table SET name='ジーズ', email='e@e.com',naiyou='あ' WHERE id =3
//1.POSTでParamを取得
if(
  !isset($_POST["job_post_id"]) || $_POST["job_post_id"]=="" ||
  !isset($_POST["last_name"]) || $_POST["last_name"]=="" ||
  !isset($_POST["first_name"]) || $_POST["first_name"]=="" ||
  !isset($_POST["last_name_kana"]) || $_POST["last_name_kana"]=="" ||
  !isset($_POST["first_name_kana"]) || $_POST["first_name_kana"]=="" ||
  // !isset($_POST["b_y"]) || $_POST["b_y"]=="" ||
  // !isset($_POST["b_m"]) || $_POST["b_m"]=="" ||
  // !isset($_POST["b_d"]) || $_POST["b_d"]=="" ||
  !isset($_POST["sex"]) || $_POST["sex"]==""
){
  exit('ParamError1');
}
$id = $_POST["interviewee_id"];
$job_post_id = $_POST["job_post_id"];
$last_name = $_POST["last_name"];
$first_name = $_POST["first_name"];
$last_name_kana = $_POST["last_name_kana"];
$first_name_kana = $_POST["first_name_kana"];
$b_y = $_POST["b_y"];
$b_m = $_POST["b_m"];
$b_d = $_POST["b_d"];
$sex = $_POST["sex"];
$mail = $_POST["mail"];
$postcode0 = $_POST["postcode0"];
$postcode1 = $_POST["postcode1"];
$address0 = $_POST["address0"];
$address1 = $_POST["address1"];
$address2 = $_POST["address2"];
$github = $_POST["github"];
$portfolio = $_POST["portfolio"];
$motivation  = $_POST["motivation"];

//姓名を連結
$interviewee_name = $last_name."　".$first_name;
//姓名かなを連結
$interviewee_name_kana = $last_name_kana."　".$first_name_kana;

//誕生日を連結
if(isset($b_y)&&isset($b_m)&&isset($b_d)){
  if($b_m <10){
    $b_m = '0'.$b_m;
  }
  if($b_d <10){
    $b_d = '0'.$b_d;
  }
  $birthday = $b_y.$b_m.$b_d;
}else{
  $birthday = "";
}

//postcodeを連結
$postcode = $postcode0."-".$postcode1;

//住所を連結
$address = $address0."\n".$address1."\n".$address2;
//

$resume0_url = "";
$resume1_url = "";
$resume2_url = "";
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
//2. DB接続します(エラー処理追加)
$pdo = db_con();


//３．データ登録SQL作成
$stmt = $pdo->prepare("UPDATE interviewee_info SET interviewee_name=:interviewee_name,interviewee_name_kana=:interviewee_name_kana,job_post_id=:job_post_id,birthday=:birthday,sex=:sex,mail=:mail,postcode=:postcode,address=:address,github=:github,portfolio=:portfolio,motivation=:motivation WHERE id=:id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
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
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}

if(isset($_FILES['resume0']) && $_FILES['resume0']['error']==0){
  $stmt_resume0 = $pdo->prepare("UPDATE interviewee_info SET resume0=:resume0 WHERE id=:id");
  $stmt_resume0->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt_resume0->bindValue(':resume0', $resume0_url, PDO::PARAM_STR);
  $status_resume0 = $stmt_resume0->execute();
  if($status_resume0==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error_resume0 = $stmt_resume0->errorInfo();
    exit("QueryError_resume0:".$error_resume0[2]);
  }
}

if(isset($_FILES['resume1']) && $_FILES['resume1']['error']==0){
  $stmt_resume1 = $pdo->prepare("UPDATE interviewee_info SET resume1=:resume1 WHERE id=:id");
  $stmt_resume1->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt_resume1->bindValue(':resume1', $resume1_url, PDO::PARAM_STR);
  $status_resume1 = $stmt_resume1->execute();
  if($status_resume1==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error_resume1 = $stmt_resume1->errorInfo();
    exit("QueryError_resume1:".$error_resume1[2]);
  }
}

if(isset($_FILES['resume2']) && $_FILES['resume2']['error']==0){
  $stmt_resume2 = $pdo->prepare("UPDATE interviewee_info SET resume2=:resume2 WHERE id=:id");
  $stmt_resume2->bindValue(':id', $id, PDO::PARAM_INT);
  $stmt_resume2->bindValue(':resume2', $resume2_url, PDO::PARAM_STR);
  $status_resume2 = $stmt_resume2->execute();
  if($status_resume2==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error_resume2 = $stmt_resume2->errorInfo();
    exit("QueryError_resume2:".$error_resume2[2]);
  }
}
  //５．index.phpへリダイレクト
  header("Location: interviewee_select.php");
  exit;




//3.UPDATE gs_an_table SET ....; で更新(bindValue)
//　基本的にinsert.phpの処理の流れです。




?>
