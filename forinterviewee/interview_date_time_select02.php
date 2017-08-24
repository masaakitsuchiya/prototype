<?php

// /10/my_kaday/setting/interview_date_time_select01.php?interviewee_id=*&interview_id=*

session_start();
include("../function/function.php");
if(isset($_GET["interview_id"]) || !$_GET["interview_id"] == ""){
$_SESSION["interview_id"] = $_GET["interview_id"];
}

$skyway_key = skyway_key();
$html_title = '無料から使えるクラウド採用管理、面接システム Smart Interview';
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head_for_interviewee.php") ?>
<script src="https://skyway.io/dist/0.3/peer.min.js"></script>
<script src="https://skyway.io/dist/multiparty.min.js"></script>

<style>

.container-fruid{
  margin-bottom:30px;
}
video {
  width:300px;
}
h4.pg{
font-size:0.9em;
}
.gray{
  color:#aaa;
}
.submit_area{
  margin-top:30px;
  margin-bottom:30px;
}
.row{
  margin-bottom:40px;
}

p{
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
      <h4 class="col-xs-2 pg text-center gray">1,規約同意</h4><h4 class="col-xs-2 pg text-center">2,動作検証</h4><h4 class="col-xs-2 pg text-center gray">3,面接日時選択</h4><h4 class=" col-xs-2 pg text-center gray">4,返信完了
      </h4>
      <div class="col-xs-2 hidden-xs"></div>
  </div>
</div>

<div class="container-fruid">
  <div class="row">
    <div class="col-xs-2 hidden-xs"></div>
    <div class="col-xs-8">
      <h3 class="text-center">動作検証</h3>
      <p>　ウェブ面接機能を利用するためには以下の環境が必要になります。また、パソコンをご使用の場合は、カメラとマイクを接続の上、動作確認を行なってください。<br>
      　もし環境がご用意できない場合は、<a href="interview_reset.php">こちら</a>からご連絡ください。</p>
      <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
          <dl class="dl-horizontal">
            <dt>対応ブラウザ</dt>
            <dd>chrome, firefox</dd>
            <dt>対応環境</dt>
            <dd>windows,macOSのパソコン、androidのインストールされたスマートフォン</dd>
            <dt>通信環境</dt>
            <dd>wifi及び有線でのインターネット環境を推奨します</dd>
          </dl>
          <p class="text-center">※iPhoneをご使用の場合、専用アプリをインストールする必要が あります</p>
        </div>
        <div class="col-sm-2"></div>
      </div>
      <div class="text-center"><button class="btn btn-info btn-lg" id="check_start">動作検証</button></div>
      <div class="row" id="self_video">
        <video id="their-video" autoplay></video>
        <video id="my-video" muted="true" autoplay></video>
      </div>
      <div class="text-center" id="result"></div>
    </div>
  </div>
</div>
<div class="container-fruid">
  <form action="interview_date_time_select03.php" method="post">
    <div class="text-center">
      <input type="checkbox" id="check" />
      <label for="check">カメラとマイクが動作して自分の画像が表示されました。</label>
    </div>
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8">
        <p class="text-center">自分の画像が表示されない場合は<a href="interview_reset.php">こちら</a>からご連絡ください。</p>
      </div>
      <div class="col-sm-2"></div>
    </div>
     <div class="text-center submit_area">
       <a class="btn btn-default" href="interview_date_time_select01.php?interview_id=<?= h($_SESSION["interview_id"]);?>">戻る</a>
       &emsp;
      <input type="submit" class="btn btn-info" id="submit" value="次へ" />
    </div>
  </form>
</div>
<?php include("../template/footer_for_interviewee.html") ?>
<script>

  $(function() {
  	$('#submit').attr('disabled', 'disabled');

  	$('#check').click(function() {
  		if ($(this).prop('checked') == false) {
  			$('#submit').attr('disabled', 'disabled');
  		} else {
  			$('#submit').removeAttr('disabled');
  		}
  	});
  });

  $(function(){
    $('#check_start').click(
      function(){
    // Compatibility shim
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    // PeerJS object
    var peer = new Peer({ key: '<?= h($skyway_key); ?>', debug: 3});
    peer.on('open', function(){
      $('#my-id').text(peer.id);
    });
    // Receiving a call
    peer.on('call', function(call){
      // Answer the call automatically (instead of prompting user) for demo purposes
      call.answer(window.localStream);
      step3(call);
    });
    peer.on('error', function(err){
      alert(err.message);
      // Return to step 2 if error occurs
      step2();
    });
    // Click handlers setup
    $(function(){
      $('#make-call').click(function(){
        // Initiate a call!
        var call = peer.call($('#callto-id').val(), window.localStream);
        step3(call);
      });
      $('#end-call').click(function(){
        window.existingCall.close();
        step2();
      });
      // Retry if getUserMedia fails
      $('#step1-retry').click(function(){
        $('#step1-error').hide();
        step1();
      });
      // Get things started
      step1();
    });
    function step1 () {
      // Get audio/video stream
      navigator.getUserMedia({audio: true, video: true}, function(stream){
        // Set your video displays
        $('#my-video').prop('src', URL.createObjectURL(stream));
        window.localStream = stream;
        step2();
      }, function(){ $('#step1-error').show(); });
    }
    function step2 () {
      $('#step1, #step3').hide();
      $('#step2').show();
    }
    function step3 (call) {
      // Hang up on an existing call if present
      if (window.existingCall) {
        window.existingCall.close();
      }
      // Wait for stream on the call, then set peer video display
      call.on('stream', function(stream){
        $('#their-video').prop('src', URL.createObjectURL(stream));
      });
      // UI stuff
      window.existingCall = call;
      $('#their-id').text(call.peer);
      call.on('close', step2);
      $('#step1, #step2').hide();
      $('#step3').show();
    }
  }
)

});






</script>
</body>
</html>
