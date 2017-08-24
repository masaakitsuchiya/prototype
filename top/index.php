<?php

session_start();
include("../function/function.php");
login_check();

$pdo = db_con();

//求人の表示
$stmt_job_post = $pdo->prepare("SELECT * FROM job_post WHERE life_flg= :life_flg AND delete_flg = :delete_flg");
$stmt_job_post->bindValue(':life_flg',0, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt_job_post->bindValue(':delete_flg',0, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status_job_post = $stmt_job_post->execute();

$view_job_post="";
if($status_job_post==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_job_post->errorInfo();
  exit("ErrorQuery_job_post:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_job_post = $stmt_job_post->fetch(PDO::FETCH_ASSOC)){
    $view_job_post .='<li class="list-group-item">';
    $view_job_post .='<div class="row">';
    $view_job_post .='<div class="col-xs-10">';
    $view_job_post .= h($result_job_post["job_title"]);
    $view_job_post .='</div>';
    $view_job_post .='<div class="col-xs-2">';
    $view_job_post .='<a href="../job_post/job_post_view.php?job_post_id='.h($result_job_post["id"]).'" class="btn btn-info btn-sm" target="_blank">詳細</a>';
    $view_job_post .='</div>';
    $view_job_post .='</div>';
    $view_job_post .='</li>';
  }
}

//面接日時の確定
$stmt_interview_date_fix = $pdo->prepare("select interview.id,interview.interview_type,interviewee_info.interviewee_name from interview INNER JOIN interviewee_info ON interview.interviewee_id = interviewee_info.id where stage_flg=:stage_flg");
$stmt_interview_date_fix->bindValue(':stage_flg',2, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status_interview_date_fix = $stmt_interview_date_fix->execute();

$view_interview_date_fix="";
if($status_interview_date_fix==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interview_date_fix->errorInfo();
  exit("ErrorQuery_interview_date_fix:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_interview_date_fix = $stmt_interview_date_fix->fetch(PDO::FETCH_ASSOC)){
    $view_interview_date_fix .='<li class="list-group-item">';
    $view_interview_date_fix .='<div class="row">';
    $view_interview_date_fix .='<div class="col-xs-10">';
    $interview_type_str = interview_type($result_interview_date_fix["interview_type"]);
    $view_interview_date_fix .= h($result_interview_date_fix["interviewee_name"]).'<br>'.h($interview_type_str);
    $view_interview_date_fix .='</div>';
    $view_interview_date_fix .='<div class="col-xs-2">';
    $view_interview_date_fix .='<a href="../setting/interview_confirm01.php?interview_id='.h($result_interview_date_fix["id"]).'" class="btn btn-warning btn-sm">確定</a>';
    $view_interview_date_fix .='</div>';
    $view_interview_date_fix .='</div>';
    $view_interview_date_fix .='</li>';
  }
}

//新しい候補者
$stmt_new_interviewee = $pdo->prepare("select * from interviewee_info INNER JOIN job_post ON interviewee_info.job_post_id = job_post.id ORDER BY interviewee_info.indate DESC limit 5");
// $stmt_interview_date_fix->bindValue(':stage_flg',2, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)

$status_new_interviewee = $stmt_new_interviewee->execute();

$view_new_interviewee="";
if($status_new_interviewee==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_new_interviewee->errorInfo();
  exit("ErrorQuery_new_interviewee:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_new_interviewee = $stmt_new_interviewee->fetch(PDO::FETCH_ASSOC)){
    $view_new_interviewee .='<li class="list-group-item">';
    $view_new_interviewee .='<div class="row">';
    $view_new_interviewee .='<div class="col-xs-10">';
    $interview_time = remove_second(h($result_new_interviewee["indate"]));
    $view_new_interviewee .= h($result_new_interviewee["interviewee_name"]).'/応募日時:'.h($interview_time).'<br>'.h($result_new_interviewee["job_title"]);
    $view_new_interviewee .='</div>';
    $view_new_interviewee .='<div class="col-xs-2">';
    $view_new_interviewee .='<a href="../setting/interviewee_detail.php?target_interviewee_id='.h($result_new_interviewee["id"]).'" class="btn btn-info btn-sm">詳細</a>';
    $view_new_interviewee .='</div>';
    $view_new_interviewee .='</div>';
    $view_new_interviewee .='</li>';
  }
}

//合否未入力
$today = date('Y/m/d H:i:s');

$stmt_s_or_f = $pdo->prepare("select interview.id,interview.interview_type,interview.interview_date_time,interviewee_info.interviewee_name from interview INNER JOIN interviewee_info ON interview.interviewee_id = interviewee_info.id where interview.interview_date_time <= :today AND interview.stage_flg = :stage_flg");
$stmt_s_or_f ->bindValue(':today',$today, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt_s_or_f ->bindValue(':stage_flg',3, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status_s_or_f = $stmt_s_or_f->execute();

$view_s_or_f="";
if($status_s_or_f==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_s_or_f->errorInfo();
  exit("ErrorQuery_s_or_f:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_s_or_f = $stmt_s_or_f->fetch(PDO::FETCH_ASSOC)){
    $view_s_or_f .='<li class="list-group-item">';
    $view_s_or_f .='<div class="row">';
    $view_s_or_f .='<div class="col-xs-10">';
    $interview_type_str = interview_type($result_s_or_f["interview_type"]);
    $interview_date = remove_time($result_s_or_f["interview_date_time"]);
    $view_s_or_f .= h($result_s_or_f["interviewee_name"]).'<br>'.h($interview_type_str).' '.h($interview_date);
    $view_s_or_f .='</div>';
    $view_s_or_f .='<div class="col-xs-2">';
    $view_s_or_f .='<a href="../result/output_data.php?interview_id='.h($result_s_or_f["id"]).'" class="btn btn-warning btn-sm">入力</a>';
    $view_s_or_f .='</div>';
    $view_s_or_f .='</div>';
    $view_s_or_f .='</li>';
  }
}

//直近の面接予定
$today = date('Y/m/d H:i:s');

$stmt_comming_interview = $pdo->prepare("select interview.id,interview.interview_type,interviewee_info.interviewee_name,interview.interview_date_time from interviewer_list INNER JOIN (interview INNER JOIN interviewee_info ON interview.interviewee_id = interviewee_info.id) ON interviewer_list.interview_id = interview.id where interview.interview_date_time >= :today AND interview.stage_flg = :stage_flg AND interviewer_list.interviewer_id =:interviewer_id");
$stmt_comming_interview ->bindValue(':today',$today, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt_comming_interview ->bindValue(':stage_flg',3, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt_comming_interview ->bindValue(':interviewer_id',$_SESSION["user_id"], PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status_comming_interview = $stmt_comming_interview->execute();

$view_comming_interview="";
if($status_comming_interview==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_comming_interview->errorInfo();
  exit("ErrorQuery_comming_interview:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_comming_interview = $stmt_comming_interview->fetch(PDO::FETCH_ASSOC)){
    $view_comming_interview .='<li class="list-group-item">';
    $view_comming_interview .='<div class="row">';
    $view_comming_interview .='<div class="col-xs-10">';
    $interview_type_str = interview_type($result_comming_interview["interview_type"]);
    $interview_time = remove_second($result_comming_interview["interview_date_time"]);
    $view_comming_interview .= h($result_comming_interview["interviewee_name"]).'<br>'.h($interview_type_str).' '.h($interview_time);
    $view_comming_interview .='</div>';
    $view_comming_interview .='<div class="col-xs-2">';
    $view_comming_interview .='<a href="../result/input_data.php?interview_id='.h($result_comming_interview["id"]).'" class="btn btn-info btn-sm">詳細</a>';
    $view_comming_interview .='</div>';
    $view_comming_interview .='</div>';
    $view_comming_interview .='</li>';
  }
}
//☆面接日時の要調整 outerjoin?
// interview にレコードがないinterivewee_info またはinterview.stage_flgが６（要再調整）
//再調整のとき
$stmt_interview_date_setting = $pdo->prepare("select interviewee_info.interviewee_name,interview.id,interview.interviewee_id,interview.stage_flg from interviewee_info LEFT OUTER JOIN interview ON interviewee_info.id = interview.interviewee_id WHERE interview.stage_flg = :stage_flg");
$stmt_interview_date_setting ->bindValue(':stage_flg',6, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status_interview_date_setting = $stmt_interview_date_setting->execute();
$view_interview_date_setting="";
if($status_interview_date_setting==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interview_date_setting->errorInfo();
  exit("ErrorQuery_interview_date_setting:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_interview_date_setting = $stmt_interview_date_setting->fetch(PDO::FETCH_ASSOC)){
    $view_interview_date_setting .='<li class="list-group-item">';
    $view_interview_date_setting .='<div class="row">';
    $view_interview_date_setting .='<div class="col-xs-10">';
    $view_interview_date_setting .= h($result_interview_date_setting["interviewee_name"]);
    $view_interview_date_setting .='</div>';
    $view_interview_date_setting .='<div class="col-xs-2">';
    $view_interview_date_setting .='<a href="../setting/interview_resetting.php?interview_id='.h($result_interview_date_setting["id"]).'" class="btn btn-warning btn-sm">再調整</a>';

    $view_interview_date_setting .='</div>';
    $view_interview_date_setting .='</div>';
    $view_interview_date_setting .='</li>';
  }
}
//nullのときすなわちまだ面接の設定を開始していない。
$stmt_interview_date_setting_null = $pdo->prepare("select interviewee_info.interviewee_name,interviewee_info.id,interview.interviewee_id,interview.stage_flg from interviewee_info LEFT OUTER JOIN interview ON interviewee_info.id = interview.interviewee_id WHERE interview.stage_flg IS NULL");
$status_interview_date_setting_null = $stmt_interview_date_setting_null->execute();

if($status_interview_date_setting_null==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interview_date_setting_null->errorInfo();
  exit("ErrorQuery_interview_date_setting_null:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_interview_date_setting_null = $stmt_interview_date_setting_null->fetch(PDO::FETCH_ASSOC)){
    $view_interview_date_setting .='<li class="list-group-item">';
    $view_interview_date_setting .='<div class="row">';
    $view_interview_date_setting .='<div class="col-xs-10">';
    $view_interview_date_setting .= h($result_interview_date_setting_null["interviewee_name"]);
    $view_interview_date_setting .='</div>';
    $view_interview_date_setting .='<div class="col-xs-2">';
    $view_interview_date_setting .='<a href="../setting/interview01_setting.php?interview_type_num=1&target_interviewee_id='.h($result_interview_date_setting_null["id"]).'" class="btn btn-warning btn-sm">調整</a>';
    $view_interview_date_setting .='</div>';
    $view_interview_date_setting .='</div>';
    $view_interview_date_setting .='</li>';
  }
}


//要面接評価入力
$stmt_evalueation_input = $pdo->prepare("select interview.interview_type, interview.id, interviewee_info.interviewee_name from interview_result RIGHT OUTER JOIN (interviewee_info INNER JOIN(interview INNER JOIN interviewer_list ON interview.id = interviewer_list.interview_id) ON interviewee_info.id = interview.interviewee_id) ON interview_result.interview_id = interview.id WHERE interviewer_list.interviewer_id = :interviewer_id AND interview_result.id IS NULL AND interview.interview_date_time >= :today");
$stmt_evalueation_input ->bindValue(':interviewer_id',$_SESSION["user_id"], PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt_evalueation_input ->bindValue(':today',$today, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$status_evalueation_input = $stmt_evalueation_input ->execute();

$view_evalueation_input="";
if($status_evalueation_input==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_evalueation_input->errorInfo();
  exit("ErrorQuery_evalueation_input:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_evalueation_input = $stmt_evalueation_input->fetch(PDO::FETCH_ASSOC)){
    $view_evalueation_input .='<li class="list-group-item">';
    $view_evalueation_input .='<div class="row">';
    $view_evalueation_input .='<div class="col-xs-10">';
    $interview_type_str = interview_type($result_evalueation_input["interview_type"]);
    $view_evalueation_input .= h($result_evalueation_input["interviewee_name"]).'<br>'.h($interview_type_str);
    $view_evalueation_input .='</div>';
    $view_evalueation_input .='<div class="col-xs-2">';
    $view_evalueation_input .='<a href="../result/input_data.php?interview_id='.h($result_evalueation_input["id"]).'" class="btn btn-warning btn-sm">入力</a>';
    $view_evalueation_input .='</div>';
    $view_evalueation_input .='</div>';
    $view_evalueation_input .='</li>';
  }
}

$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
.row {
  margin-bottom:30px;
}
</style>
</head>
<body>
  <?php include("../template/nav.php") ?>


<div class="container-fluid">
  <div class="row">
    <h2 class="text-center">Dash Board</h2>
  </div>
  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
      <div class="text-right">
        <a class="btn btn-default" href="../setting/interviewee_select.php">候補者一覧へ</a>
      </div>
    </div>
    <div class="col-sm-2"></div>
  </div>
  <!-- ここから管理領域 -->
  <?php if($_SESSION["kanri_flg"]==1): ?>
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-5">
      <div class="panel panel-warning">
        <div class="panel-heading">新しい候補者</div>
        <div class="panel-body"></div>
        <ul class="list-group">
          <?=$view_new_interviewee?>
        </ul>
      </div>
    </div>
    <div class="col-sm-5">
      <div class="panel panel-warning">
        <div class="panel-heading">面接日時の調整</div>
        <div class="panel-body"></div>
        <ul class="list-group">
          <?= $view_interview_date_setting ?>
        </ul>
      </div>
    </div>
    <div class="col-sm-1"></div>
  </div>
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-5">
      <div class="panel panel-warning">
        <div class="panel-heading">面接日時の確定</div>
        <div class="panel-body"></div>
        <ul class="list-group list-unstyled">
          <?=$view_interview_date_fix ?>
        </ul>
      </div>
    </div>
    <div class="col-sm-5">
      <div class="panel panel-warning">
        <div class="panel-heading">合否未入力</div>
        <div class="panel-body"></div>
        <ul class="list-group">
          <?=$view_s_or_f?>
        </ul>
      </div>
    </div>
    <div class="col-sm-1"></div>
  </div>
  <?php endif; ?>
  <!-- 管理領域ここまで -->
  <!-- ここから一般ユーザー領域 -->

  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-5">
      <div class="panel panel-default">
        <div class="panel-heading">直近の面接予定</div>
        <div class="panel-body"></div>
        <ul class="list-group">
            <?= $view_comming_interview ?>
        </ul>
      </div>
    </div>
    <div class="col-sm-5">
      <div class="panel panel-default">
        <div class="panel-heading">面接評価入力</div>
        <div class="panel-body"></div>
        <ul class="list-group">
          <?=$view_evalueation_input ?>
        </ul>
      </div>
    </div>
    <div class="col-sm-1"></div>
  </div>
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-5">
      <div class="panel panel-default">
        <div class="panel-heading">現在募集中の求人</div>
        <div class="panel-body"></div>
        <ul class="list-group list-unstyled">
          <?= $view_job_post ?>
        </ul>
      </div>
    </div>
    <div class="col-sm-1"></div>
  </div>
  <?php include("../template/footer.html") ?>
</body>
</html>
