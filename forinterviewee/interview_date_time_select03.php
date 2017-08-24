<?php

// /10/my_kaday/setting/interview_date_time_select01.php?interviewee_id=*&interview_id=*

session_start();
include("../function/function.php");
include("../template/csrf_token_generate.php");
$interview_id = $_SESSION["interview_id"];
$pdo = db_con();

$stmt_interview_style = $pdo->prepare("SELECT interview_style FROM interview where id = :interview_id");
$stmt_interview_style->bindValue(':interview_id',$_SESSION["interview_id"],PDO::PARAM_INT);
$status_interview_style = $stmt_interview_style->execute();

//３．データ表示
if($status_interview_style==false){
  //execute（SQL実行時にエラーがある場合）
  $error_interview_style = $stmt_interview_style->errorInfo();
  exit("ErrorQuery:".$error_interview_style[2]);
}else{
  $res_interview_style = $stmt_interview_style->fetch();
}

//今日の日付の翌日の日付を取得
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$not_available_time[]=""; //予約できない日時の配列


//明日以降で確定している面接時間を抽出
$stmt_interview_date_time = $pdo->prepare("SELECT interview_date_time from interview WHERE interview_date_time > :tomorrow");
$stmt_interview_date_time->bindValue(':tomorrow', $tomorrow, PDO::PARAM_STR);
$status_interview_date_time = $stmt_interview_date_time->execute();

