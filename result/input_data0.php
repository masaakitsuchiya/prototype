<?php
session_start();
include("../function/function.php");
login_check();
$interview_id = $_GET["interview_id"];
// $interviewee_id = $_GET["interviewee_id"];

$pdo = db_con();

//２．データ登録SQL作成

//interviewee_id 検索
$stmt_interviewee_id = $pdo->prepare("SELECT * FROM interview WHERE id = :interview_id");
$stmt_interviewee_id->bindValue(':interview_id',$interview_id, PDO::PARAM_INT);
$status_interviewee_id = $stmt_interviewee_id->execute();

if($status_interviewee_id==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interviewee_id->errorInfo();
  exit("ErrorQuery_interviewee_id:".$error[2]);
}else{
  $res_interviewee_id= $stmt_interviewee_id->fetch();
}

$interviewee_id = $res_interviewee_id["interviewee_id"];



$stmt = $pdo->prepare("SELECT * FROM interviewee_info,interview WHERE interviewee_info.id = :interviewee_id AND interview.id= :interview_id");
$stmt->bindValue(':interviewee_id',$interviewee_id, PDO::PARAM_INT);
$stmt->bindValue(':interview_id',$interview_id, PDO::PARAM_INT);

$status = $stmt->execute();

//３．データ表示
$interview_type = array("書類選考","1次面接","2次面接","3次面接");
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res= $stmt->fetch();
}

//job_post.job_title抽出
$stmt2 = $pdo->prepare("SELECT job_title FROM job_post WHERE id =:job_post_id ");
$stmt2->bindValue(':job_post_id',$res["job_post_id"], PDO::PARAM_INT);

$status2 = $stmt2->execute();

//３．データ表示
if($status2==false){
  //execute（SQL実行時にエラーがある場合）
  $error2 = $stmt2->errorInfo();
  exit("ErrorQuery:".$error2[2]);
}else{
  $res_job_post= $stmt2->fetch();
}

//アンケート結果があれば表示する
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
<link rel="stylesheet" href="http://code.ionicframework.com/ionicons/1.4.1/css/ionicons.min.css">
<style>

.div_vertical-middle{
  /*vertical-align: middle;*/
  text-align: center;
  margin-top:auto;
  margin-bottom:auto;
}
div.item{
  margin-bottom:40px;
}
.item_title{
  margin:15px;
}
.form_title{
  margin:30px auto;
}
label{
  font-size: 1.5em;
}
label#inteviewer{
  font-size:1em;
}

.submit_btn {
  margin:40px;
}

/*ここからレーティング星*/
.input-range {
  display: none;
}
.range-group {
  position: relative;
}
.range-group > a {
  display: inline-block;
  width: 20px;
  height: 20px;
}
.range-group > a:before {
  content: "\f3ae";
  font-family: "Ionicons";
  font-size: 20px;
  color: #aaa;
}
.range-group > a.on:before {
  content: "\f2fc";
  color: #fc3;
}
</style>
</head>
<body>
<?php include("../template/nav.php") ?>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-5">
      <p class="text-center"><?=h($res["interviewee_name_kana"])?></p>
      <h2 class="text-center"><?=h($res["interviewee_name"])?></h2>
    </div>
    <div class="col-sm-5">
      <table class="table table-striped">
        <tr><th class="text-center">誕生日</th><td class="text-center"><?=h($res["birthday"])?></td></tr>
        <tr><th class="text-center">職種</th><td class="text-center"><?=h($res_job_post["job_title"])?></td></tr>
        <tr><th class="text-center">選考ステージ</th><td class="text-center"><?=$interview_type[h($res["interview_type"])]?></td></tr>
        <tr><th class="text-center">面接日時</th><td class="text-center"><?=h($res["interview_date_time"])?></td></tr>
        <?php if($res_anchet["anchet_id"]):?>
        <tr><th class="text-center">アンケート</th><td class="text-center"><a class="btn btn-default" href="../setting/questionnaire_show.php?anchet_id=<?= $res_anchet["anchet_id"]?>">アンケート結果</a></td></tr>
        <?php endif;?>
      </table>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<div class="container text-center">
