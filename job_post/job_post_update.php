<?php
session_start();
include("../function/function.php");
login_check();

//入力チェック(受信確認処理追加)

$job_post_id = $_POST["job_post_id"];

if(
  !isset($_POST["job_title"]) || $_POST["job_title"]=="" ||
  !isset($_POST["job_description"]) || $_POST["job_description"]=="" ||
  !isset($_POST["requirement"]) || $_POST["requirement"]=="" ||
  !isset($_POST["salary_sys"]) || $_POST["salary_sys"]=="" ||
  !isset($_POST["estimate_income"]) || $_POST["estimate_income"]=="" ||
  !isset($_POST["welfare"]) || $_POST["welfare"]=="" ||
  !isset($_POST["location"]) || $_POST["location"]=="" ||
  !isset($_POST["work_hour"]) || $_POST["work_hour"]=="" ||
  !isset($_POST["life_flg"]) || $_POST["life_flg"]==""

){
  exit('ParamError');
}

//1. POSTデータ取得
$job_title        = $_POST["job_title"];
$job_description  = $_POST["job_description"];
$requirement  = $_POST["requirement"];
$salary_sys    = $_POST["salary_sys"];
$estimate_income  = $_POST["estimate_income"];
$welfare          = $_POST["welfare"];
$location         = $_POST["location"];
$work_hour        = $_POST["work_hour"];
$etc              = $_POST["etc"];
$life_flg         = $_POST["life_flg"];

//1.アップロードが正常に行われたかチェック
//isset();でファイルが送られてきてるかチェック！そしてErrorが発生してないかチェック
if(isset($_FILES['job_img_f']) && $_FILES['job_img_f']['error']==0){

    //2. アップロード先とファイル名を作成
    $job_img = "./data/".$_FILES["job_img_f"]["name"];

    // アップロードしたファイルを指定のパスへ移動
    //move_uploaded_file("一時保存場所","成功後に正しい場所に移動");
    if (move_uploaded_file($_FILES["job_img_f"]['tmp_name'],$job_img)){

        //パーミッションを変更（ファイルの読み込み権限を付けてあげる）
        chmod($job_img,0644);//チェンジモディファイ
    }
}


//2. DB接続します(エラー処理追加)
$pdo = db_con();


//３．データ登録SQL作成

if($job_img){
  $stmt = $pdo->prepare("UPDATE job_post SET job_title = :job_title, job_img= :job_img, job_description = :job_description, requirement = :requirement, salary_sys = :salary_sys, estimate_income = :estimate_income, welfare = :welfare, location = :location, work_hour = :work_hour, etc = :etc, life_flg = :life_flg WHERE id=:id");
  $stmt->bindValue(':id', $job_post_id, PDO::PARAM_STR);
  $stmt->bindValue(':job_title', $job_title, PDO::PARAM_STR);
  $stmt->bindValue(':job_img', $job_img, PDO::PARAM_STR);
  $stmt->bindValue(':job_description', $job_description, PDO::PARAM_STR);
  $stmt->bindValue(':requirement', $requirement, PDO::PARAM_STR);
  $stmt->bindValue(':salary_sys', $salary_sys, PDO::PARAM_STR);
  $stmt->bindValue(':estimate_income', $estimate_income, PDO::PARAM_STR);
  $stmt->bindValue(':welfare', $welfare, PDO::PARAM_STR);
  $stmt->bindValue(':location', $location, PDO::PARAM_STR);
  $stmt->bindValue(':work_hour', $work_hour, PDO::PARAM_STR);
  $stmt->bindValue(':etc', $etc, PDO::PARAM_STR);
  $stmt->bindValue(':life_flg', $life_flg, PDO::PARAM_INT);
  $status = $stmt->execute();

  //４．データ登録処理後
  if($status==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
  }else{
    //５．index.phpへリダイレクト
    header("Location: job_post_select.php");
    exit;
  }

}else{
  $stmt = $pdo->prepare("UPDATE job_post SET job_title = :job_title, job_description = :job_description, requirement = :requirement, salary_sys = :salary_sys, estimate_income = :estimate_income, welfare = :welfare, location = :location, work_hour = :work_hour, etc = :etc, life_flg = :life_flg WHERE id=:id");
  $stmt->bindValue(':id', $job_post_id, PDO::PARAM_STR);
  $stmt->bindValue(':job_title', $job_title, PDO::PARAM_STR);
  $stmt->bindValue(':job_description', $job_description, PDO::PARAM_STR);
  $stmt->bindValue(':requirement', $requirement, PDO::PARAM_STR);
  $stmt->bindValue(':salary_sys', $salary_sys, PDO::PARAM_STR);
  $stmt->bindValue(':estimate_income', $estimate_income, PDO::PARAM_STR);
  $stmt->bindValue(':welfare', $welfare, PDO::PARAM_STR);
  $stmt->bindValue(':location', $location, PDO::PARAM_STR);
  $stmt->bindValue(':work_hour', $work_hour, PDO::PARAM_STR);
  $stmt->bindValue(':etc', $etc, PDO::PARAM_STR);
  $stmt->bindValue(':life_flg', $life_flg, PDO::PARAM_INT);
  $status = $stmt->execute();

  //４．データ登録処理後
  if($status==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
  }else{
    //５．index.phpへリダイレクト
    header("Location: job_post_select.php");
    exit;
  }

}



//3.UPDATE gs_an_table SET ....; で更新(bindValue)
//　基本的にinsert.phpの処理の流れです。




?>
