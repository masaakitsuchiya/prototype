<?php

session_start();
include("../function/function.php");
include("../function/setting.php");
$_SESSION["interviewee_id"] = "";
$_SESSION["interviewee_id"] = $_GET["interviewee_id"];



$pdo = db_con();

$profile_flg = profile_flg();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT interviewee_name FROM interviewee_info WHERE id =:interviewee_id");
$stmt->bindValue(':interviewee_id',$_SESSION["interviewee_id"],PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res_interviewee_info = $stmt->fetch();
}

$_SESSION["user_name"] = $res_interviewee_info["interviewee_name"];



//interview 情報の出力
$stmt = $pdo->prepare("SELECT interview.id,interview.interview_type,interview.interview_style,interview.interview_date_time FROM interview INNER JOIN interviewee_info ON interviewee_info.id = interview.interviewee_id WHERE interview.stage_flg = :stage_flg AND interviewee_info.id = :interviewee_id ");
$stmt->bindValue(':interviewee_id',$_SESSION["interviewee_id"],PDO::PARAM_INT);
$stmt->bindValue(':stage_flg',3,PDO::PARAM_INT);//3=面接日程確定

$status_interview = $stmt->execute();

$view_interview ="";
if($status_interview==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view_interview .= '<div class="row interview_info">';
    $view_interview .= '<div class="col-sm-1"></div>';

    if($profile_flg == 1){//面接担当者プロフィールが公開設定だったら
      $view_interview .= '<div class="col-sm-5">';
          $interview_type_str = interview_type($result["interview_type"]);
      if($result["interview_style"] == 0){//ウェブ面接の時
        $view_interview .= '<h2 class="text-center">'.h($interview_type_str).'(ウェブ面接)</h2>';
        $view_interview .= '<div class="text-center"><span class="interview_info">'.h($result["interview_date_time"]).'開始</span></div>';
        $view_interview .= '<p>面接日時の5分前には下記ボタンから面接画面にアクセスして準備を完了していただきますようお願い致します。</p>';
        $view_interview .= '<div class="text-center"><ul class="list-inline list-unstyled"><li><a href="../result/web_interview.php?interview_id='.h($result["id"]).'&interviewee_id='.$_SESSION["interviewee_id"].'" class="btn btn-info" target="_blank"><span class="glyphicon glyphicon-facetime-video"></span> 面接画面へ</a></li><li><a class="btn btn-default" href="#">キャンセル</a><li></ul></div>';
        $view_interview .= '</div>';
      }elseif($result["interview_style"] == 1){//通常面接の時
        $view_interview .= '<h2 class="text-center">'.h($interview_type_str).'</h2>';
        $view_interview .= '<div class="text-center"><span class="interview_info">'.h($result["interview_date_time"]).'開始</span></div>';
        $view_interview .= '<p>面接日時の5分前には面接場所にお越しいただきますようお願い致します。</p>';
        $view_interview .= '<div class="text-center"><ul class="list-inline list-unstyled">><li><a class="btn btn-default" href="#">キャンセル</a><li></ul></div>';
        $view_interview .= '</div>';
      }

      $view_interview .= '<div class="col-sm-5">';//面接者情報
      $view_interview .= '<h2 class="text-center"></h2>';
      $view_interview .= '<h3 class="text-center">面接担当者</h3>';
      $view_interview .= '<p>以下の者が当日はお話させていただきます。よろしくお願いいたします。プロフィールも確認できますのでぜひご覧ください。</p>';
      $view_interview .= '<div class="row">';
        $stmt_interviewer = $pdo->prepare("SELECT * FROM interviewer_list INNER JOIN interviewer_info ON interviewer_list.interviewer_id = interviewer_info.id WHERE interviewer_list.interview_id = :interview_id");
        $stmt_interviewer->bindValue(':interview_id',$result["id"],PDO::PARAM_INT);
        $status_interviewer = $stmt_interviewer->execute();
        if($status_interviewer==false){
          //execute（SQL実行時にエラーがある場合）
          $error = $stmt_interviewer->errorInfo();
          exit("ErrorQuery_interviewer:".$error[2]);
        }else{
          //Selectデータの数だけ自動でループしてくれる
          while( $result_interviewer = $stmt_interviewer->fetch(PDO::FETCH_ASSOC)){
              $view_interview .= '<div class="col-sm-6 interviewer_item">';
              $view_interview .= '<div class="text-center"><img class="interviewer_img img-responsive img-circle center-block" src="'.h($result_interviewer["interviewer_img"]).'" alt=""></div>';
              // // $view_interview .= '<dl class="dl-horizontal">';
              // $view_interview .= '<dt>名前</dt><dd>'.$result_interviewer["interviewer_name"].'</dd>';
              // $view_interview .= '<dt>部署</dt><dd>'.$result_interviewer["department"].'</dd>';
              // $view_interview .= '<dt>役職</dt><dd>'.$result_interviewer["title"].'</dd>';
              // $view_interview .= '</dl>';
              $view_interview .= '<div class="text-center">';
              $view_interview .= '<div>名前:'.h($result_interviewer["interviewer_name"]).'</div>';
              $view_interview .= '<div>部署:'.h($result_interviewer["department"]).'</div>';
              $view_interview .= '<div>役職:'.h($result_interviewer["title"]).'</div>';
              $view_interview .= '</div>';
              // $view_interview .= '<div class="text-center"><a href="interviewer_show.php?interviewer_id='.$result_interviewer["interviewer_id"].'" class="btn btn-default btn-sm">プロフィール詳細</a></div>';

              $view_interview .= '<div class="text-center"><a data-toggle="modal" href="interviewer_show0.php?interviewer_id='.h($result_interviewer["interviewer_id"]).'" data-target="#remoteModal" class="remoteModallink btn btn-default btn-sm">プロフィール詳細</a></div>';
            }
            $view_interview .= '</div>';
        }
      $view_interview .= '</div>';//row
      $view_interview .= '</div>';//面接者情報
      //面接担当者プロフィールが公開設定だったら end
    }elseif($profile_flg == 0){//面接担当者プロフィールが非公開設定だったら
        $view_interview .= '<div class="col-sm-10">';
            $interview_type_str = interview_type($result["interview_type"]);
        if($result["interview_style"] == 0){//ウェブ面接の時
          $view_interview .= '<h2 class="text-center">'.h($interview_type_str).'(ウェブ面接)</h2>';
          $view_interview .= '<div class="text-center"><span class="interview_info">'.h($result["interview_date_time"]).'開始</span></div>';
          $view_interview .= '<p class="text-center">面接日時の5分前には下記ボタンから面接画面にアクセスして準備を完了していただきますようお願い致します。</p>';
          $view_interview .= '<div class="text-center"><ul class="list-inline list-unstyled"><li><a href="../result/web_interview.php?interview_id='.h($result["id"]).'&interviewee_id='.$_SESSION["interviewee_id"].'" class="btn btn-info" target="_blank"><span class="glyphicon glyphicon-facetime-video"></span> 面接画面へ</a></li><li><a class="btn btn-default" href="#">キャンセル</a><li></ul></div>';
          $view_interview .= '</div>';
        }elseif($result["interview_style"] == 1){//通常面接の時
          $view_interview .= '<h2 class="text-center">'.h($interview_type_str).'</h2>';
          $view_interview .= '<div class="text-center"><span class="interview_info">'.h($result["interview_date_time"]).'開始</span></div>';
          $view_interview .= '<p class="text-center">面接日時の5分前には面接場所にお越しいただきますようお願い致します。</p>';
          $view_interview .= '<div class="text-center"><ul class="list-inline list-unstyled">><li><a class="btn btn-default" href="#">キャンセル</a><li></ul></div>';
          }
        $view_interview .= '</div>';
    }
    $view_interview .= '<div class="col-sm-1"></div>';
    $view_interview .= '</div>';//row
  }
}
//anchet情報の出力

