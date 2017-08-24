<?php
include("../function/function.php");
session_start();
$cancel_reason = $_GET["cancel_reason"];
$comment = $_GET["comment"];
$html_title = servise_name();
?>

<!DOCTYPE html>
<html>
<head>
<?php include("../template/head_for_interviewee.php") ?>
<style>
.row{
  margin-top:30px;
  margin-bottom:30px;
}
</style>
</head>
<body>
<?php include("../template/nav_for_interviewee.php"); ?>
<p class="text-center">ご連絡ありがとうございます。
以下の内容で連絡しました。</p>
<div class="container">
  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
      <h4 class="text-center">選択理由</h4><?php if($cancel_reason == "not_work"):?>
      <div class="text-center">ウェブ面接機能が動作しない。</div>
      <?php elseif($cancel_reason == "not_wish"):?>
      <div class="text-center">ウェブ面接を希望しない。</div>
      <?php elseif($cancel_reason == "not_available"):?>
      <div class="text-center">対応可能な日時がない</div>
      <?php endif; ?>
    </div>
    <div class="col-sm-2"></div>
  </div>
  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
      <h4 class="text-center">コメント</h4>
      <p class="text-center"><?php echo h($comment); ?></p>
    </div>
    <div class="col-sm-2"></div>
  </div>
</div>

<p class="text-center">
確認の上再度ご連絡いたしますのでしばらくおまちください。
</p>
<?php include("../template/footer_for_interviewee.html") ?>
</body>
</html>
