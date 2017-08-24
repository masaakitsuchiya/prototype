<?php
session_start();
include("../function/function.php");
login_check();
//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成 該当の候補者情報の抽出
$stmt = $pdo->prepare("SELECT * FROM job_post WHERE delete_flg = :delete_flg");
$stmt->bindValue(':delete_flg',0,PDO::PARAM_INT);//0 = active
$status = $stmt->execute();

$view = "";
$jq_i = "";
//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view .= '<tr>';
    $view .= '<td><span class="glyphicon glyphicon-duplicate"></span></td>';
    $view .= '<td>'.h($result["job_title"]).'</td>';
    $jd_text_of_head = mb_substr($result["job_description"],0,50);
    $view .= '<td>'.$jd_text_of_head.'</td>';
    $view .= '<td>';
    $view .= '<div class="btn-group" role="group">';
    $view .= '<a type="button" class="btn btn-xs btn-info" href="job_post_view.php?job_post_id='.h($result["id"]).'">確認</a> <a type="button" class="btn btn-xs btn-primary" href="job_post_detail.php?job_post_id='.h($result["id"]).'">修正</a>';
    // $view .= '<a type="button" class="btn btn-xs btn-danger" href="job_post_delete.php?job_post_id='.h($result["id"]).'">削除</a>';
    $view .= '<a type="button" data-toggle="modal" href="#myModal_delete_'.h($result["id"]).'" class="btn btn-danger btn-xs">削除</a>';

    $view .='<div class="modal fade" id="myModal_delete_'.h($result["id"]).'">';
    $view .='<div class="modal-dialog">';
    $view .='<div class="modal-content">';
    $view .='<div class="modal-header">';
    $view .='<button class="close" data-dismiss="modal">&times;</button>';
    $view .='<h4 class="modal-title">削除確認</h4>';
    $view .='</div>';
    $view .='<div class="modal-body">削除すると元に戻せません。この求人を削除してよいですか。</div>';
    $view .='<div class="modal-footer">';
    $view .='<a class="btn btn-danger" href="job_post_delete.php?job_post_id='.h($result["id"]).'">削除</a>';
    $view .='</div>';
    $view .='</div>';
    $view .='</div>';
    $view .='</div>';

    if($result["life_flg"]==1){
      // $view .=' <a class="openClose btn btn-warning btn-xs open" id="'.h($result["id"]).'">休止中</a>';
      $view .='<a type="button" data-toggle="modal" href="#myModal_open_'.h($result["id"]).'" class="btn btn-warning btn-xs">休止中</a>';
      $view .='</div>';
      $view .='<div class="modal fade" id="myModal_open_'.h($result["id"]).'">';
      $view .='<div class="modal-dialog">';
      $view .='<div class="modal-content">';
      $view .='<div class="modal-header">';
      $view .='<button class="close" data-dismiss="modal">&times;</button>';
      $view .='<h4 class="modal-title">公開確認</h4>';
      $view .='</div>';
      $view .='<div class="modal-body">この求人を公開してよいですか</div>';
      $view .='<div class="modal-footer">';
      $view .='<a class="btn btn-success" href="stop_start0.php?job_post_id='.h($result["id"]).'&life_flg=0">公開</a>';
      $view .='</div>';
      $view .='</div>';
      $view .='</div>';
      $view .='</div>';
    }else{
      // $view .=' <a class="openClose btn btn-success btn-xs close" id="'.h($result["id"]).'">公開中</a>';
      $view .='<a type="button" data-toggle="modal" href="#myModal_close_'.h($result["id"]).'" class="btn btn-success btn-xs">公開中</a>';
      $view .='</div>';
      $view .='<div class="modal fade" id="myModal_close_'.h($result["id"]).'">';
      $view .='<div class="modal-dialog">';
      $view .='<div class="modal-content">';
      $view .='<div class="modal-header">';
      $view .='<button class="close" data-dismiss="modal">&times;</button>';
      $view .='<h4 class="modal-title">休止確認</h4>';
      $view .='</div>';
      $view .='<div class="modal-body">この求人を休止してよいですか</div>';
      $view .='<div class="modal-footer">';
      $view .='<a class="btn btn-warning" href="stop_start0.php?job_post_id='.h($result["id"]).'&life_flg=1">休止</a>';
      $view .='</div>';
      $view .='</div>';
      $view .='</div>';
      $view .='</div>';
    }
    $view .= '</td>';
    $view .= '</tr>';
  }
}






$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>

.container{
  margin-bottom:20px;
}


</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">選考設定</h3>
<div class="container">
<div class="row">
  <div class="col-sm-offset-9 col-sm-2 text-center"><a class="btn btn-xs btn-default" href="job_post_input.php">新規登録</a></div>
  </div>
</div>
<div class="container">
  <table class="table table-hover">
    <tr><th></th><th>jobtitle</th><th>summary</th><th>処理</th><tr>
    <?=$view?>
  </table>
</div>
<?php include("../template/footer.html") ?>
</body>
<script>
$(function(){
  $('.openClose').on('click',function(){
    var target_id = this.id;
    console.log(target_id);
    if($(this).hasClass('close')){
      $(this).removeClass('btn-success close').addClass('btn-warning open').text('').text('休止中');
      $.ajax({
        type:"post",
        url: "stop_start.php?job_post_id=".target_id,
        data: {
          "life_flg":"1"
        },
        success: function(j_data){
          console.log("closing");
          }
        });

    }else if($(this).hasClass('open')){
        $(this).removeClass('btn-warning open').addClass('btn-success close').text('').text('掲載中');
        $.ajax({
          type:"post",
          url: "stop_start.php?job_post_id=".target_id,
          data: {
            "life_flg":"0"
          },
          success: function(j_data){
            console.log("openning");
          }
        });

      }
    });
  });
</script>
</html>
