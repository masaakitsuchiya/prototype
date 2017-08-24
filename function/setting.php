<?php

// include("../function/function.php");


function profile_flg(){
  $pdo = db_con();
//２．データ登録SQL作成
$stmt_corp_info = $pdo->prepare("SELECT profile_flg FROM corp_info WHERE id =:id");
$stmt_corp_info->bindValue(':id',1,PDO::PARAM_INT);
$status_corp_info = $stmt_corp_info->execute();

//３．データ表示
if($status_corp_info==false){
  //execute（SQL実行時にエラーがある場合）
  $error_corp_info = $stmt_corp_info->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res_corp_info = $stmt_corp_info->fetch();
}




  return $res_corp_info["profile_flg"];
}

function corp_info_array(){
  $pdo = db_con();
  $stmt_corp_info = $pdo->prepare("SELECT * FROM corp_info");
  $status_corp_info = $stmt_corp_info->execute();

  //３．データ表示
  if($status_corp_info ==false){
    //execute（SQL実行時にエラーがある場合）
    queryError_corp_info($stmt_corp_info);
  }else{
    $res_corp_info = $stmt_corp_info->fetch();
  }
return $res_corp_info;
}

$JD_OPEN = 0;
//0非公開、１公開


?>
