<?php
session_start();
include("../function/function.php");
login_check();

if(isset($_POST["form_id"])){
$_SESSION["form_id"] = $_POST["form_id"];
}

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt_interviewee = $pdo->prepare("SELECT * FROM interviewee_info WHERE id = :interviewee_id");
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
$default_message_for_interviewee  = '';
$default_message_for_interviewee .= " この度は弊社求人にご応募いただきありがとうございます。\n選考をスムーズに進めるために幾つか質問させていただきたいと存じます。\n";
$default_message_for_interviewee .= "お忙しいところ大変恐縮ですが、ご確認いただき、以下の提出期限までに本入力フォームよりご返信ください。\n";
$default_message_for_interviewee .= "面接当日は送信いただきました内容を踏まえましてお話させていただきたいと存じます。\n";
$default_message_for_interviewee .= "何卒よろしくお願いいたします。";

$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">フリーアンケート送信</h3>
<?php include("../template/back_to_interviewee_select.php"); ?>
<h4 class="text-center">コメント入力・返信期限設定</h3>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form" action="questionnaire_setting03.php" method="post">
        <div class="form-group">
          <label class="control-label" for="anchet_message">アンケート上部表示するコメント</label>
          <textarea id = "text"class="form-control" name="anchet_message" rows="10" required><?= h($default_message_for_interviewee); ?>
          </textarea>
        </div>
        <div class="form-group">
          <label class="control-label" for="deadline">アンケート提出期限</label>
          <input type="date" name="deadline" required>
        </div>
        <div class="form-group text-center">
          <a class="btn btn-default" href="questionnaire_setting01.php?target_interviewee_id=<?=h($_SESSION["interviewee_id"]);?>">戻る</a>
          &emsp;
          <input id="submit_button" type="submit" class="btn btn-info" value="送信内容確認">
        </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<?php include("../template/footer.html") ?>

</body>
<!-- <script> -->
<!-- // $(function(){
// // まず改行らしき文字を\nに統一。\r、\r\n → \n
// $("#submit_button").on('click',function(){
// var txt = $('#text').val();
// txt = txt.replace(/\r\n/g, '\n');
// txt = txt.replace(/\r/g, '\n');
//
// // 改行を区切りにして入力されたテキストを分割して配列に保存する。
// var lines = txt.split('\n');
// var replacedText = $(lines).join<
//
// });
//
// }); -->
<!-- </script> -->
</html>
