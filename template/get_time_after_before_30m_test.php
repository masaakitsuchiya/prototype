<!-- ターゲット日時の３０分前、３０分後を取得 -->


<?php
include("../function/function.php");
$t_time = "2017-12-12 20:15:30";
$result_time_lists = before_after_30minute($t_time);
// $target_time = strtotime("2017-12-12 20:15:30");//日時の文字列をタイムスタンプに変換
// $before_30 = date('Y-m-d H:i:s', strtotime('-30 minute', $target_time));
// $after_30 = date('Y-m-d H:i:s', strtotime('-30 minute', $target_time));

var_dump($result_time_lists);
?>
