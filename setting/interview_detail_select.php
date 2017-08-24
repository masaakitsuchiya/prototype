<?php
session_start();
include("../function/function.php");

login_check();

$interviewee_id = $_GET["target_interviewee_id"];
//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成 該当の候補者情報の抽出
$stmt = $pdo->prepare("SELECT * FROM interviewee_info where id=:id");
$stmt->bindValue(':id',$interviewee_id,PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res = $stmt->fetch();
}


//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM interview WHERE interviewee_id = :interviewee_id");
$stmt->bindValue(':interviewee_id',$interviewee_id, PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
$interview_type = array("書類選考","1次面接","2次面接","3次面接");
$view="";
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view .= '<tr>';
    $view .= '<td><span class="glyphicon glyphicon-file"></span></td>';
    $view .= '<td>'.$interview_type[h($result["interview_type"])].'</td>';
    $view .= '<td>'.h($result["interview_date_time"]).'</td>';
    $view .= '<td>';
      // $stmt_interviewer = $pdo->prepare("SELECT * FROM interviewer_list WHERE interview_id = :interview_id");
      // $stmt_interviewer->bindvalue(':interview_id',$result["id"], PDO::PARAM_INT);
      // $status_interviewer = $stmt_interviewer->execute();
      // // $view_interviewer="";
      // if($status_interviewer==false){
      //   //execute（SQL実行時にエラーがある場合）
      //   $error_interviewer = $stmt_interviewer->errorInfo();
      //   exit("ErrorQuery:".$error_interviewer[2]);
      //
      // }else{
      //   while($result_interviewer = $stmt_interviewer->fetch(PDO::FETCH_ASSOC)){
      //     $view .= h($result_interviewer["interviewer_name"]);
      //     $view .= " ";
      //   }
      // }
      $stmt_interviewer = $pdo->prepare("SELECT * FROM interviewer_list,interviewer_info WHERE interviewer_list.interview_id = :interview_id AND interviewer_list.interviewer_id = interviewer_info.id");
      $stmt_interviewer->bindvalue(':interview_id',$result["id"], PDO::PARAM_INT);
      $status_interviewer = $stmt_interviewer->execute();
      // $view_interviewer="";
      if($status_interviewer==false){
        //execute（SQL実行時にエラーがある場合）
        $error_interviewer = $stmt_interviewer->errorInfo();
        exit("ErrorQuery:".$error_interviewer[2]);

      }else{
        while($result_interviewer = $stmt_interviewer->fetch(PDO::FETCH_ASSOC)){
          $view .= h($result_interviewer["interviewer_name"]);
          $view .= " ";
        }
      }

    $view .= '</td>';
    $view .= '<td>';
    $view .= '<a class="btn btn-xs btn-primary" href="interview_detail_select_detail.php?interview_id='.h($result["id"]).'&interviewee_name='.h($res["interviewee_name"]).'">修正</a> ';
    $view .= '<a class="btn btn-xs btn-danger" href="interview_detail_select_delete.php?interview_id='.h($result["id"]).'&interviewee_name='.h($res["interviewee_name"]).'">削除</a>';
    $view .= '</td>';
    $view .= '</tr>';
  }
}




$html_title = servise_name();

?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>

.container{
  margin-bottom:20px;
}


</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">選考設定</h3>
<div class="container">
<div class="row">
  <div class="col-sm-offset-9 col-sm-2 text-center"><a class="btn btn-sm btn-default" href="interview_setting.php?target_interviewee_id=<?= $res["id"] ?>">新規登録</a></div>
  </div>
</div>
<div class="container">
  <h3 class="text-center">候補者名:<?= h($res["interviewee_name"]) ?></h3>
</div>
<div class="container">
  <table class="table table-hover">
    <tr><th></th><th>選考区分</th><th>日時</th><th>選考担当者</th><th>処理</th><tr>
    <?=$view?>
  </table>
</div>
<?php include("../template/footer.html") ?>

</script>
</body>
</html>
