<?php
session_start();
include("../function/function.php");
login_check();

$_SESSION["interviewee_id"] = $_GET["target_interviewee_id"];

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt_interviewee = $pdo->prepare("SELECT * FROM interviewee_info INNER JOIN job_post ON interviewee_info.job_post_id = job_post.id WHERE interviewee_info.id=:interviewee_id");
$stmt_interviewee->bindValue(':interviewee_id', $_SESSION["interviewee_id"], PDO::PARAM_INT);
$status_interviewee = $stmt_interviewee->execute();

//３．データ表示
if($status_interviewee==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interviewee->errorInfo();
  exit("ErrorQuery_interviewee:".$error[2]);
}else{
  $res_interviewee = $stmt_interviewee->fetch();
}


//アンケートフォーム形式一覧
$stmt_form = $pdo->prepare("SELECT * FROM form WHERE life_flg=:life_flg");
$stmt_form->bindValue(':life_flg', 1, PDO::PARAM_INT);
$status_form = $stmt_form->execute();

$view_form_item = "";
if($status_form==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_form->errorInfo();
  exit("ErrorQuery_form:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_form = $stmt_form->fetch(PDO::FETCH_ASSOC)){
    $view_form_item .= '<tr>';
    $view_form_item .= '<td>';
    $view_form_item .= '<input type="radio" group="anchet_group" name="form_id" value="'.$result_form["form_id"].'">';
    $view_form_item .= '</td>';
    $view_form_item .= '<td>';
    $view_form_item .= $result_form["form_name"];
    $view_form_item .= '</td>';
    $view_form_item .= '<td>';
    $view_form_item .= $result_form["form_description"];
    $view_form_item .= '</td>';
    $view_form_item .= '<td>';
    $view_form_item .= '<a type="button" href="../questionnaire/show.php?form_id='.$result_form["form_id"].' "class="btn btn-sm btn-primary" target="_blank">確認</a>';
    $view_form_item .= '</td>';
    $view_form_item .= '</tr>';
  }
}

$html_title = '無料から使えるクラウド採用管理、面接システム Smart Interview';
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
.container{
  margin-bottom:30px;
}
</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">フリーアンケート送信</h3>
<?php include("../template/back_to_interviewee_select.php"); ?>
<!-- <h4 class="text-center">アンケート選択</h3> -->
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">

      <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
          <table class="table">
            <tr><th>送信先候補者</th><td><?= h($res_interviewee["interviewee_name"]); ?></td><tr>
            <tr><th>職種</th><td><?= h($res_interviewee["job_title"]); ?></td></th></tr>
          </table>
        </div>
        <div class="col-sm-3"></div>
      </div>

      <div class="anchet_items">
        <p class="text-center">送信するアンケート形式を選んでください。</div>
        <form class="form" method="post" action="questionnaire_setting02.php">
        <table class="table">
          <?=$view_form_item?>
        </table>
        <div class="text-center">

          <input type="submit" id="submit" class="btn btn-info" value="次へ">
        <form>
      </div>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<?php include("../template/footer.html") ?>
<script>
$(function(){
  $('#submit').attr('disabled', 'disabled');
  $("input[group='anchet_group']").click(function(){
    if($("input[group='anchet_group']:checked").length == 0){
      $('#submit').attr('disabled', 'disabled');
    }else{
        $('#submit').removeAttr('disabled');
    }
  });
});
</script>

</body>
</html>
