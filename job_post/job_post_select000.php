<?php
session_start();
include("../function/function.php");
login_check();
//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成 該当の候補者情報の抽出
$stmt = $pdo->prepare("SELECT * FROM job_post");
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
    $view .= '<a class="btn btn-sm btn-info" href="job_post_view.php?job_post_id='.h($result["id"]).'">確認</a> <a class="btn btn-sm btn-primary" href="job_post_detail.php?job_post_id='.h($result["id"]).'">修正</a> <a class="btn btn-sm btn-danger" href="job_post_delete.php?job_post_id='.h($result["id"]).'">削除</a>';
    if($result["life_flg"]==1){
      $view .=' <button class="openClose btn btn-warning btn-sm open" id="'.h($result["id"]).'">休止中</button>';
    }else{
      $view .=' <button class="openClose btn-success btn btn-sm close" id="'.h($result["id"]).'">掲載中</button>';
    }
    $view .= '</td>';
    $view .= '</tr>';
    // $jq_i .= '$("#close_'.$result["id"].'").click(function(){';
    // $jq_i .=  '$.ajax({';
    // $jq_i .=     'type:"post",';
    // $jq_i .=     'url: "stop_start.php?job_post_id='.$result["id"].'",';
    // $jq_i .=     'data: {';
    // $jq_i .=       '"life_flg": 1 ';
    // $jq_i .=     '},';
    // $jq_i .=     'success: function(j_data){';
    // $jq_i .=       '$("#close_'.$result["id"].'").removeClass("btn-success").addClass("btn-warning").text("休止中").attr("id","open_'.$result["id"].'");';
    // $jq_i .=        'console.log(j_data);';
    // $jq_i .=     '}';
    // $jq_i .=   '});';
    // $jq_i .= '});';
    //
    // $jq_i .= '$("#open_'.$result["id"].'").click(function(){';
    // $jq_i .=  '$.ajax({';
    // $jq_i .=     'type:"post",';
    // $jq_i .=     'url: "stop_start.php?job_post_id='.$result["id"].'",';
    // $jq_i .=     'data: {';
    // $jq_i .=       '"life_flg": 0 ';
    // $jq_i .=     '},';
    // $jq_i .=     'success: function(j_data){';
    // $jq_i .=       '$("#open_'.$result["id"].'").removeClass("btn-warning").addClass("btn-success").text("掲載中").attr("id","close_'.$result["id"].'");';
    // $jq_i .=        'console.log(j_data);';
    // $jq_i .=     '}';
    // $jq_i .=   '});';
    // $jq_i .= '});';




  }
}







$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<script src="../ckeditor/ckeditor.js"></script>
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
  <div class="col-sm-offset-9 col-sm-2 text-center"><a class="btn btn-sm btn-default" href="job_post_input.php">新規登録</a></div>
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
