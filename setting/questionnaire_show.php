<?php
session_start();
include("../function/function.php");
$anchet_id = $_GET["anchet_id"];


$pdo = db_con();

//２．データ登録SQL作成
$stmt_question = $pdo->prepare("SELECT * FROM detail_question WHERE anchet_id = :anchet_id ORDER BY question_order ASC");
$stmt_question->bindValue(':anchet_id',$anchet_id,PDO::PARAM_INT);
$status_question = $stmt_question->execute();

//３．データ表示
$question_answer_view="";
if($status_question==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_question->errorInfo();
  exit("ErrorQuery_question:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_question = $stmt_question->fetch(PDO::FETCH_ASSOC)){
    $question_answer_view .= '<div class="reply_item">';
    $question_answer_view .= '<p class="text-left"><i class="glyphicon glyphicon-question-sign"></i>'.h($result_question["question"]).'</p>';
    $question_answer_view .= '<ul class="list-unstyled answer_item">';
    //回答を検索
      $stmt_answer = $pdo->prepare("SELECT * FROM detail_answer WHERE detail_question_id = :detail_question_id");
      $stmt_answer->bindValue(':detail_question_id',h($result_question["detail_question_id"]),PDO::PARAM_INT);
      $status_answer = $stmt_answer->execute();
      //３．データ表示
      if($status_answer==false){
        //execute（SQL実行時にエラーがある場合）
        $error = $stmt_answer->errorInfo();
        exit("ErrorQuery_answer:".$error[2]);
      }else{
        //Selectデータの数だけ自動でループしてくれる
        while( $result_answer = $stmt_answer->fetch(PDO::FETCH_ASSOC)){
          $question_answer_view.= '<li>'.h($result_answer["answer"]).'<li>';
        }
      }
    $question_answer_view .= '</ul>';
    $question_answer_view .= '</div>';

  }
}
//回答者の名前をだす
$stmt_interviewee_name = $pdo->prepare("SELECT interviewee_info.interviewee_name FROM interviewee_info INNER JOIN anchet ON anchet.interviewee_id = interviewee_info.id where anchet.anchet_id=:anchet_id");
$stmt_interviewee_name->bindValue(':anchet_id',$anchet_id,PDO::PARAM_INT);
$status_interviewee_name = $stmt_interviewee_name->execute();

//３．データ表示
if($status_interviewee_name==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interviewee_name->errorInfo();
  exit("ErrorQuery_interviewee_name:".$error[2]);
}else{
  $res_interviewee_name = $stmt_interviewee_name->fetch();
}
$html_title = servise_name();

?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
.reply_item{
  margin-bottom:40px;

}
.answer_item{

}
</style>
<body>
<?php include("../template/nav.php") ?>
<div class="container">
  <h2 class="text-center" style="margin-bottom:30px;">
    アンケート回答内容
  </h2>

  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8 text-center">
      <h4 class="text-center" style="margin-bottom:30px;">
      回答者:<?= h($res_interviewee_name["interviewee_name"]); ?>
      </h3>
      <?= $question_answer_view ?>
    </div>
    <div class="col-sm-2"></div>
  </div>
</div>

<?php include("../template/footer.html") ?>

</body>
</html>