$stmt_anchet = $pdo->prepare("SELECT * FROM anchet WHERE interviewee_id = :interviewee_id ");
$stmt_anchet->bindValue(':interviewee_id',$_SESSION["interviewee_id"],PDO::PARAM_INT);
$status_anchet = $stmt_anchet->execute();

if($status_anchet==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_anchet->errorInfo();
  exit("ErrorQuery_anchet:".$error[2]);
}else{
  $res_anchet= $stmt_anchet->fetch();
}



//会社情報の出力

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

$pdf_url_for_iframe = url_folder_name_remove("pdfjs",$res_corp_info["up_pdf"]);

$url_for_pdf = path_for_mail();
$html_title = '無料から使えるクラウド採用管理、面接システム Smart Interview';
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head_for_interviewee.php") ?>
<style>
/*html, body {
  height: 100%;
  margin: 0;
  padding: 0;
}*/
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
#map{
  /*height:20%;*/
  padding-bottom:50%;
}
</style>
</head>
<body>
<?php include("../template/nav_for_interviewee_index.php") ?>
<div class="container-fruid">
  <h1 class="text-center">面接に関するお知らせ</h1>
  <p class="text-center"><small>（個人情報保護のためお名前は表示しておりません。）</small></p>
<!-- <h1 class="text-center"><?= h($res_interviewee_info["interviewee_name"]);?>様</h1> -->
</div>

<div class="container interview_info_all">
  <h2 class="text-center title" id="interview_item">面接情報</h2>
  <?= $view_interview ?>
</div>

<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true" data-show="true" data-keyboard="false" data-backdrop="static">
   <div class="modal-dialog">
     <div class="modal-content">
     </div> <!-- /.modal-content -->
   </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->
<?php if($res_anchet["stage_flg"]==1):?>
<div class="container interview_info_all">
  <h2 class="text-center title">アンケート</h2>
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="text-center">
    <p>アンケートに入力してください。</p>
    <div>返信期限:<span style="color:red;"><?= h($res_anchet["deadline"]); ?></span></div>
      <a class="btn btn-info" href="reply_anchet.php?anchet_id=<?= h($res_anchet["anchet_id"]); ?>">アンケートに入力する</a>
      </div>
    </div>
  <div class="col-sm-1"></div>
  </div>