<a class="btn btn-lg btn-info" href="web_interview.php?interview_id=<?= $interview_id ?>" target="_blank">web面接開始</a>
  </div>

<div class="container">
  <h2 class="text-center form_title">面接結果入力フォーム</h2>
  <div class="row">

  <form method="post" action="insert.php">
    <input type="hidden" name="interviewee_id" value="<?= $interviewee_id ?>">
    <input type="hidden" name="interview_id" value="<?= $interview_id ?>">
    <div class="item_0 item">
      <div class="row item_title">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="col-sm-10"><h3 class="text-center">能力/スキル</h3></div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
      <div class="row">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="form-group col-sm-2">
          <div class="row">
              <div class="col-sm-6"><label for="score_0">score</label></div>
              <div class="col-sm-6 range-group">
                 <input type="range" min="1" max="10" value="" class="input-range" name="score_0" required/>
                <!-- <input class="form-control" type="number" min="0" max="10" name="score_0"> -->
                <!-- <select class="form-control" name="score_0" required>
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select> -->
              </div>
          </div>
        </div>
        <div class="form-group col-sm-8">
          <div class="row">
              <div class="col-sm-2"><label for="qualitative_0">comment</label></div><div class="col-sm-10"><textarea class="form-control" name="qualitative_0"></textarea></div>
          </div>
        </div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
    </div>

    <div class="item_1 item">
      <div class="row item_title">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="col-sm-10"><h3 class="text-center">協調性</h3></div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
      <div class="row">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="form-group col-sm-2">
          <div class="row">
              <div class="col-sm-6"><label for="score_1">score</label></div>
              <div class="col-sm-6">
                <!-- <input class="form-control" type="number" min="0" max="10" name="score_0"> -->
                <select class="form-control" name="score_1">
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select>
              </div>
          </div>
        </div>
        <div class="form-group col-sm-8">
          <div class="row">
              <div class="col-sm-2"><label for="qualitative_1">comment</label></div><div class="col-sm-10"><textarea class="form-control" name="qualitative_1"></textarea></div>
          </div>
        </div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
    </div>

    <div class="item_2 item">
      <div class="row item_title">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="col-sm-10"><h3 class="text-center">コミュニケーション能力</h3></div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
      <div class="row">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="form-group col-sm-2">
          <div class="row">
              <div class="col-sm-6"><label for="score_2">score</label></div>
              <div class="col-sm-6">
                <!-- <input class="form-control" type="number" min="0" max="10" name="score_0"> -->
                <select class="form-control" name="score_2">
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select>
              </div>
          </div>
        </div>
        <div class="form-group col-sm-8">
          <div class="row">
              <div class="col-sm-2"><label for="qualitative_2">comment</label></div><div class="col-sm-10"><textarea class="form-control" name="qualitative_2"></textarea></div>
          </div>
        </div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
    </div>

    <div class="item_3 item">
      <div class="row item_title">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="col-sm-10"><h3 class="text-center">積極性</h3></div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
      <div class="row">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="form-group col-sm-2">
          <div class="row">
              <div class="col-sm-6"><label for="score_3">score</label></div>
              <div class="col-sm-6">
                <!-- <input class="form-control" type="number" min="0" max="10" name="score_0"> -->
                <select class="form-control" name="score_3">
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select>
              </div>
          </div>
        </div>
        <div class="form-group col-sm-8">
          <div class="row">
              <div class="col-sm-2"><label for="qualitative_3">comment</label></div><div class="col-sm-10"><textarea class="form-control" name="qualitative_3"></textarea></div>
          </div>
        </div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
    </div>
    <div class="item_4 item">
      <div class="row item_title">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="col-sm-10"><h3 class="text-center">モラル/性格面</h3></div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
      <div class="row">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="form-group col-sm-2">
          <div class="row">
              <div class="col-sm-6"><label for="score_4">score</label></div>
              <div class="col-sm-6">
                <!-- <input class="form-control" type="number" min="0" max="10" name="score_0"> -->
                <select class="form-control" name="score_4">
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select>
              </div>
          </div>
        </div>
        <div class="form-group col-sm-8">
          <div class="row">
              <div class="col-sm-2"><label for="qualitative_4">comment</label></div><div class="col-sm-10"><textarea class="form-control" name="qualitative_4"></textarea></div>
          </div>
        </div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
    </div>
    <div class="item_5 item">
      <div class="row item_title">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="col-sm-10"><h3 class="text-center">定着度</h3></div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
      <div class="row">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="form-group col-sm-2">
          <div class="row">
              <div class="col-sm-6"><label for="score_5">score</label></div>
              <div class="col-sm-6">
                <!-- <input class="form-control" type="number" min="0" max="10" name="score_0"> -->
                <select class="form-control" name="score_5">
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select>
              </div>
          </div>
        </div>
        <div class="form-group col-sm-8">
          <div class="row">
              <div class="col-sm-2"><label for="qualitative_5">comment</label></div><div class="col-sm-10"><textarea class="form-control" name="qualitative_5"></textarea></div>
          </div>
        </div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
    </div>
    <div class="item_6 item">
      <div class="row item_title">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="col-sm-10"><h3 class="text-center">総評</h3></div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
      <div class="row">
        <div class="col-sm-1 hidden-xs"></div>
        <div class="form-group col-sm-10">
          <div class="row">
              <div class="col-sm-2"><label for="comment">comment</label></div><div class="col-sm-10"><textarea class="form-control" name="comment"></textarea></div>
          </div>
        </div>
        <div class="col-sm-1 hidden-xs"></div>
      </div>
    </div>


