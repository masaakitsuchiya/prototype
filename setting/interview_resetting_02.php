<?php

session_start();
include("../function/function.php");
login_check();

//1. POSTデータ取得

// $interviewee_id =  $_POST["interviewee_id"];]
if(isset($_POST["interviewer_id"])){
$_SESSION["interviewer_id"]= $_POST["interviewer_id"];//配列
}
$interviewer_id_count = count($_SESSION["interviewer_id"]);//面接担当者の数



//1.  DB接続します
$pdo = db_con();




//面接者名出力
$view_interviewer_name="";
for($i = 0; $i < $interviewer_id_count; $i++){
$stmt = $pdo->prepare("SELECT interviewer_name FROM interviewer_info where id = :interviewer_id");
$stmt->bindValue(':interviewer_id', $_SESSION["interviewer_id"][$i], PDO::PARAM_INT);
$status = $stmt->execute();
//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
    $res_interviewer_name = $stmt->fetch();
    $view_interviewer_name .= h($res_interviewer_name["interviewer_name"]);
    $view_interviewer_name .= '&emsp;';
  }
}

//候補者名出力
$stmt = $pdo->prepare("SELECT * FROM interview INNER JOIN interviewee_info ON interview.interviewee_id = interviewee_info.id where interview.id= :interview_id");
$stmt->bindValue(':interview_id', $_SESSION["interview_id"], PDO::PARAM_INT);
$status2 = $stmt->execute();

//３．データ表示
if($status2==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res_interviewee = $stmt->fetch();
  }


$reserve_time_list = [];
// １，interviewerごとのinterview.interview_date_timeを配列に入れる
// SELECT * FROM interview, interviewer_list where interviewer_list.interviewer_id = 1 AND interviewer_list.interview_id = interview.id AND interview.stage_flg = 3;
$stmt = $pdo->prepare("SELECT interview.interview_date_time from interviewer_list INNER JOIN interview ON interviewer_list.interview_id = interview.id where interviewer_list.interviewer_id = :interviewer_id AND interview.stage_flg = :stage_flg");
$stmt->bindValue(':stage_flg', 3, PDO::PARAM_INT);
foreach($_SESSION["interviewer_id"] as $interviewer_id){
$stmt->bindValue(':interviewer_id', $interviewer_id, PDO::PARAM_INT);
$status = $stmt->execute();
  if($status==false){
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit("ErrorQuery:".$error[2]);
  }else{
    while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
      $target_time = h($result["interview_date_time"]);
      $before_after_30minute = before_after_30minute($target_time);//30分後と３０分前の時間を取得
      array_push($reserve_time_list,$target_time,$before_after_30minute[0],$before_after_30minute[1]);
    }
  }
}


// ２，interviewerごとの予約済み時間の呼び出し
$stmt = $pdo->prepare("SELECT interview_reserve_time.interview_reserve_time FROM interview_reserve_time INNER JOIN (interview INNER JOIN interviewer_list ON interview.id = interviewer_list.interview_id) ON interview_reserve_time.interview_id =interview.id WHERE interview.stage_flg = :stage_flg AND interviewer_list.interviewer_id = :interviewer_id");
$stmt->bindValue(':stage_flg', 2, PDO::PARAM_INT);
foreach($_SESSION["interviewer_id"] as $interviewer_id){
$stmt->bindValue(':interviewer_id', $interviewer_id, PDO::PARAM_INT);
$status = $stmt->execute();
  if($status==false){
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt->errorInfo();
    exit("ErrorQuery:".$error[2]);
  }else{
    while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
      $target_time = h($result["interview_reserve_time"]);
      $before_after_30minute = before_after_30minute($target_time);//該当の時間の３０分前後の時間を取得
      array_push($reserve_time_list,h($result["interview_reserve_time"]),$before_after_30minute[0],$before_after_30minute[1]);
    }
  }
}



//フォームの日付表示

$view_date = "<th>&emsp;&emsp;&emsp;</th>";
$week_day_jp = array("(日)","(月)","(火)","(水)","(木)","(金)","(土)");
for($j=1; $j<=14; $j++){
  $interview_date =strtotime("+".$j." day");
  // $interview_date = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j') + $j, date('Y')));
  $week_day_num = date('w',$interview_date);
  $interview_date_s = date('m/d',$interview_date);
    if($week_day_num == 0){
      $view_date .= '<th class="sunday">'.$interview_date_s.'<br>'.$week_day_jp[$week_day_num].'</th>';
    }elseif($week_day_num == 6){
      $view_date .= '<th class="saturday">'.$interview_date_s.'<br>'.$week_day_jp[$week_day_num].'</th>';
    }else{
    $view_date .= '<th>'.$interview_date_s.'<br>'.$week_day_jp[$week_day_num].'</th>';
    }
}

//フォーム出力
$view_form = "";
$target_date_time = '08:00:00';
$interview_date = date('Y-m-d', mktime(0, 0, 0, date('n'), date('j'), date('Y')));
$interview_date_time = $interview_date." ".$target_date_time;

