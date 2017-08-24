<?php

include("../function/function.php");
$interviewer_id = $_GET["interviewer_id"];

$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM interviewer_info WHERE id= :interviewer_id");
$stmt->bindValue(':interviewer_id',$interviewer_id,PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res_interviewer_info = $stmt->fetch();
}


$html_title = servise_name();
?>
<!-- <!DOCTYPE html> -->
<!-- <html> -->
<head>
<!-- <?php include("../template/head_for_interviewee.php") ?> -->
<style>
.row{
  margin-bottom:30px;
}
.video-responsive{
  max-width: 100%;
  height: auto;
}
</style>
</head>
<!-- <body> -->
<!-- <div class="modal fade" id="myModal"> -->
  <!-- <div class="modal-dialog"> -->
      <div class="modal-header">
        <button class="close" data-dismiss="modal">&times;</button>
        <h3 class="text-center"><?=h($res_interviewer_info["interviewer_name"]);?> プロフィール</h3>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row top-image">
            <div class="col-sm-12">
              <?= $res_interviewer_info["interviewer_img"] ? '<img class="img-responsive center-block" src="'.h($res_interviewer_info["interviewer_img"]).'" alt="">' : '<div class="text-center">NO IMAGE!</div>'?>
            </div>
          </div>
          <div class="row main">
            <h2 class="text-center"><?=h($res_interviewer_info["interviewer_name"]);?></h2>
            <div class="main_info text-center">
              <p><?php if($res_interviewer_info["department"]){echo h($res_interviewer_info["department"]);} ?></p>
              <p><?php if($res_interviewer_info["title"]){echo h($res_interviewer_info["title"]);} ?></p>
            </div>
          </div>
          <div class="row profile">
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
              <?php if($res_interviewer_info["interviewer_profile"]){echo h($res_interviewer_info["interviewer_profile"]);} ?>
            </div>
            <div class="col-sm-1"></div>
          </div>
          <div class="row video">
            <h5 class="text-center">メッセージ動画</h5>
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
              <?= $res_interviewer_info["interviewer_video"] ? '<div><video id="video_thumbnail" class="video-responsive" src="'.h($res_interviewer_info["interviewer_video"]).'" alt="" preload="auto" onclick="this.play()" controls></div>' : '<div class="col-sm-6"><video id="video_thumbnail" class="video-responsive" src="" alt="" preload="none" onclick="this.play()" controls></div>'?>
            </div>
            <div class="col-sm-3"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">閉じる</button>
      </div>
<!-- </body>
</html> -->
