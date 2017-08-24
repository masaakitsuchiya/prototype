<?php
$btn_text = $_GET["btn_text"];
$body_text = $_GET["body_text"];
?>

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">送信確認</h4>
        </div>
        <div class="modal-body">
          <!-- 候補者に送信してよろしいでしょうか？
          送信ボタンを押すと候補者にメールが送信されます。 -->
          <?php echo (hd($body_text));?>
        </div>
        <div class="modal-footer">
          <input class="btn btn-info" type="submit" value="<?php echo(h($btn_text));?>">
        </div>
      </div>
    </div>
  </div>
