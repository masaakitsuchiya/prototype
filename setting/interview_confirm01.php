<?php
session_start();
include("../function/function.php");
include("../template/csrf_token_generate.php");
login_check();
$_SESSION["interview_id"] = $_GET["interview_id"];
// $interview_id = $_GET["target_interview_id"];
$pdo = db_con();

if(!isset($_GET["stage_flg"])|| $_GET["stage_flg"] ==""){
  $stmt = $pdo->prepare("SELECT * FROM interview,interviewee_info,interview_reserve_time
   WHERE interview.id= :interview_id AND interviewee_info.id = interview.interviewee_id AND interview_reserve_time.interview_id = interview.id");
  $stmt->bindValue(':interview_id', $_SESSION["interview_id"] , PDO::PARAM_INT);
  $status = $stmt->execute();


  //３．データ表示
  if($status==false){
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit("ErrorQuery:".$error[2]);
  }else{
    $res = $stmt->fetch();
    }
}elseif($_GET["stage_flg"] == 3){
  $stmt = $pdo->prepare("SELECT * FROM interview INNER JOIN interviewee_info ON interview.interviewee_id = interviewee_info.id
    WHERE interview.id = :interview_id");
   $stmt->bindValue(':interview_id', $_SESSION["interview_id"] , PDO::PARAM_INT);
   $status = $stmt->execute();
   if($status==false){
     //execute（SQL実行時にエラーがある場合）
     $error = $stmt->errorInfo();
     exit("ErrorQuery:".$error[2]);
   }else{
     $res = $stmt->fetch();
     }
}


//1.  DB接続します



$interview_type_str = interview_type($res["interview_type"]);

//面接担当者
$stmt = $pdo->prepare("SELECT interviewer_name FROM interviewer_list,interviewer_info
 WHERE interviewer_list.interview_id = :interview_id AND interviewer_info.id = interviewer_list.interviewer_id");
$stmt->bindValue(':interview_id', $_SESSION["interview_id"], PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view .= $result["interviewer_name"].' ';
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
  margin-bottom:30px;
}
</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<?php if($res["stage_flg"] == 2): ?><!-- stage_flgで文章を分ける日程確定前　-->
<h3 class="text-center">面接日時確認</h3>
<?php include("../template/back_to_interviewee_select.php"); ?>
<p class="text-center">候補者より面接日時の返信が届いています。</p>
<p class="text-center">確認の上、日程を確定してください。</p>
<?php elseif($res["stage_flg"] == 3): ?><!-- キャンセルのとき　-->
  <h3 class="text-center">面接日時確認・修正</h3>
  <?php include("../template/back_to_interviewee_select.php"); ?>
  <p class="text-center">面接の日時は以下の通りで確定しています。</p>
<?php endif;?><!--stage_flgで文章を分ける日程確定前 終了　-->
<div class="container">
  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8 text-center">

        <?php if($res["stage_flg"] == 2 || $res["stage_flg"] == 3 ): ?><!--１stage_flgが２か三の場合-->
          <table class="table">
            <tr>
              <th>候補者名</th>
              <td class="text-left"><?= h($res["interviewee_name"]); ?></td>
            </tr>
            <tr>
              <th>面接ステージ</th>
              <td class="text-left"><?= h($interview_type_str); ?></td>
            </tr>
            <tr>
              <th>面接担当者</th>
              <td class="text-left"><?=$view ?></td>
            </tr>
              <?php if(!isset($_GET["stage_flg"])||$_GET["stage_flg"]=="") :?><!--2 最終確認前はinterview_reserve_timeを出す-->
                <tr>
                  <th>面接時間</th>
                  <td class="text-left"><?=h($res["interview_reserve_time"]); ?></td>
                </tr>
              <!-- <a class="btn btn-info" href="interview_confirm_insert.php?interview_id=<?= $res["interview_id"] ?>&interview_date_time=<?= h($res["interview_reserve_time"]); ?>">確定</a> -->
              <?php elseif($_GET["stage_flg"] == 3) :?><!--2確定している日時interview_date_timeをだす-->
                <tr>
                  <th>面接時間</th>
                  <td class="text-left"><?=h(remove_second($res["interview_date_time"])); ?></td>
                </tr>
              <?php endif; ?><!--2終了-->
          </table>
            <!-- 確定ボタンとモーダル　まだ日程が確定していない場合 -->
              <?php if(!isset($_GET["stage_flg"])||$_GET["stage_flg"]="") :?><!--3-->
              <a data-toggle="modal" href="#myModal_fix" class="btn btn-info">確定</a>
              <div class="modal fade" id="myModal_fix">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">確認</h4>
                      </div>
                      <div class="modal-body">
                        日程を確定します。
                        確定すると候補者に通知が送られます。
                      </div>
                      <div class="modal-footer">
                        <a class="btn btn-info" href="interview_confirm_insert.php?interview_id=<?= h($res["interview_id"]);?>&interview_date_time=<?= h($res["interview_reserve_time"]); ?>&csrf_token=<?= $csrf_token ?>">確定</a>
                      </div>
                    </div>
                  </div>
                </div>
                &emsp;
            <?php endif; ?><!--3終了-->
            <!-- キャンセルボタンとモーダル -->
            <a data-toggle="modal" href="#myModal_cancel" class="btn btn-default">キャンセルして再設定</a>
            <div class="modal fade" id="myModal_cancel">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">確認</h4>
                    </div>
                    <div class="modal-body">
                      本当にキャンセルしてよろしいでしょうか。
                      キャンセルすると候補者に通知が送られます。
                      キャンセル後、必ず面接の再調整を行ってください。
                    </div>
                    <div class="modal-footer">
                      <a class="btn btn-danger" href="interview_cancel.php?interview_id=<?= h($_SESSION["interview_id"]); ?>&csrf_token=<?= $csrf_token ?>">キャンセルして再設定</a>
                    </div>
                  </div>
                </div>
              </div>
      <?php else: ?><!--1-->
        <?php include("../template/back_to_interviewee_select.php"); ?>
        <p>確認できる面接日程がありません</p>
      <?php endif; ?><!--1終了-->


    </div>
    <div class="col-sm-2"></div>
  </div>
</div>

<?php include("../template/footer.html") ?>

</body>
</html>
