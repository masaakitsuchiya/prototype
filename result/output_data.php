<?php
session_start();
include("../function/function.php");
login_check();
// $interviewee_id = $_POST["target_inteviewee"];
$interview_id = $_GET["interview_id"];
//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM  interview_result,interviewer_info WHERE interview_result.interview_id = :interview_id AND interview_result.interviewer_id = interviewer_info.id");
$stmt->bindValue(':interview_id',$interview_id, PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
$view="";
$data_s = [];
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view .= '<h3 class="text-center">'.h($result["interviewer_name"]).'</h3>';
    $view .= '<table class="table table-striped evaluation_detail" style="table-layout:fixed;width:100%;">';
    $view .= '<thead><tr><th class="text-center">評価項目</th><th class="text-center">score</th><th class="text-center">comment</th></tr></thead>';
    $view .= '<tbody>';
    $view .= '<tr><td class="text-center">能力・スキル</td><td class="point text-center">'.h($result["score_0"]).'</td><td class="comment">'.h($result["qualitative_0"]).'</td></tr>';
    $view .= '<tr><td class="text-center">協調性</td><td class="point text-center">'.h($result["score_1"]).'</td><td class="comment">'.h($result["qualitative_1"]).'</td></tr>';
    $view .= '<tr><td class="text-center">コミュニケーション能力</td><td class="point text-center">'.h($result["score_2"]).'</td><td class="comment">'.h($result["qualitative_2"]).'</td></tr>';
    $view .= '<tr><td class="text-center">積極性</td><td class="point text-center">'.h($result["score_3"]).'</td><td class="comment">'.h($result["qualitative_3"]).'</td></tr>';
    $view .= '<tr><td class="text-center">モラル・性格面</td><td class="point text-center">'.h($result["score_4"]).'</td><td class="comment">'.h($result["qualitative_4"]).'</td></tr>';
    $view .= '<tr><td class="text-center">定着度</td><td class="point text-center">'.h($result["score_5"]).'</td><td class="comment">'.h($result["qualitative_5"]).'</td></tr>';
    $view .= '<tr><td class="text-center">平均点/総評</td><td class="point text-center">1</td><td class="comment">'.h($result["comment"]).'</td></tr>';
    $view .= '</tbody>';
    $view .= '</table>';

    $data = array(h($result["score_0"]),h($result["score_1"]),h($result["score_2"]),h($result["score_3"]),h($result["score_4"]),h($result["score_5"]),h($result["interviewer_name"]));
    array_push($data_s, $data);
  }
}
$json_data_s = json_encode($data_s);
$interview_type = array("書類選考","1次面接","2次面接","3次面接");

$stmt2 = $pdo->prepare("SELECT * FROM  interview,interviewee_info WHERE interview.id = :interview_id AND interview.interviewee_id = interviewee_info.id");
$stmt2->bindValue(':interview_id',$interview_id, PDO::PARAM_INT);
$status2 = $stmt2->execute();
if($status2==false){
  //execute（SQL実行時にエラーがある場合）
  $error2 = $stmt2->errorInfo();
  exit("ErrorQuery:".$error2[2]);
}else{
  $res = $stmt2->fetch();
}
$stmt3 = $pdo->prepare("SELECT * FROM  job_post WHERE id = :job_post_id");
$stmt3->bindValue(':job_post_id',$res["job_post_id"], PDO::PARAM_INT);
$status3 = $stmt3->execute();
if($status3==false){
  //execute（SQL実行時にエラーがある場合）
  $error3 = $stmt3->errorInfo();
  exit("ErrorQuery:".$error3[2]);
}else{
  $res_job_post = $stmt3->fetch();
}

//アンケート結果があれば表示する
$interviewee_id = $res["interviewee_id"];
$stmt_anchet = $pdo->prepare("SELECT * FROM anchet WHERE interviewee_id = :interviewee_id AND stage_flg= :stage_flg");
$stmt_anchet->bindValue(':interviewee_id',$interviewee_id, PDO::PARAM_INT);
$stmt_anchet->bindValue(':stage_flg',2, PDO::PARAM_INT);//2= 回答済み

