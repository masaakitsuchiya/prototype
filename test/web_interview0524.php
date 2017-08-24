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

$interviewer_id_str = "interviewer".(string)$interviewer_id;
$interview_id_str = "room".(string)$interview_id;
// var_dump($interviewer_id_str);
// var_dump($interview_id_str );
// exit;

$html_title = '無料から使えるクラウド採用管理、面接システム Smart Interview';
?>
<?php include("../template/head_for_webinterview.php") ?>
<script src="https://skyway.io/dist/0.3/peer.min.js"></script>
<script src="../js/multiparty.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
body{
  height:800px;
}
video {
  width:100%;
  height:100%;
  -webkit-transform: scaleX(-1);
 }
.mobile_text_chat_area{
  /*background-color: rgba(255,0,0,0.8);
  opacity:0.4;*/
}
.receive{
  overflow:auto;
  width:95%;
}
#receive_pc{
  margin-top:20px;
  background:#fff;
  padding-top:20px;
  padding-bottom:10px;
  height:400px;
  border: 2px groove #fff;
  border-radius: 5px;
}
#receive_mobile{
  /*background:#fff;*/
  padding-top:10px;
  padding-bottom:10px;
  height:350px;
  /*opacity:0.2;*/
}

#main{
  /*background-image: url("http://www.wallpaper-box.com/sky/images/sky37.jpg")*/
}

.my_message{
  margin:5px;
  background:rgba(10,100,255,1);
  color:white;
  padding:7px;
  border-radius:10px;
  display:inline-block;
}
#receive_mobile .my_message{
  background:rgba(10,100,255,0.3);
}
#message_xs_input{
  background:rgba(255,255,255,0.3);
  color:white;
}
.peer_message{
  margin:5px;
  background:#ccc;
  padding:7px;
  border-radius: 10px;
  display:inline-block;
}
#receive_mobile .peer_message{
  background:rgba(200,200,200,0.5);
  color:white;
  
}
#message_xs .btn-default{
  background:rgba(200,200,200,0.3);
  color:white;
}
.modal-footer .btn-info{
    background:rgba(10,100,255,0.3);
}
h4.modal-title{
  color:white;
}
/*.control-panel{
  position:fixed;
  bottom:15px;
}
*/
/*.self-video{
  position:fixed;
  bottom:60px;
}*/

#message_xs{
  display:inline-block;
  width:95%;
}
#message{
  display:inline-block;
  width:95%;
}

#pc_chat_area{
}
/*#message{
  display:inline-block;
  width:95%;
}*/
/*#buttons{
  display:inline-block;
}*/
.material-icons {
  font-family: 'Material Icons';
  font-weight: normal;
  font-style: normal;
  font-size: 24px;  /* 推奨サイズ */
  display: inline-block;
  width: 1em;
  height: 1em;
  line-height: 1;
  text-transform: none;

  /* WebKitブラウザサポート */
  -webkit-font-smoothing: antialiased;
  /* Chrome、Safariサポート */
  text-rendering: optimizeLegibility;

  /* Firefoxサポート */
  -moz-osx-font-smoothing: grayscale;

  /* IEサポート */
  font-feature-settings: 'liga';
}
.material-icons.md-18 { font-size: 18px; }
.material-icons.md-24 { font-size: 24px; }
.material-icons.md-36 { font-size: 36px; }
.material-icons.md-48 { font-size: 48px; }

/* 背景が明るいとき用のアイコン色 */
.material-icons.md-dark { color: rgba(0, 0, 0, 0.54); }
.material-icons.md-dark.md-inactive { color: rgba(0, 0, 0, 0.26); }

/* 背景が暗いとき用のアイコン色 */
.material-icons.md-light { color: rgba(255, 255, 255, 1); }
.material-icons.md-light.md-inactive { color: rgba(255, 255, 255, 0.3); }

.btn_text{
  font-size:0.2em;
}

/*.self_video_area{
  background-color:#000;
  /*background-image:url("self_video.jpg");
  background-size:cover;
  background-repeat:no-repeat;
  background-position: center center;
}
*/
.video_area{
  height:200px;
  background-image:url("white.jpeg");
  background-size:cover;
  background-repeat:no-repeat;
  background-position: center center;
}
#self_video_area{
  height:100px;
  background-image:url("white.jpeg");
  background-size:cover;
  background-repeat:no-repeat;
  background-position: center center;

}

