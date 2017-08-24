<?php

session_start();
include("../function/function.php");
login_check();
kanri_check();

$pdo = db_con();

$stmt = $pdo->prepare("SELECT * FROM corp_info");
$status_corp_info = $stmt->execute();

//３．データ表示
if($status_corp_info==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery_corp_info:".$error[2]);
}else{
  $res_corp_info = $stmt->fetch();
}
if($res_corp_info["up_pdf"]){
$pdf_url_for_iframe = url_folder_name_remove("pdfjs",h($res_corp_info["up_pdf"]));
}
$url_for_pdf = path_for_mail();
$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
span.interview_info{
  font-size:2.5em;
}
.interviewer_img{
  height: 120px;
}
div.interview_info{
  background:#fff;
  margin-bottom:30px;
  padding-top:20px;
  padding-bottom:20px;
}
.interviewer_item{
  padding-top:10px;
  padding-bottom:10px;
}
.video-responsive{
  max-width: 100%;
  height: auto;
}
.content_item{
  margin-bottom: 30px;
}
.content_sub_item{
    margin-bottom: 15px;
}
h2.title{
  margin-bottom:30px;
}
h3.item_title{
  margin-bottom:50px;
}
div.item_s{
  margin-bottom:15px;
}
</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<div class="container interview_info_all" style="display:none;">
  <h2 class="text-center title" id="interview_item">面接情報</h2>
</div>


<div class="container-fruid">
  <div class="row content_item">
    <div class="row">
      <div class="col-sm-1"></div>
      <div class="col-sm-10 text-right"><a href="detail.php" class="btn btn-default">編集する</a></div>
      <div class="col-sm-1"></div>
    </div>
    <h2 class="text-center title" id="info_top">会社情報</h2>
    <h3 class="text-center title"><?= h($res_corp_info["corp_name"]); ?></h2>
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="row content_sub_item">
        <div class="col-sm-5"></div>
        <div class="col-sm-2 text-center"><img class="img-responsive center-block" src="<?= h($res_corp_info["catch_photo"]); ?>" alt=""></div>
        <div class="col-sm-5"></div>
      </div>
      <div class="row content_sub_item">
        <div class="col-sm-5"></div>
        <div class="col-sm-2 text-center"><a class="btn btn-success btn-sm" href="<?=h($res_corp_info["corp_url"]);?>" target="_blank">corprate site</a></div>
        <div class="col-sm-5"></div>
      </div>
      <div class="row content_sub_item">
        <div class="col-sm-3"></div>
        <div class="col-sm-6"><?= hd($res_corp_info["info_text"]);?></div>
        <div class="col-sm-3"></div>
      </div>
    </div>
    <div class="col-sm-1"></div>
  </div>
  <?php if($res_corp_info["up_pdf"]): ?>
    <div class="row content_item" id="reference_material">
      <h3 class="text-center item_title">参考資料</h3>
      <div class="col-sm-3"></div>
      <div class="col-sm-6">
        <iframe src="<?= $url_for_pdf ?>pdfjs/web/viewer.html?file=<?php if($pdf_url_for_iframe){echo $pdf_url_for_iframe; }?>" width="100%" height="400" style="border: none;"></iframe>
        </div>
      <div class="col-sm-3"></div>
    </div>
  <?php endif;?>
  <?php if($res_corp_info["company_video"]): ?>
    <div class="row content_item" id="company_video">
      <h3 class="text-center item_title">紹介動画</h3>
      <div class="col-sm-3"></div>
      <div class="col-sm-6">
        <video class="video-responsive" src="<?php if($res_corp_info["company_video"]){echo h($res_corp_info["company_video"]); }?>" alt="" preload="auto" onclick="this.play()" controls>
      </div>
      <div class="col-sm-3"></div>
    </div>
  <?php endif;?>

  <div class="row content_item" id="access">
    <h3 class="text-center item_title">アクセス</h3>
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="text-center item_s"><?=$res_corp_info["address"]?></div>
      <div class="text-center item_s"><?=$res_corp_info["tel"]?></div>
      <div class="text-center item_s"><a class="btn btn-success btn-sm" href="<?= h($res_corp_info["corp_url"]); ?>" target="_blank">corprate site</a></div>
    </div>
    <div class="col-sm-1"></div>
  </div>

</div>

<div class="text-center content_item">
<a href="detail.php" class="btn btn-default btn-lg">編集する</a>
</div>



<?php include("../template/footer.html") ?>

</body>
</html>
