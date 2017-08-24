<?php
include("../function/function.php");
include("../function/setting.php");
session_start();
$stage_flg = $_GET["stage_flg"];
$html_title = servise_name();
$corp_info = corp_info_array();

$contact = "";
$contact .= h($corp_info["corp_name"])."<br>";
$contact .= h($corp_info["address"])."<br>";
$contact .= "tel:".h($corp_info["tel"])."<br>";
$contact .= "mail:".h($corp_info["corp_mail"])."<br>";
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
<p class="text-center"></p>
<div class="container">
  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
      <h4 class="text-center"></h4>
      <?php if($stage_flg == 0 || $stage_flg == 4 || $stage_flg == 5 || $stage_flg == 6): ?>
        <p class="text-center">無効なURLです。</p>
      <?php elseif($stage_flg == 2): ?>
        <p class="text-center">ご連絡をお待ちください。</p>
        <div class="text-center"><?=$contact?></div>
      <?php elseif($stage_flg == 3): ?>
        <p class="text-center">面接日時は確定しました。</p>
        <div class="text-center"><?=$contact?></div>
      <?php endif; ?>
    </div>
    <div class="col-sm-2"></div>
  </div>
</div>

<?php include("../template/footer_for_interviewee.html") ?>
</body>
</html>