#btn_area{
  margin-top:20px;
}
#timer_area{
  font-size:2em;
}
</style>
<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Cabin+Condensed&text=SmartInterview">
<script src="https://use.fontawesome.com/16c63c33a4.js"></script>
</head>
<body>
  <header>
    <div class="container-fruid">
      <div class="row">
        <div class="col-xs-12 text-center">
          <a class="navbar-brand text-primary" href="#" style="font-family:'Cabin+Condensed',serif;"><i class="fa fa-id-card" aria-hidden="true"></i> InterFree</a>
        </div>
      </div>
      <div class="text-right">
        <a class="btn btn-default visible-xs" data-toggle="modal" href="#mobile_text_chat_area"><i class="glyphicon glyphicon-comment"></i> Text Message</a>
      </div>
    </div>
  </header>
  <div class="container-fruid" id="main">
      <!-- xs用テキストチャット -->
    <div id="mobile_text_chat_area" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content" style="background:rgba(255,255,255,0);">
          <div class="modal-header">
            <button class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title text-center">Text Message</h4>
          </div>
          <div class="modal-body">
            <p id="receive_mobile" class="receive">
            </p>
            <div id="message_xs">
              <form class="form">
                <div class="input-group">
                  <input id="message_xs_input" type="text" class="form-control" placeholder="メッセージを入力">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-send"></i></button>
                  </span>
                </div>
              </form>
            </div>
          </div>
          <div class="modal-footer">
              <button class="btn btn-info" data-dismiss="modal">閉じる</button>
          </div>
        </div>
      </div>
    </div>
    <!-- xs用テキストチャット ここまで-->
    <div class="row peer-video">
      <div class="col-sm-8">
        <div class="row" id="streams">
          <div class="col-xs-6 video_area text-center" id="video_0">
          </div>
          <div class="col-xs-6 video_area text-center" id="video_1">
          </div>
          <div class="col-xs-6 video_area text-center" id="video_2">
          </div>
          <div class="col-xs-6 video_area text-center" id="video_3">
          </div>
        </div>
      <div class="row self-video">
          <div class="col-sm-1 hidden-xs"></div>
          <div class="col-sm-4 text-center" id="self_video_area">
            <div id="self_video"></div>
          </div>
          <div class="col-sm-6">
            <div id="timer_area" class="text-primary text-center">00:00:00</div>
            <div id="btn_area" class="text-center">
              <div class="btn-group" role="group">
                <button class="btn btn-info" type="button" name="start" id="start"><i class="material-icons">play_circle_filled</i><br><span class="btn_text">&nbsp;&nbsp;Start&nbsp;&nbsp;</span></button>
                <button class="btn btn-info" type="button" id="audio-mute" data-muted="false" disabled><i class="material-icons">mic</i><br><span class="btn_text">&nbsp;&nbsp;Mute&nbsp;&nbsp;</span></button>
                <button class="btn btn-info" type="button" id="video-mute" data-muted="false" disabled><i class="material-icons">videocam</i><br><span class="btn_text">Video off</span></button>
              </div>
            </div>
          </div>
          <div class="col-sm-1 hidden-sx"></div>
            <!-- <div class="btn-toolbar" role="toolbar" style="display:inline-block;">
              <div class  ="btn-group" role="group">
                <button class="btn btn-primary" type="button" name="start" id="start"><i class="glyphicon glyphicon-facetime-video"></i> 面接を開始</button>
                <button class="btn btn-danger"type="button" name="exit" id="exit" disabled ><i class="glyphicon glyphicon-remove-circle"></i> 退出</button>
              </div>
              <div class="btn-group" role="group">
                <button class="btn btn-warning" type="button" id="video-mute" data-muted="false" disabled><i class="glyphicon glyphicon-facetime-video"></i><i class="glyphicon glyphicon-remove" style="font-size:0.3em;"></i></button>
                <button class="btn btn-success" type="button" id="audio-mute" data-muted="false" disabled><i class="glyphicon glyphicon-volume-off"></i></button>
              </div>
            </div> -->
      </div>
     </div>
    <div class="col-sm-4 hidden-xs" id="pc_chat_area">
      <p id="receive_pc" class="receive">
      </p>
      <div id="message">
          <form class="form">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="メッセージを入力">
              <span class="input-group-btn">
                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-send"></i></button>
              </span>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