</div>
<?php elseif($res_anchet["stage_flg"]==2):?>
  <div class="container interview_info_all">
    <h2 class="text-center title">アンケート</h2>
    <div class="row">
      <div class="col-sm-1"></div>
      <div class="col-sm-10">
        <div class="text-center">
          <p><?= $res_anchet["recieved_date"] ?>に返信済みです。</p>
          <a class="btn btn-default" href="reply_show.php?anchet_id=<?=h($res_anchet["anchet_id"]);?>" target="_blank">返信済みアンケートを確認する。</a>
        </div>
      </div>
    <div class="col-sm-1"></div>
    </div>
  </div>
<?php endif ?>


<div class="container-fruid">
  <div class="row content_item">
    <h2 class="text-center title" id="info_top">会社情報</h2>
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="row content_sub_item">
        <div class="col-sm-5"></div>
        <div class="col-sm-2 text-center"><img class="img-responsive center-block" src="<?=h($res_corp_info["catch_photo"]);?>" alt=""></div>
        <div class="col-sm-5"></div>
      </div>
      <div class="row content_sub_item">
        <div class="col-sm-5"></div>
        <div class="col-sm-2 text-center"><a class="btn btn-success btn-sm" href="<?=h($res_corp_info["corp_url"]);?>" target="_blank">corprate site</a></div>
        <div class="col-sm-5"></div>
      </div>
      <div class="row content_sub_item">
        <div class="col-sm-3"></div>
        <div class="col-sm-6"><?=hd($res_corp_info["info_text"]);?></div>
        <div class="col-sm-3"></div>
      </div>
    </div>
    <div class="col-sm-1"></div>
  </div>
  <!-- <div class="row">
    <h3 class="text-center item_title">参考資料</h3>
    <div class="col-sm-1"></div>
    <div class="col-sm-10"><object data="<?php if($res_corp_info["up_pdf"]){echo $res_corp_info["up_pdf"]; }?>" type="application/pdf" width="100%" height="auto"></object></div>
    <div class="col-sm-1"></div>
  </div> -->
  <div class="row content_item" id="reference_material">
    <h3 class="text-center item_title">参考資料</h3>
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
      <iframe src='<?= $url_for_pdf ?>pdfjs/web/viewer.html?file=<?php if($pdf_url_for_iframe){echo h($pdf_url_for_iframe); }?>' width="100%" height="400" style="border: none;"></iframe>
      </div>
    <div class="col-sm-3"></div>
  </div>
  <div class="row content_item" id="company_video">
    <h3 class="text-center item_title">紹介動画</h3>
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
      <video class="video-responsive" src="<?php if($res_corp_info["company_video"]){echo h($res_corp_info["company_video"]); }?>" alt="" preload="auto" onclick="this.play()" controls>
    </div>
    <div class="col-sm-3"></div>
  </div>

  <div class="row content_item" id="access">
    <h3 class="text-center item_title">アクセス</h3>
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="text-center item_s"><?= h($res_corp_info["address"]);?></div>
      <div class="text-center item_s"><?= h($res_corp_info["tel"]);?></div>
      <div class="text-center item_s"><a class="btn btn-success btn-sm" href="<?=h($res_corp_info["corp_url"]); ?>" target="_blank">corprate site</a></div>
      <div id="map"></div>
    </div>
    <div class="col-sm-1"></div>

  </div>
</div>

<?php include("../template/footer_for_interviewee.html") ?>

<script>
var plat;
var plon;

  // var address = encodeURIComponent("東京都杉並区高円寺南6－14");
  var address = encodeURIComponent('<?php echo h($res_corp_info["address"]);?>');
  console.log(address);

  var google_geolocation_api = "https://maps.googleapis.com/maps/api/geocode/json?address=" + address + "&key=AIzaSyC2ZQj8dGnc9KjzFs1peNKU8JIRHvTQofM";

  const p1 = $.getJSON(google_geolocation_api, function(json){
    console.dir(json);
    plat = json.results["0"].geometry.location.lat;
    plon = json.results["0"].geometry.location.lng;
  });

  // The following example creates a marker in Stockholm, Sweden using a DROP
  // animation. Clicking on the marker will toggle the animation between a BOUNCE
  // animation and no animation.

  var marker;

  function initMap() {
    p1.then(() => {
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: {lat: plat, lng: plon}
      });

      marker = new google.maps.Marker({
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP,
        position: {lat: plat, lng: plon}
      });
      marker.addListener('click', toggleBounce);
    }).catch(err => {
      console.log('err : ', err)
    })

  }

  function toggleBounce() {
    if (marker.getAnimation() !== null) {
      marker.setAnimation(null);
    } else {
      marker.setAnimation(google.maps.Animation.BOUNCE);
    }
  }
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC2ZQj8dGnc9KjzFs1peNKU8JIRHvTQofM&callback=initMap">
</script>
</body>
</html>