<!-- koomadde -->

    <!-- <div class="form-group item_0">
    <label for="score_0">能力・スキル</label><input class="form-control" type="number" min="0" max="10" name="score_0">
    </div>
    <div class="form-group item_0">
    <label for="qualitative_0">能力・スキル定性評価</label><textarea class="form-control" name="qualitative_0"></textarea>
    </div>

    <div class="form-group item_1">
    <label for="score_1">協調性</label><input class="form-control" type="number" min="0" max="10" name="score_1">
    </div>
    <div class="form-group item_1">
    <label for="qualitative_1">協調性定性評価</label><textarea class="form-control" name="qualitative_1"></textarea>
    </div>

    <div class="form-group item_2">
    <label for="score_2">コミュニケーション能力</label><input class="form-control" type="number" min="0" max="10" name="score_2">
    </div>
    <div class="form-group item_2">
    <label for="qualitative_2">コミュニケーション能力定性評価</label><textarea class="form-control" name="qualitative_2"></textarea>
    </div>

    <div class="form-group item_3">
    <label for="score_3">積極性</label><input class="form-control" type="number" min="0" max="10" name="score_3">
    </div>
    <div class="form-group item_3">
    <label for="qualitative_3">積極性定性評価</label><textarea class="form-control" name="qualitative_3"></textarea>
    </div>

    <div class="form-group item_4">
    <label for="score_4">モラル・性格面</label><input class="form-control" type="number" min="0" max="10" name="score_4">
    </div>
    <div class="form-group item_4">
    <label for="qualitative_4">スキル定性評価</label><textarea class="form-control" name="qualitative_4"></textarea>
    </div>

    <div class="form-group item_5">
    <label for="score_5">定着度</label><input class="form-control" type="number" min="0" max="10" name="score_5">
    </div>
    <div class="form-group item_5">
    <label for="qualitative_5">定着度定性評価</label><textarea class="form-control" name="qualitative_5"></textarea>
    </div> -->
    <div class="submit_btn text-center">
      <input class="btn btn-default btn-lg" type="submit" name="" value="確定">
    </div>
  </form>
  </div>
</div>
<?php include("../template/footer.html") ?>
</body>
<script>
$(function() {
  $('.range-group').each(function() {
    for (var i = 0; i < 10; i ++) {
      $(this).append('<a>');
    }
  });
  $('.range-group>a').on('click', function() {
     var index = $(this).index();
    $(this).siblings().removeClass('on');
     for (var i = 0; i < index; i++) {
        $(this).parent().find('a').eq(i).addClass('on');
     }
    $(this).parent().find('.input-range').attr('value', index);
  });
});
</script>

</html>