<script>

  // MultiParty インスタンスを生成
  multiparty = new MultiParty( {
    "key": "<?= $skyway_key ?>", /* SkyWay keyを指定 */
    "reliable": true,
    /* "id" : '<?=$interviewer_id_str ?>', */
    "room" : '<?=$interview_id_str ?>',
    "secure": false
  });
  var i = 0;

  //peer_idにuser_idをいれればいいんじゃない？

    // var streams = $("#streams")
  multiparty.on('my_ms', function(video) {
    // 自分のvideoを表示
    var vNode = MultiParty.util.createVideoNode(video);
    vNode.volume = 0;
    $(vNode).appendTo('#self_video');
  }).on('peer_ms', function(video) {
    // peerのvideoを表示1
    var vNode = MultiParty.util.createVideoNode(video);
    $(vNode).appendTo('#video_' + i);
    i++;
  }).on('ms_close', function(peer_id) {
    // peerが切れたら、対象のvideoノードを削除する
    $("#"+peer_id).remove();
  });
  console.log(multiparty.room);

  multiparty.on('message', function(mesg) {
       // peerからテキストメッセージを受信
  $('.receive').append('<div class="text-left"><div class="peer_message">' + mesg.data + '</div></div>');
  $('.receive').animate({scrollTop: $('.receive')[0].scrollHeight}, 'fast');
  });

  $("#message form").on("submit", function(ev) {
        ev.preventDefault();  // onsubmitのデフォルト動作（reload）を抑制

        // テキストデータ取得
        var $text = $(this).find("input[type=text]");
        var data = $text.val();

        if(data.length > 0) {
          data = data.replace(/</g, "&lt;").replace(/>/g, "&gt;");
          $('.receive').append('<div class="text-right"><div class="my_message bg-primary">' + data + '</div></div>');
          $('#receive_pc').animate({scrollTop: $('#receive_pc')[0].scrollHeight}, 'fast');
          $('#receive_mobile').animate({scrollTop: $('#receive_mobile')[0].scrollHeight}, 'fast');
          // メッセージを接続中のpeerに送信する
          multiparty.send(data);
          $text.val("");
        }
    });
    $("#message_xs form").on("submit", function(ev) {
          ev.preventDefault();  // onsubmitのデフォルト動作（reload）を抑制

          // テキストデータ取得
          var $text = $(this).find("input[type=text]");
          var data = $text.val();

          if(data.length > 0) {
            data = data.replace(/</g, "&lt;").replace(/>/g, "&gt;");
            $('.receive').append('<div class="text-right"><div class="my_message bg-primary">' + data + '</div></div>');
            $('#receive_pc').animate({scrollTop: $('#receive_pc')[0].scrollHeight}, 'fast');
            $('#receive_mobile').animate({scrollTop: $('#receive_mobile')[0].scrollHeight}, 'fast');

            // メッセージを接続中のpeerに送信する
            multiparty.send(data);
            $text.val("");
          }
      });

  //サーバとpeerに接続
  var sec = 0;
  var min = 0;
  var hour = 0;
  var video_on_of = false;
  $('#start').on('click',function(){
    if(!video_on_of){
    multiparty.start();
    $('#start').html('<i class="material-icons">stop</i><br><span class="btn_text">&nbsp;&nbsp;Stop&nbsp;&nbsp;</span>');
    // $('#exit').prop("disabled",false);
    $("#video-mute").prop("disabled",false);
    $("#audio-mute").prop("disabled",false);
    video_on_of = true;
    setInterval(function() {
    // カウントアップ
    sec += 1;

    if (sec > 59) {
      sec = 0;
      min += 1;
    }

    if (min > 59) {
      min = 0;
      hour += 1;
    }

    // 0埋め
    sec_number = ('0' + sec).slice(-2);
    min_number = ('0' + min).slice(-2);
    hour_number = ('0' + hour).slice(-2);

    $('#timer_area').html(hour_number + ':' +  min_number + ':' + sec_number);
  },1000);



  }else if(video_on_of){
      multiparty.close();
      $('#start').html('<i class="material-icons">play_circle_filled</i><br><span class="btn_text">&nbsp;&nbsp;Start&nbsp;&nbsp;</span>');
      $("#video-mute").prop("disabled",true);
      $("#audio-mute").prop("disabled",true);
      video_on_of = false;
    }
  });

  // $('#exit').on('click',function(){
  //   multiparty.close();
  //   $('#self_video').html("");
  //
  //   $("#video-mute").prop("disabled",true);
  //   $("#audio-mute").prop("disabled",true);
  //   $("#start").prop("disabled",false).text("面接終了").removeClass('btn-info').addClass('btn-default');
    //再接続
    // });

  $("#video-mute").on("click", function(ev) {
    var mute = !$(this).data("muted");
    multiparty.mute({video: mute});
    // $(this).text("video " + (mute ? "unmute" : "mute")).data("muted", mute);
    $(this).html( mute ? '<i class="material-icons">videocam</i></i><br><span class="btn_text">&nbsp;Video on&nbsp;</span>' : '<i class="material-icons">videocam_off</i><br><span class="btn_text">Video off</span>').data("muted", mute);
  });

  $("#audio-mute").on("click", function(ev) {
    var mute = !$(this).data("muted");
    multiparty.mute({audio: mute});
    $(this).html( mute ? '<i class="material-icons">mic</i><br><span class="btn_text">&nbsp;&nbsp;Mute&nbsp;&nbsp;</span>' : '<i class="material-icons">mic_off</i><br><span class="btn_text">Unmute</span>').data("muted", mute);
  });

  $("#mobile_text_chat_button").click(function(){
    $('#mobile_text_chat_area').toggle();
  });
  // $('#mute').on('click',function(){
  //   multiparty.mute({"video":true,"audio":true);
  // });

  // $('#unmute').on('click',function(){
  //   multiparty.unmute("video":true,"audio":true);
  // });

  //テキストチャットのテキストが増えたら自動スクロール




</script>
</body>
</html>
