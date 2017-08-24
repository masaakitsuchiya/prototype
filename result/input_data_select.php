<?php
session_start();
include("../function/function.php");
login_check();

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM interviewer_list,interview where interviewer_list.interviewer_id = :id AND interview.id=interviewer_list.interview_id");
$stmt->bindValue(':id',$_SESSION["user_id"],PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
$view="";
$interview_type = array("書類選考","1次面接","2次面接","3次面接");
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    // var_dump($result);
    $view .='<tr>';
    $view .='<td><span class="glyphicon glyphicon-user"><span>';
    // interviewee名前抽出
        $stmt2 = $pdo->prepare("SELECT interviewee_name FROM interviewee_info where id=:id");
        $stmt2->bindValue(':id',$result["interviewee_id"],PDO::PARAM_INT);
        $status2 = $stmt2->execute();
        if($status2==false){
          //execute（SQL実行時にエラーがある場合）
          $error2 = $stmt2->errorInfo();
          exit("ErrorQuery:".$error2[2]);
        }else{
          $res = $stmt2->fetch();
        }
    $view .='<td>'.h($res["interviewee_name"]).'</td>';
    $view .='<td>'.$interview_type[h($result["interview_type"])].'</td>';
    $view .='<td>'.h($result["interview_date_time"]).'</td>';
    //自分が入力済みか確認
        $stmt3 = $pdo->prepare("SELECT id FROM interview_result where interview_id=:interview_id AND interviewer_id = :interviewer_id");
        $stmt3->bindValue(':interview_id',$result["interview_id"],PDO::PARAM_INT);
        $stmt3->bindValue(':interviewer_id',$_SESSION["user_id"],PDO::PARAM_INT);
        $status3 = $stmt3->execute();
        if($status3==false){
          //execute（SQL実行時にエラーがある場合）
          $error3 = $stmt3->errorInfo();
          exit("ErrorQuery:".$error3[2]);
        }else{
          $res3 = $stmt3->fetch();
        }
      //だれかひどりでも入力しているか確認
        $stmt4 = $pdo->prepare("SELECT id FROM interview_result where interview_id=:interview_id");
        $stmt4->bindValue(':interview_id',$result["interview_id"],PDO::PARAM_INT);
        $status4 = $stmt4->execute();
        if($status4==false){
          //execute（SQL実行時にエラーがある場合）
          $error4 = $stmt4->errorInfo();
          exit("ErrorQuery:".$error4[2]);
        }else{
          $res4 = $stmt4->fetch();
        }
    if(!$res3 && !$res4){
      //誰も入力していない　[入力]
    $view .='<td><a href="input_data.php?interview_id='.h($result["interview_id"]).'&interviewee_id='.h($result["interviewee_id"]).'" class="btn btn-xs btn-primary">入力</a></td>';
    }elseif(!$res3 && $res4){
      //だれかが入力している。かつ自分は入力していない　[入力][閲覧]
    $view .='<td><a href="input_data.php?interview_id='.h($result["interview_id"]).'&interviewee_id='.h($result["interviewee_id"]).'" class="btn btn-xs btn-primary">入力</a> <a href="output_data.php?interview_id='.h($result["interview_id"]).'&interviewee_id='.h($result["interviewee_id"]).'" class="btn btn-xs btn-warning">閲覧</a></td>';
    }else{
    //自分が入力している
    $view .='<td><a href="input_data_detail.php?interview_id='.h($result["interview_id"]).'&interviewee_id='.h($result["interviewee_id"]).'" class="btn btn-xs btn-info">修正</a> <a href="output_data.php?interview_id='.h($result["interview_id"]).'&interviewee_id='.h($result["interviewee_id"]).'" class="btn btn-xs btn-warning">閲覧</a></td>';
  }

    $view .='</tr>';

  }
}
$html_title = servise_name();

?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>

html,body{
  height: 100%;
}



</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">評価入力</h3>

<div class="container">
  <table class="table table-hover">
    <?=$view?>
  </table>
</div>
<?php include("../template/footer.html") ?>
<!-- <script>
  $(function(){
      $('#to_output_data').click(function() {
          $('#form').attr('action', 'output_data.php');
          $('#form').submit();
      });
      $('#to_input_data').click(function() {
          $('#form').attr('action', 'input_data.php');
          $('#form').submit();
      });
  }); -->
</script>
</body>
</html>
