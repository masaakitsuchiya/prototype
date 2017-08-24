<?php
session_start();
include("../function/function.php");

login_check();

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT interviewee_info.id,interviewee_info.interviewee_name,interviewee_info.interviewee_name_kana,interviewee_info.indate,job_post.job_title FROM interviewee_info,job_post WHERE interviewee_info.job_post_id = job_post.id ORDER BY interviewee_info.indate DESC");
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view .='<tr>';
    $view .='<td><span class="glyphicon glyphicon-user"><span>';
    $view .='<td><ul class="list-unstyled">';
    $view .='<li class="">'.h($result["interviewee_name"]).'</li>';
    $view .='<li class="isc">'.h($result["interviewee_name_kana"]).'</li>';
    $view .='</ul></td>';
    $view .='<td><ul class="list-unstyled">';
    $view .='<li class="">'.h($result["job_title"]).'</li>';
    $view .='<li class="isc">自社サイト</li>';
    $view .='<li class="isc">中途</li>';
    $view .='<li class="isc">'.h($result["indate"]).'</li>';
    $view .='</ul></td>';
      //アンケート情報の検索
      $stmt_anchet = $pdo->prepare("SELECT * FROM anchet WHERE interviewee_id = :interviewee_id");
      $stmt_anchet->bindValue(':interviewee_id', $result["id"], PDO::PARAM_INT);
      $status_anchet = $stmt_anchet->execute();
      if($status_anchet==false){
        //execute（SQL実行時にエラーがある場合）
        $error = $stmt_anchet->errorInfo();
        exit("ErrorQuery_anchet:".$error[2]);
      }else{
        $res_anchet = $stmt_anchet->fetch();
      }
    $view .='<td><ul class="list-unstyled">';
    if(!$res_anchet["stage_flg"]){//アンケート未送信のとき
      $view .='<li class="isc"><a class="btn btn-xs btn-default" href="questionnaire_setting01.php?target_interviewee_id='.$result["id"].'">未送信</a></li>';
    }elseif($res_anchet["stage_flg"]==1){//返信まち
      $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="">回答待</a></li>';
      $view .='<li class="isc">送信:'.$res_anchet["send_date"].'</li>';
      $view .='<li class="isc">返信期限:'.$res_anchet["deadline"].'</li>';
      $view .='<li class="isc"><a class="btn btn-xs btn-default" href="../forinterviewee/reply_anchet.php?anchet_id='.$res_anchet["anchet_id"].'" target="_blank">（仮）候補者回答画面へ</a></li>';
    }elseif($res_anchet["stage_flg"]==2){//受信完了
      $view .='<li class="isc"><a class="btn btn-xs btn-success" href="questionnaire_show.php?anchet_id='.$res_anchet["anchet_id"].'">回答済</a></li>';
      $view .='<li class="isc">回答:'.$res_anchet["recieved_date"].'</li>';
    }
    $view .='</ul></td>';//アンケート終了
    // $view .='<td><ul class="list-unstyled">';//書類選考
    // $view .='<li class="isc">2017-01-12</li>';
    // $view .='<li class="isc">2017-01-20</li>';
    // $view .='<li class="isc">通過</li>';
    // $view .='</ul></td>';
      $stmt2 = $pdo->prepare("SELECT * FROM interview WHERE interviewee_id = :interviewee_id AND interview_type = :interview_type");
      $stmt2->bindValue(':interviewee_id', $result["id"], PDO::PARAM_INT);
      $stmt2->bindValue(':interview_type', 1, PDO::PARAM_INT);
      $status2 = $stmt2->execute();
      if($status==false){
        //execute（SQL実行時にエラーがある場合）
        $error2 = $stmt2->errorInfo();
        exit("ErrorQuery:".$error2[2]);
      }else{
        $res = $stmt2->fetch();
      }
    $view .='<td><ul class="list-unstyled">';//1次面接
    if(!$res["stage_flg"]||$res["stage_flg"] ==0){
    $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="interview01_setting.php?interview_type_num=1&target_interviewee_id='.h($result["id"]).'">未設定（日程）</a></li>';//日程調整
    }elseif($res["stage_flg"] ==1){
      $view .='<li class="isc"><a class="btn btn-xs btn-default" href="#">日程候補送信済</a></li>';//日程調整
      $view .='<li class="isc"><a class="btn btn-xs btn-default" href="../forinterviewee/interview_date_time_select01.php?interview_id='.$res["id"].'" target="_blank">仮）候補者確認画面</a></li>';//日程調整

    }elseif($res["stage_flg"] ==2){
      $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="interview_confirm01.php?interview_id='.$res["id"].'">要日程確定</a></li>';//日程調整
    }elseif($res["stage_flg"] ==3){
      $view .='<li class="isc"><a class="btn btn-xs btn-success" href="interview_confirm01.php?interview_id='.h($res["id"]).'&stage_flg=3">日程確定</a></li>';
      $view .='<li class="isc">'.$res["interview_date_time"].'</li>';
      $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="../result/output_data.php?interview_id='.h($res["id"]).'">結果入力</a></li>';
    }elseif($res["stage_flg"] ==4){
          $view .='<li class="isc">面接:'.$res["interview_date_time"].'</li>';//日程調整
          $view .='<li class="isc">通過:'.$res["fix_time"].'</li>';//日程調整
          $view .='<li class="isc"><a class="btn btn-xs btn-default" href="../result/output_data.php?interview_id='.h($res["id"]).'">結果変更</a></li>';//日程調整
    }elseif($res["stage_flg"] ==5){
          $view .='<li class="isc">'.$res["interview_date_time"].'</li>';//日程調整
          $view .='<li class="isc">不合格:'.$res["fix_time"].'</li>';//日程調整
          $view .='<li class="isc"><a class="btn btn-xs btn-default" href="../result/output_data.php?interview_id='.h($res["id"]).'">結果変更</a></li>';//日程調整
    }elseif($res["stage_flg"] ==6){
      $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="interview_resetting.php?interview_id='.h($res["id"]).'">日程再調整</a></li>';//通過
    }
    $view .='</ul></td>';
    // $view .='</ul></td>';
    // $view .='<td><ul class="list-unstyled">';//2次面接
    //  if($res["stage_flg"] ==4){
    // $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="interview01_setting.php?interview_type_num=2&target_interviewee_id='.h($result["id"]).'">未設定（日程）</a></li>';//日程調整
    // $view .='<li class="isc">-（結果）</li>';
    // $view .='<li class="isc">-（通過/不可　日付)</li>';
    // $view .='</ul></td>';
    // $view .='</ul></td>';
    // }
    $view .='<td><ul class="list-unstyled">';//3次面接
    // $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="interview01_setting.php?interview_type_num=3&target_interviewee_id='.h($result["id"]).'">未設定（日程）</a></li>';//日程調整
    // $view .='<li class="isc">-（結果）</li>';
    // $view .='<li class="isc">-（通過/不可　日付)</li>';
    $view .='</ul></td>';
    $view .='<td><ul class="list-unstyled">';//オファー
    // $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="interview_detail_select.php?target_interviewee_id='.h($result["id"]).'">未設定（日程）</a></li>';//日程調整
    // $view .='<li class="isc">-（結果）</li>';
    // $view .='<li class="isc">-（通過/不可　日付)</li>';
    $view .='</ul></td>';


    // $view .='<td><a href="input_data.php?target_inteviewee='.h($result["id"]).'" class="btn btn-xs btn-info">評価入力</a>&nbsp;<a href="output_data.php?target_inteviewee='.h($result["id"]).'" class="btn btn-xs btn-primary">評価閲覧</a></td>';
    // $view .='<td><a href="interview_detail_select.php?target_interviewee_id='.h($result["id"]).'" class="btn btn-xs btn-info">選考設定</a>&nbsp;<a href="interviewee_detail.php?target_interviewee_id='.h($result["id"]).'&target_interviewee_name='.h($result["interviewee_name"]).'" class="btn btn-xs btn-primary">情報更新</a>&nbsp;<a href="interviewee_delete.php?target_interviewee_id='.h($result["id"]).'&target_interviewee_name='.h($result["interviewee_name"]).'" class="btn btn-xs btn-danger">削除</a></td>';
    $view .='<td class="text-center"><p><a href="interviewee_detail.php?target_interviewee_id='.h($result["id"]).'&target_interviewee_name='.h($result["interviewee_name"]).'" class="btn btn-xs btn-primary">情報更新</a></p>';
    // $view .='<p><a href="interviewee_delete.php?target_interviewee_id='.h($result["id"]).'&target_interviewee_name='.h($result["interviewee_name"]).'" class="btn btn-xs btn-danger">削除</a></p></td>';
    $view .='<a data-toggle="modal" href="#myModal_cancel_'.h($result["id"]).'" class="btn btn-danger btn-xs">削除</a>';
    // <!-- キャンセルモーダル -->
    $view .='<div class="modal fade" id="myModal_cancel_'.h($result["id"]).'">';
    $view .='<div class="modal-dialog">';
    $view .='<div class="modal-content">';
    $view .='<div class="modal-header">';
    $view .='<button class="close" data-dismiss="modal">&times;</button>';
    $view .='<h4 class="modal-title">削除確認</h4>';
    $view .='</div>';
    $view .='<div class="modal-body">本当に削除してよろしいでしょうか。削除すると元に戻せません。</div>';
    $view .='<div class="modal-footer">';
    $view .='<a class="btn btn-danger" href="interviewee_delete.php?target_interviewee_id='.h($result["id"]).'&target_interviewee_name='.h($result["interviewee_name"]).'">候補者情報を削除</a>';
    $view .='</div>';
    $view .='</div>';
    $view .='</div>';
    $view .='</div>';
    $view .='</tr>';
  }
}
$html_title = '無料から使えるクラウド採用管理、面接システム Smart Interview';
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>

html,body{
  height: 100%;
}
.container{
  margin-bottom:20px;
}
li.isc{
  font-size:0.7em;

}
span.isc{
    font-size:0.7em;
}


</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">候補者一覧</h3>
<div class="container">
<div class="row">
  <div class="col-sm-offset-9 col-sm-2 text-center"><a class="btn btn-sm btn-default" href="interviewee_setting.php">新規登録</a></div>
  </div>
</div>




<div class="container-fruid">
  <table class="table table-hover table-bordered">
    <tr>
      <th></th>
      <th>名前 カナ</th>
      <th>ポジション</th>
      <th>アンケートフォーム</th>
      <!-- <th>書類選考</th> -->
      <th>一次面接</th>
      <th>二次面接</th>
      <th>最終面接</th>
      <th>オファー</th>
      <th></th>
    </tr>
    <?=$view?>
  </table>
</div>
<?php include("../template/footer.html") ?>
<!-- <script>
  $(function(){
      $('#to_output_data').click(function() {
          $('#form').attr('action', 'output_data.php');
          $('#form').submit();
      });
      $('#to_input_data').click(function() {
          $('#form').attr('action', 'input_data.php');
          $('#form').submit();
      });
  }); -->
</script>
</body>
</html>