$status_anchet = $stmt_anchet->execute();
if($status_anchet==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_anchet->errorInfo();
  exit("ErrorQuery_anchet:".$error[2]);
}else{
  $res_anchet = $stmt_anchet->fetch();
}

$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.js"></script>
<style type="text/css">
  body{
    background:#f8f8f8;

  }
  td.comment{
    word-wrap:break-word;
  }

  .tableItemTitle{
    width:30%;
  }
  .tableCommentTitle{
    width:60%;
  }
  .tablePointTitle{
    width:10%;
  }
.evaluation_detail{
  margin-bottom: 60px;
}
.info_name{
  margin-bottom:30px;
}

.tr_select{
 font-size:1.2em;
}

</style>
</head>
<body>
<?php include("../template/nav.php") ?>
<h3 class="text-center">評価結果</h3>
<div class="info_name">
<p class="text-center"><?= h($res["interviewee_name_kana"])?></p>
<h2 class="text-center"><?= h($res["interviewee_name"])?></h2>
<p class="text-center">
  <!-- <a class="btn btn-info" href="web_interview.php?interview_id=<?=$interview_id?>" target="_blank">ビデオ面接</a></p> -->
  <!-- <button class="btn btn-info" id="web_interview_open">ウェブ面接</button> -->
</div>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-6">
      <canvas id="myChart" width="400" height="400"></canvas>
    </div>
    <div class="col-sm-4">
      <table class="table table-striped">
      <tr><th class="text-center">誕生日</th><td class="text-center"><?= h($res["birthday"]) ?></td></tr>
      <tr><th class="text-center">職種</th><td class="text-center"><?= h($res_job_post["job_title"]) ?></td></tr>
      <tr><th class="text-center">ステージ</th><td class="text-center"><?= $interview_type[h($res["interview_type"])] ?></td></tr>
      <tr><th class="text-center">面接日時</th><td class="text-center"><?= h($res["interview_date_time"]) ?></td></tr>
      <?php if($res_anchet["anchet_id"]):?>
      <tr><th class="text-center">アンケート</th><td class="text-center"><a class="btn btn-default" href="../setting/questionnaire_show.php?anchet_id=<?= $res_anchet["anchet_id"]?>">アンケート結果</a></td></tr>
      <?php endif;?>
      <?php if($res["resume0"]):?>
        <tr><th class="text-center">履歴書・職務経歴書</th><td class="text-center"><a href="<?php h($res["resume0"]);?>" target="_blank"><span class="glyphicon glyphicon-book text-primary file_icon"></span></a></td></tr>
      <?php endif; ?>
      <?php if($res["resume1"]):?>
        <tr><th class="text-center">履歴書・職務経歴書</th><td class="text-center"><a href="<?php h($res["resume1"]);?>" target="_blank"><span class="glyphicon glyphicon-book text-primary file_icon"></span></a></td></tr>
      <?php endif; ?>
      <?php if($res["resume2"]):?>
        <tr><th class="text-center">履歴書・職務経歴書</th><td class="text-center"><a href="<?php h($res["resume2"]);?>" target="_blank"><span class="glyphicon glyphicon-book text-primary file_icon"></span></a></td></tr>
      <?php endif; ?>
      <!-- 合否が確定している場合は、合否情報を出力 -->
      <?php if($res["stage_flg"]== 4 OR $res["stage_flg"] == 5): ?>
        <tr><th class="text-center">合否判定</th><td class="text-center"><?php if($res["stage_flg"]==4){echo '合格';}elseif($res["stage_flg"]==5){echo '不合格';} ?></td></tr>
        <tr><th class="text-center">判定日時</th><td class="text-center"><?= h($res["fix_time"]) ?></td></tr>
        <tr><th class="text-center">合否コメント</th><td class="text-center"><?= h($res["t_r_reason"]) ?></td></tr>
      <?php endif;?>
      </table>


      <!-- 管理者でかつ合否が未確定だと合否入力のフォームを表示する。管理者でかつ合否が確定していたら合否をキャンセルするボダンを表示 -->
      <?php if($res["stage_flg"]!= 4 AND $res["stage_flg"]!= 5 AND $_SESSION["kanri_flg"] == 1): ?>
      <table class="table tr_input">
        <thead>
          <tr><th class="text-center">合否入力(管理者)</th></tr>
        </thead>
        <tbody>
          <tr><td>
            <form class="form-horizontal" method="post" action="pass_faile_insert.php?interview_id=<?= h($interview_id); ?>">
              <div class="form-group">
                <label class="radio-inline">
                <input type="radio" name="stage_flg" value="4" required><span class="tr_select">通過</span>
                </label>
                <label class="radio-inline">
                <input type="radio" name="stage_flg" value="5" required><span class="tr_select">不合格</span>
                </label>
              </div>
              <div class="form-group">
                <label for="t_r_reason" style="font-weight:normal;">合否に関するコメント</label>
                <textarea class="form-control" name="t_r_reason" rows="10" id="t_r_reason" required placeholder="合格・不合格についての理由やコメントを入力"></textarea>
              </div>
                <div class="form-group text-left">
                  <input type="submit" class="btn btn-default" value="決定">
                </div>
            </form>
          </tr><td>
        </tbody>
      </table>
    <?php elseif($res["stage_flg"]== 4 || $res["stage_flg"]== 5 AND $_SESSION["kanri_flg"] == 1): ?>
        <div class="text-right">
          <a href="pass_faile_reset.php?interview_id=<?= h($interview_id); ?>" class="btn btn-default">合否を未確定に戻す</a>
          <!-- <form class="form-horizontal" method="post" action="pass_faile_reset.php?interview_id=<?= h($interview_id); ?>">
              <div class="form-group text-center">
                <input type="submit" class="btn btn-default" name="stage_flg" value="合否を未確定に戻す">
              </div>
          </form> -->
        </div>
    <?php endif;?>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<h2 class="text-center">評価詳細</h2>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <?=$view?>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<?php include("../template/footer.html") ?>