//３．データ表示
if($status_interview_date_time==false){
  //execute（SQL実行時にエラーがある場合）
  $error_interview_date_time = $stmt_interview_date_time->errorInfo();
  exit("ErrorQuery:".$error_interview_date_time[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_interview_date_time = $stmt_interview_date_time->fetch(PDO::FETCH_ASSOC)){
    $before_after_30minute = before_after_30minute($result_interview_date_time["interview_date_time"]);//３０分まえと３０分あとのタイムスタンプ
    array_push($not_available_time,$result_interview_date_time["interview_date_time"],$before_after_30minute[0],$before_after_30minute[1]);
    // $not_available_time[] = $result_interview_date_time["interview_date_time"];
  }
}
//今日以降の interview_reserve_time.interview_reserve_time かつ　interview.stage_flg = 2
$stmt_reserve_time_flg_2 = $pdo->prepare("SELECT interview_reserve_time from interview_reserve_time INNER JOIN interview ON interview_reserve_time.interview_id = interview.id WHERE interview.stage_flg = :stage_flg");
$stmt_reserve_time_flg_2->bindValue(':stage_flg', 2, PDO::PARAM_INT);
$status_reserve_time_flg_2 = $stmt_reserve_time_flg_2->execute();

//３．データ表示
if($status_reserve_time_flg_2==false){
  //execute（SQL実行時にエラーがある場合）
  $error_reserve_time_flg_2 = $stmt_reserve_time_flg_2->errorInfo();
  exit("ErrorQuery:".$error_reserve_time_flg_2[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_reserve_time_flg_2 = $stmt_reserve_time_flg_2->fetch(PDO::FETCH_ASSOC)){
    $before_after_30minute_reserve_time = before_after_30minute($result_reserve_time_flg_2["interview_reserve_time"]);
    array_push($not_available_time,$result_reserve_time_flg_2["interview_reserve_time"],$before_after_30minute_reserve_time[0],$before_after_30minute_reserve_time[1]);
    // $not_available_time[] = $result_reserve_time_flg_2["interview_reserve_time"];
  }
}



// var_dump($not_available_time);
//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM interview_reserve_time WHERE interview_id = :interview_id");
$stmt->bindValue(':interview_id', $_SESSION["interview_id"], PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
$reserve_times=array();
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
array_push($reserve_times,$result["interview_reserve_time"]);
  }
}
asort($reserve_times);
$view="";
foreach($reserve_times as $reserve_time){
  $interview_reserve_time_remove_second = remove_second($reserve_time);

  $view .='<div class="radio text-center">';

  //interview_reserve_timeを日付と時間に分割
  $interview_reserve_date_and_time_array = explode(" ",$reserve_time);//時間を削除

  $key = in_array($reserve_time,$not_available_time);
  if($tomorrow < $interview_reserve_date_and_time_array[0] && !$key){
    //interview_reserve_timeのほうがおおきければ、選択可能
    //そうでなければ選択不可
    $view .='<label><input type="radio" name="interview_reserve_time" id="'.h($interview_reserve_time_remove_second).'" value="'.h($reserve_time).' required">'.h($interview_reserve_time_remove_second).'</label>';
  }else{
    $view .='<label class="unselectable"><input type="radio" name="interview_reserve_time" id="'.h($interview_reserve_time_remove_second).'" value="'.h($reserve_time).' required" disabled="disabled">'.h($interview_reserve_time_remove_second).'</label>';
  }
  $view .='</div>';
}




$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head_for_interviewee.php") ?>
<style>
h4.pg{
font-size:0.9em;
}
.gray{
  color:#aaa;
}

.unselectable{
  text-decoration: line-through;
}
.submit_area{
  margin-top:30px;
  margin-bottom:30px;

}
</style>



</head>
<body>
<?php include("../template/nav_for_interviewee.php") ?>
<div class="container-fruid">
  <div class="row">
    <div class="col-xs-2 hidden-xs"></div>
    <?php if($res_interview_style["interview_style"]==1): ?>
      <h4 class="col-xs-2 pg text-center gray">1,規約同意</h4>
      <h4 class="col-xs-2 pg text-center gray">2,動作検証</h4>
      <h4 class="col-xs-2 pg text-center">3,面接日時選択</h4>
      <h4 class=" col-xs-2 pg text-center gray">4,返信完了</h4>
    <?php elseif($res_interview_style["interview_style"]==2): ?>
      <div class="col-xs-8">
        <div class="row">
        <h4 class="col-xs-4 pg text-center gray">1,規約同意</h4>
        <h4 class="col-xs-4 pg text-center">2,面接日時選択</h4>
        <h4 class=" col-xs-4 pg text-center gray">3,返信完了</h4>
        </div>
      </div>
    <?php endif; ?>
    <div class="col-xs-2 hidden-xs"></div>
  </div>
</div>
<div class="container-fruid">
  <div class="row">
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
    <h3 class="text-center">面接日時選択</h3>
    <p  class="text-center">
    以下の日時候補の中からご対応可能な日時をご選択ください。<br>
  </p>
    <div class="row" id="streams" style="display:none;">
    </div>
    <div class="col-xs-2"></div>
  </div>
</div>

</div>
  <div class="container-fruid">
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8">
        <form class="form-horizontal" action="interview_date_time_insert.php" method="post">
          <div class="form-group">
            <?= $view ?>
          </div>
          <div class="form-group">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
          </div>
          <div class="form-group text-center">
            <div class="text-center submit_area">
              <?php if($res_interview_style["interview_style"]==2): ?>
                <a class="btn btn-default" href="interview_date_time_select01.php?interview_id=<?= $interview_id ?>">戻る</a>
              <?php elseif($res_interview_style["interview_style"]==1): ?>
                <a class="btn btn-default" href="interview_date_time_select02.php?interview_id=<?= $interview_id ?>">戻る</a>
              <?php endif; ?>
              &emsp;
              <a data-toggle="modal" href="#myModal" class="btn btn-info">確認</a>
              <!-- <input class="btn btn-info" type="submit" value="送信"> -->
            </div>
            <?php
              $body_text="日程を返信してよろしいでしょうか？<br>
              ※返信しても面接日時が確定するわけではありません。
              このあと人事担当者が日時を確認すると日時が確定となります。
              確認後に通知がありますのでしばらくお待ちください。";
              $btn_text="返信";
              $_GET['body_text'] = $body_text;
              $_GET['btn_text'] = $btn_text;
              include("../template/submit_my_modal.php");
            ?>
          </div>
        </form>
      </div>
      <div class="col-sm-2"></div>
    </div>
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8">
        <p class="text-center">※過去、当日、翌日の日時は選択できません。</p>
        <p class="text-center">予約がはいってしまった日時は選択できません</p>
        <p class="text-center">※対応可能な日時がない場合<?php if($res_interview_style["interview_style"]==1):?>、ウェブ面接で対応できない場合<?php endif; ?>は<a href="interview_reset.php">こちら</a>からご連絡ください。</p>
      </div>
      <div class="col-sm-2"></div>
    </div>
  </div>
    </form>

  </div>
<?php include("../template/footer_for_interviewee.html") ?>

</body>
</html>
