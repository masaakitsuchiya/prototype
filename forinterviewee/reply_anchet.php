<?php
session_start();
include("../function/function.php");

$anchet_id = $_GET["anchet_id"];
// $form_id = $_GET["form_id"];

$pdo = db_con();
$stmt_anchet = $pdo->prepare("SELECT * from anchet WHERE anchet_id = :anchet_id");
$stmt_anchet ->bindValue(':anchet_id', $anchet_id, PDO::PARAM_INT);
$status_anchet = $stmt_anchet ->execute();
if($status_anchet==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_anchet->errorInfo();
  exit("ErrorQuery_anchet:".$error[2]);
}else{
$res_anchet = $stmt_anchet->fetch();
}

// var_dump($res_anchet["form_id"]);
$form_id = $res_anchet["form_id"];

$stmt = $pdo->prepare("SELECT * from form INNER JOIN form_item ON form.form_id = form_item.form_id WHERE form.form_id = :form_id");
$stmt->bindValue(':form_id', $form_id, PDO::PARAM_INT);
$status_form_item = $stmt->execute();

$form_item_view = "";
if($status_form_item==false){
 //execute（SQL実行時にエラーがある場合）
 $error = $stmt->errorInfo();
 exit("ErrorQuery:".$error[2]);
}else{
 //Selectデータの数だけ自動でループしてくれる
 while($result_form_item = $stmt->fetch(PDO::FETCH_ASSOC)){
 $form_element_id = "form_".h($result_form_item["form_order"]);//from_ + 数値　でidを作成。
 $form_item_view .= '<div class="form-group" id="'.h($form_element_id).'">';
//  $form_item_view .= '<label class="control-label" for="answer['.$form_element_id.']">'.$result_form_item["question"].'</label>';
//  $form_item_view .= '<input type="hidden" name="question['.$form_element_id.']">';

 if($result_form_item["form_type"] == "textarea"){//textareaの場合
   $form_item_view .= '<label class="control-label" for="answer['.h($form_element_id).']" style="font-weight:normal; margin-bottom:20px;"><span class="glyphicon  glyphicon-question-sign"></span>　'.h($result_form_item["question"]).'</label>';
   $form_item_view .= '<input type="hidden" name="question['.h($form_element_id).']" value="'.h($result_form_item["question"]).'">';
   $form_item_view .= '<textarea class="form-control" name="answer['.h($form_element_id).'][]"></textarea>';

 }elseif($result_form_item["form_type"] == "radio"){//radio-boxの場合
    $form_item_view .= '<p class="question_text text-left" for="answer['.h($form_element_id).']"><span class="glyphicon  glyphicon-question-sign"></span>　'.h($result_form_item["question"]).'</p>';
    $form_item_view .= '<input type="hidden" name="question['.h($form_element_id).']" value="'.h($result_form_item["question"]).'">';
    $stmt_select_item = $pdo->prepare("SELECT * FROM form_item INNER JOIN select_item ON form_item.form_item_id = select_item.form_item_id WHERE form_item.form_id = :form_id AND form_item.form_item_id = :form_item_id");
    $stmt_select_item->bindValue(':form_id', $form_id, PDO::PARAM_INT);
    $stmt_select_item->bindValue(':form_item_id', $result_form_item["form_item_id"], PDO::PARAM_INT);
    $statu_select_item = $stmt_select_item->execute();
      if($statu_select_item==false){
        //execute（SQL実行時にエラーがある場合）
        $error = $stmt_select_item->errorInfo();
        exit("ErrorQuery_select_item:".$error[2]);
      }else{
        //Selectデータの数だけ自動でループしてくれる
        while($result_select_item = $stmt_select_item->fetch(PDO::FETCH_ASSOC)){
          $form_item_view .= '<div class="radio-inline">';
          $form_item_view .= '<label class="radio-inline"><input type="radio" name="answer['.h($form_element_id).'][]" value="'.h($result_select_item["select_item_label"]).'">'.h($result_select_item["select_item_label"]).'</label>';
          $form_item_view .= '</div>';
      }
 }

}elseif($result_form_item["form_type"] == "checkbox"){//checkboxの場合
 $form_item_view .= '<p class="question_text text-left" for="answer['.h($form_element_id).']"><span class="glyphicon  glyphicon-question-sign"></span>　'.h($result_form_item["question"]).'</p>';
 $form_item_view .= '<input type="hidden" name="question['.h($form_element_id).']" value="'.h($result_form_item["question"]).'">';
 $stmt_select_item = $pdo->prepare("SELECT * FROM form_item INNER JOIN select_item ON form_item.form_item_id = select_item.form_item_id WHERE form_item.form_id = :form_id AND form_item.form_item_id = :form_item_id");
 $stmt_select_item->bindValue(':form_id', $form_id, PDO::PARAM_INT);
 $stmt_select_item->bindValue(':form_item_id', $result_form_item["form_item_id"], PDO::PARAM_INT);
 $statu_select_item = $stmt_select_item->execute();
   if($statu_select_item==false){
     //execute（SQL実行時にエラーがある場合）
     $error = $stmt_select_item->errorInfo();
     exit("ErrorQuery_select_item:".$error[2]);
   }else{
     //Selectデータの数だけ自動でループしてくれる
     while($result_select_item = $stmt_select_item->fetch(PDO::FETCH_ASSOC)){
       $form_item_view .= '<div class="checkbox-inline">';
       $form_item_view .= '<label class="checkbox-inline"><input type="checkbox" name="answer['.h($form_element_id).'][]" value="'.h($result_select_item["select_item_label"]).'">'.h($result_select_item["select_item_label"]).'</label>';
       $form_item_view .= '</div>';
   }
 }

}
  $form_item_view .= '</div>';//form-contorl
}
}