<script>
var ctx = document.getElementById("myChart");
var data_s = JSON.parse('<?php echo $json_data_s; ?>');
console.dir(data_s);

var colorSet = [["rgba(255, 99, 132, 0.2)","rgba(255,99,132,1)"],["rgba(54, 162, 235, 0.2)","rgba(54, 162, 235, 1)"],["rgba(255, 206, 86, 0.2)","rgba(255, 206, 86, 1)"],["rgba(75, 192, 192, 0.2)","rgba(75, 192, 192, 1)"],["rgba(153, 102, 255, 0.2)","rgba(153, 102, 255, 1)"],["rgba(255, 159, 64, 0.2)","rgba(255, 159, 64, 1)"]];

var dataSets = [];
for (var i=0; i< data_s.length; i++){
  var p1 = Number(data_s[i][0]);
  var p2 = Number(data_s[i][1]);
  var p3 = Number(data_s[i][2]);
  var p4 = Number(data_s[i][3]);
  var p5 = Number(data_s[i][4]);
  var p6 = Number(data_s[i][5]);
  var points = [p1,p2,p3,p4,p5,p6];
  var obj = { label: data_s[i][6],//名前
              backgroundColor: colorSet[i][0],
              borderColor: colorSet[i][1],
              data: points //配列
            };
  dataSets.push(obj);  //配列 オブジェクトが完成したらpush
  // console.dir(points);
}
console.dir(dataSets);
var myChart = new Chart(ctx, {
  type: 'radar',
  data: {
    labels: ["能力・スキル", "協調性", "コミュニケーション", "積極性", "モラル", "定着度"],
    datasets: dataSets
    },
  options:{
      scale:{
        ticks:{
          beginAtZero: true
        }
      }

  }

});

// $(function(){
// // var openedWindow;
// // //
// // function openWindow() {
// //   openedWindow = window.open("web_intervivew0.php?interview_id=<?= $interview_id ?>");
// // }
// //
// $('web_interview_open').click(function(){
//   window.open('web_interview0.php?interview_id=<?= $interview_id ?>');
//   return false;
// });
// });

</script>

</body>
</html>
