<?php
$html_title = '無料から使えるクラウド採用管理、面接システム Smart Interview';
?>
<?php include("../template/head.php") ?>
<script src="https://skyway.io/dist/0.3/peer.min.js"></script>
<script src="https://skyway.io/dist/multiparty.min.js"></script>
<style>
  #mobile_text_chat_area{
    height:500px;
    position:absolute;
    top:'0';
    left:'0';
    z-index: '1';
  }
  .back{
    position:relative;
  }

  </style>
</head>
<body>
  <div class="text-right">
  <button class="btn btn-default visible-xs" id="mobile_text_chat_button"><i class="glyphicon glyphicon-comment"></i></button>
  </div>
  <div class="container">
    <div class="row control-panel">
      <div class="col-xs-1"></div>
      <div class="col-xs-10 text-center">
          <div class="btn-group" role="group">
            <button class="btn btn-primary" type="button" name="start" id="start"><i class="glyphicon glyphicon-facetime-video"></i> 面接を開始</button>
            <button class="btn btn-danger" type="button" name="exit" id="exit" disabled ><i class="glyphicon glyphicon-remove-circle"></i> 退出</button>
            <button class="btn btn-warning" type="button" id="video-mute" data-muted="false" disabled><i class="glyphicon glyphicon-facetime-video"></i><i class="glyphicon glyphicon-remove" style="font-size:0.3em;"></i></button>
            <button class="btn btn-success" type="button" id="audio-mute" data-muted="false" disabled><i class="glyphicon glyphicon-volume-off"></i></button>
          </div>
      </div>
      <div class="col-xs-1"></div>
   </div>
  </div>
  <!-- xs用テキストチャット -->
<div id="mobile_text_chat_area" style="display:none;">
  <p id="receive_mobile" class="receive">
    test
    test
    test
    test
    test
    test
    test
    test
    test
    test
    test
    test
    test
    test
    test
    test
    test
  </p>
  <div id="message_xs">
    <form class="form-inline">
      <div class="form-group">
        <input type="text" class="form-control">
      </div>
      <div class="text-right">
        <button class="btn btn-default" type="submit">send</button>
      </div>
    </form>
  </div>
</div>
<div class="back">
  155500
  155500
  155500
  155500
  155500
  155500
  155500
  155500
  155500
  155500
  155500  155500
  155500
  155500
  155500
  155500

  155500
  155500
  155500
  155500
  155500
  155500
  155500
  155500

</div>
<script>
$("#mobile_text_chat_button").click(function(){
  $('#mobile_text_chat_area').toggle();
});
</script>
</body>
</html>