//明日の8時にプラス30分、1日づつプラスして、14日間出力
//明日の8時にプラス60分、1日づつプラスして、14日間出力
//以下24時迄繰り返し
$interview_date_time_tommorow = date("Y-m-d H:i:s",strtotime($interview_date_time . "+1 day"));
for($j=0; $j<29; $j++){
  $view_form .= "<tr>";
  $target_plus_minute = 30 * $j;
  $target_ingerview_date_time = date("Y-m-d H:i",strtotime($interview_date_time_tommorow . "+".$target_plus_minute." minutes"));

  $time_for_header = explode(" ",$target_ingerview_date_time);
  $view_form .= '<th>'.$time_for_header[1].'</th>';
    for($i=0; $i<14; $i++){
      $target_ingerview_date_time2 = date("Y-m-d H:i:s",strtotime($target_ingerview_date_time . "+".$i." day"));
      // $view_form .= '<td><div class="btn-group" data-toggle="buttons"><label class="btn btn-default active" for="'.$target_ingerview_date_time2.'"><input type="checkbox" autocomplete="off" name="interview_date_time_reserves[]" value="'.$target_ingerview_date_time2.'" id="'.$target_ingerview_date_time2.'"><span>予約</span></label></div></td>';
      //すでに予約が入っている時間はdisableそうでなければ予約可
      if(in_array($target_ingerview_date_time2,$reserve_time_list)){
      $view_form .= '<td class="text-center"><input class="icr" group="time_check" type="checkbox" name="interview_date_time_reserves[]" value="'.$target_ingerview_date_time2.'" id="'.$target_ingerview_date_time2.'" disabled="disabled"><label class="lcr-disable" for="'.$target_ingerview_date_time2.'"><span class="not_reserve"><i class="glyphicon glyphicon-remove"></i></span></label></td>';
      }else{
      $view_form .= '<td><input class="icr" type="checkbox" group="time_check" name="interview_date_time_reserves[]" value="'.$target_ingerview_date_time2.'" id="'.$target_ingerview_date_time2.'"><label class="lcr" for="'.$target_ingerview_date_time2.'"><span class="reserve">予約可</span></label></td>';
      }
    }
  $view_form .= '</tr>';
}


$interview_type_str = interview_type($res_interviewee["interview_type"]);

$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>

.container{
  margin-top:30px;
  margin-bottom:30px;
}

thead.scrollHead,tbody.scrollBody{
    display:block;
}
tbody.scrollBody{
  overflow-y:scroll;
  height:500px;
}
th,td{
width:5%;
}
tr{
  width:100%;
}
.sunday{
  background:red;
}
.saturday{
    background:blue;
}

.lcr{
  cursor:pointer;
  width:100%;
  height:100%;
  border: 1px solid gray;
  border-radius: 2px;
  padding:10px;
}
.icr{
  display: none;
}
.icr:checked + .lcr {
  background-color: skyblue;
}
span.reserve{
  font-size:0.7em;
}
.lcr-disable{
  cursor:not-allowed;
  background-color: gray;
  border: 1px solid gray;
  border-radius: 2px;
  padding:10px;
}

</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">ビデオ面接予約</h3>
<!-- 一覧に戻るボタン -->
<?php include("../template/back_to_interviewee_select.php"); ?>

<div class="container">
<?php $_GET['progress']=2;include("../template/interview_setting_progress.php"); ?>
</div>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form-group form-horizontal" action="interview_resetting_03.php" method="post">
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewee_name">候補者名</label><div class="col-sm-10"><p class="form-control-static"><?= h($res_interviewee["interviewee_name"]); ?></p></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interview_type">選考ステップ</label>
          <div class="col-sm-10"><p class="form-control-static"><?= h($interview_type_str); ?></p></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interview_type">面接担当者</label>
          <div class="col-sm-10"><p class="form-control-static"><?= $view_interviewer_name ?></p></div>
        </div>
        <div class="form-group">
            <span class="help-block">面接実施可能な日時を選択してください。候補者はここで選択した日時から対応可能な日時を選ぶことになります。</span>
          <table class="table table-hover table-bordered table-condensed">
            <thead class="scrollHead">
              <tr><?= $view_date ?></tr>
            <tbody class="scrollBody">
              <?= $view_form ?>
            </tbody>

          </table>
        </div>

        <div class="text-center">
          <a class="btn btn-default" href="interview_resetting_01.php?interview_id=<?php echo($_SESSION["interview_id"]);?>">戻る</a>
          &emsp;
          <input class="btn btn-info" type="submit" id="submit" value="次へ">
        </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>

<?php include("../template/footer.html") ?>
<script>
$(function(){
  $('input[name="interview_date_time_reserves[]"]').change(function(){
    var prop = $(this).prop('checked')
    if(prop){
      console.log('checked');
      $(this).children("span").text("選択済");
    }else{
      console.log('unchecked');
      $(this).children("span").text("予約可");
    }
    // if($(this).is(':checked')){
    //   console.log("checked!")
    //   $(this > span).text("");
    //   $(this > span).text("選択済");
    // }else{
    //   console.log("unchecked");
    //   $(this > span).text("");
    //   $(this > span).text("予約可");
    // }
  });
  	$('#submit').attr('disabled', 'disabled');
    $("input[group='time_check']").click(function(){
      if($("input[group='time_check']:checked").length == 0){
        $('#submit').attr('disabled', 'disabled');
          // $("#msg").html("OK!!");
      }else{
          $('#submit').removeAttr('disabled');
          // $("#msg").html("どれか最低1つチェックを付けてください。");
      }
    });
});
</script>

</body>
</html>
