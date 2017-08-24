<?php
session_start();
include("../function/function.php");
login_check();
if($_SESSION){
$interviewer_name = $_SESSION["user_name"];
$interviewer_id = $_SESSION["user_id"];
}else{
  header("Location: ../login_out/login.php");
  exit;
}
$interview_id = $_GET["interview_id"];
$skyway_key = skyway_key();

$interviewer_id_str = (string)$interviewer_id;
$interview_id_str = (string)$interview_id;
$html_title = '無料から使えるクラウド採用管理、面接システム Smart Interview';
?>
<?php include("../template/head.php") ?>
<script src="https://skyway.io/dist/0.3/peer.min.js"></script>
<script src="https://skyway.io/dist/multiparty.min.js"></script>
<style>

  <style> video { width:300px; } </style>

</head>
<body>
  <div class="container">
    <div class="row" id="streams">
      <div class="col-sm-4" id="video_0">
      </div>
      <div class="col-sm-4" id="video_1">
      </div>
      <div class="col-sm-4" id="video_2">
      </div>
    </div>
    <div class="row" id="streams">
      <div class="col-sm-4" id="video_3">
      </div>
      <div class="col-sm-4" id="video_4">
      </div>
      <div class="col-sm-4" id="video_5">
      </div>
    </div>
  </div>
<script>

  // MultiParty インスタンスを生成
  multiparty = new MultiParty( {
    "key": "<?= $skyway_key ?>", /* SkyWay keyを指定 */
    "reliable": true,
    "id" : <?=$interviewer_id_str ?>,
    "room" : <?=$interview_id_str ?>
  });
  var i = 0;

    // var streams = $("#streams")
  multiparty.on('my_ms', function(video) {
    // 自分のvideoを表示
    var vNode = MultiParty.util.createVideoNode(video);
    $(vNode).appendTo('#video_' + i);
    i++;
  }).on('peer_ms', function(video) {
    // peerのvideoを表示1
    var vNode = MultiParty.util.createVideoNode(video);
    $(vNode).appendTo('#video_' + i);
    i++;
  }).on('ms_close', function(peer_id) {
    // peerが切れたら、対象のvideoノードを削除する
    $("#"+peer_id).remove();
  });

  // サーバとpeerに接続
  multiparty.start();

</script>
</body>
</html>
