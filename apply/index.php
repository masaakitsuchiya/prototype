<?php
include("../function/function.php");
include("../function/setting.php");

if($JD_OPEN == 0){
  header("Location: https://google.co.jp");
  exit;
}

$pdo = db_con();


$stmt = $pdo->prepare("SELECT * FROM corp_apply");
$status = $stmt->execute();
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  queryError($stmt);
}else{
  $res = $stmt->fetch();
}

//job_post表示
$stmt = $pdo->prepare("SELECT * FROM job_post where life_flg = 0 ORDER BY indate DESC limit 5");
$status = $stmt->execute();

//３．データ表示
$view2="";
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    // var_dump($result);
    // $view .='<a href="../job_post/job_post_view.php?job_post_id='.$result["id"].'">';
    // $view .='<tr>';
    // $view .='<td><i class="fa fa-id-badge" aria-hidden="true"></i></td>';
    // $view .='<td><a href="../job_post/job_post_view.php?job_post_id='.$result["id"].'">'.$result["job_title"].'</a></td>';
    // $jd_text_of_head = substr($result["job_description"],0,100);
    // $view .= '<td>'.$jd_text_of_head.'</td>';
    // $view .= '<td>'.$result["indate"].'</td>';
    // $view .='</tr>';
    $view2 .= '<div class="panel panel-default">';
    $view2 .= '<div class="panel-heading" role="tab" id="heading_'.$result["id"].'">';
    $view2 .= '<h4 class="panel-title">';
    $view2 .= '<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_'.$result["id"].'" aria-expanded="false" aria-controls="collapse_'.$result["id"].'">';
    $view2 .= h($result["job_title"]);
    $view2 .= '</a>';
    $view2 .= '</h4>';
    $view2 .= '</div>';
    $view2 .= '<div id="collapse_'.$result["id"].'" class="panel-collapse" role="tabpanel" aria-labelledby="heading_'.$result["id"].'">';
    $view2 .= '<div class="panel-body">';
    $view2 .= h($result["job_description"]);
    $view2 .= '<div class="text-right"><a href="../job_post/job_post_view.php?job_post_id='.$result["id"].'" class="btn btn-sm btn-info" target="_blank">詳細</a></div>';
    $view2 .= '</div>';
    $view2 .= '</div>';
    $view2 .= '</div>';
  }
}
$corp_info = corp_info_array();

$html_title = h($corp_info["corp_name"])."採用情報";
?>
<!DOCTYPE html>
<html>
<head>
  <?php include("../template/head_for_interviewee.php") ?>
<style>
html,body{
   height: 100%;
 }

 .carousel{
    width:100%;  /*サイズ指定*/
    margin:auto;
 }
 .carousel img{
    width:100%;
 }
 #main{
   background-image: url("<?=h($res["main_photo"])?>");
        background-size: cover;
        background-position:center center;
        height:100%;
        color:#fff;
        padding-top:200px;
        margin-bottom:50px;
        margin-top:-20px;

}
#main h1{
  text-shadow: 3px 3px 3px #999;
}
#main p{
  text-shadow: 3px 3px 3px #999;
}
#list{
  margin-bottom:40px;
}

 </style>
 </head>
 <body>
<?php include("./template/apply_nav.php"); ?>
 <div class="container-fluid" id="main">
   <h1 class="text-center"><?=hd($res["main_title_text"]);?></h1>
     <div class="text-center">
       <?=hd($res["main_lead_text"]);?>
     </div>
 </div>
<h2 class="text-center text-info" id="list">現在募集中のポジション</h2>

 <div class="container-fluid">
   <div class="row">
     <div class="col-sm-2"></div>
     <div class="col-sm-8">
       <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
      <?=$view2?>
      </div>
    </div>
    <div class="col-sm-2"></div>
  </div>
</div>
<?php include("./template/apply_footer.php"); ?>
 </body>

</html>