$interviewee_id = $res_anchet["interviewee_id"];
$stmt_interviewee = $pdo->prepare("SELECT * from interviewee_info WHERE id = :interviewee_id");
$stmt_interviewee->bindValue(':interviewee_id', $interviewee_id, PDO::PARAM_INT);
$status_interviewee = $stmt_interviewee->execute();

if($status_interviewee==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interviewee->errorInfo();
  exit("ErrorQuery_interviewee:".$error[2]);
}else{
$res_interviewee = $stmt_interviewee->fetch();
}

$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head_for_interviewee.php") ?>
<style>
.question_text{
  /*font-weight:bold;*/
}
.mb-20{
  margin-bottom:20px;
}
.mb-40{
  margin-bottom:40px;
}
.form-group{
margin-bottom:40px;
}
</style>
</head>
<body>
<?php include("../template/nav_for_interviewee.php") ?>
<div class="container">
    <div class="row mb-20">
      <div class="col-sm-1"></div>
      <div class="col-sm-10">
        <h4 class="text-center mb-40"><?=h($res_interviewee["interviewee_name"]);?>様</h5>
        <p class="mb-20"> <?= $res_anchet["anchet_message"]?></p>
        <h5 class="text-center mb-20">返信期限：<span style="color:red;"><?= h($res_anchet["deadline"]);?></span></h5>
        <div class="row">
          <div class="col-sm-1"></div>
            <div class="col-sm-10">
            <h3 class="text-center mb-20">事前アンケート</h3>
            <form class="form-horizontal" method="post" action="reply_anchet_insert.php?anchet_id=<?=h($anchet_id);?>">
              <?= $form_item_view ?>
            <div class="text-center">
              <a data-toggle="modal" href="#myModal" class="btn btn-info">送信</a>
            </div>
            <?php
              $body_text="アンケートの回答内容を送信してもよろしいでしょうか。";
              $btn_text="送信";
              $_GET['body_text'] = $body_text;
              $_GET['btn_text'] = $btn_text;
              include("../template/submit_my_modal.php");
            ?>
            </div>
            </form>
            </div>
          <div class="col-sm-1"></div>
        </div>
      </div>
    <div class="col-sm-1"></div>
  </div>
</div>

<?php include("../template/footer_for_interviewee.html") ?>
</body>
</html>
