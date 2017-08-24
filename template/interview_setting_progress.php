<?php
$progress = $_GET["progress"];
?>
<div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <div class="row">
      <div class="col-sm-4 text-center<?php if($progress != 1){echo ' hidden-xs';} ?>">
        <span id="interviewer_select" <?php if($progress == 1){echo 'style="text-decoration:underline;font-weight:bold;"';} ?>>1,面接担当者選択</span>
      </div>
      <div class="col-sm-4 text-center<?php if($progress != 2){echo ' hidden-xs';} ?>">
      <span id="date_select" <?php if($progress == 2){echo 'style="text-decoration:underline;font-weight:bold;"';} ?>>2,日時候補選択</span>
      </div>
      <div class="col-sm-4 text-center<?php if($progress != 3){echo ' hidden-xs';} ?>">
      <span id="confirm" <?php if($progress == 3){echo 'style="text-decoration:underline;font-weight:bold;"';} ?>>3,送信内容確認</span>
      </div>
    </div>
  </div>
  <div class="col-sm-1"></div>
</div>
