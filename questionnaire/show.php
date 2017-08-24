<?php
session_start();
include("../function/function.php");
login_check();

$form_id = $_GET["form_id"];

$pdo = db_con();
$stmt = $pdo->prepare("SELECT * FROM form INNER JOIN form_item ON form.form_id = form_item.form_id WHERE form.form_id = :form_id");
 $stmt->bindValue(':form_id', $form_id, PDO::PARAM_INT);
 $status = $stmt->execute();

 $form_item_view = "";
 if($status==false){
   //execute（SQL実行時にエラーがある場合）
   $error = $stmt->errorInfo();
   exit("ErrorQuery:".$error[2]);

 }else{
   //Selectデータの数だけ自動でループしてくれる
   while( $result_form_item = $stmt->fetch(PDO::FETCH_ASSOC)){
   $form_element_id = "form_".$result_form_item["form_order"];//from_ + 数値　でidを作成。
   $form_item_view .= '<div class="form-group" id="'.h($form_element_id).'">';
  //  $form_item_view .= '<label class="control-label" for="answer['.h($form_element_id).']">'.$result_form_item["question"].'</label>';
  //  $form_item_view .= '<input type="hidden" name="question['.h($form_element_id).']">';

   if($result_form_item["form_type"] == "textarea"){//textareaの場合
     $form_item_view .= '<label class="control-label" for="answer['.h($form_element_id).']">'.h($result_form_item["question"]).'</label>';
     $form_item_view .= '<input type="hidden" name="question['.h($form_element_id).']" value="'.h($result_form_item["question"]).'">';
     $form_item_view .= '<textarea class="form-control" name="answer['.h($form_element_id).'][]"></textarea>';

   }elseif($result_form_item["form_type"] == "radio"){//radio-boxの場合
      $form_item_view .= '<p class="question_text text-left" for="answer['.h($form_element_id).']">'.h($result_form_item["question"]).'</p>';
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
   $form_item_view .= '<p class="question_text text-left" for="answer['.h($form_element_id).']">'.$result_form_item["question"].'</p>';
   $form_item_view .= '<input type="hidden" name="question['.h($form_element_id).']" value="'.$result_form_item["question"].'">';
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
         $form_item_view .= '<label class="checkbox-inline"><input type="checkbox" name="answer['.h($form_element_id).'][]" value="'.$result_select_item["select_item_label"].'">'.$result_select_item["select_item_label"].'</label>';
         $form_item_view .= '</div>';
     }
   }

 }
    $form_item_view .= '</div>';//form-contorl
 }
 }
$stmt_title = $pdo->prepare("SELECT * FROM form WHERE form_id = :form_id");
$stmt_title->bindValue(':form_id', $form_id, PDO::PARAM_INT);
$status_title = $stmt_title->execute();

if($status_title==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_title->errorInfo();
  exit("ErrorQuery_title:".$error[2]);
}else{
  $res_title = $stmt_title->fetch();
}

$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
.question_text{
  font-weight:bold;
}
</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<div class="container">
  <h2 class="text-center"><?= h($res_title["form_name"]); ?></h2>
  <p class="text-center"><?= h($res_title["form_description"]); ?></p>

  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="text-right">
        <a class="btn btn-primary" href="detail.php?form_id=<?= h($form_id); ?>">編集</a>
      </div>
      <form class="form-horizontal" method="post" action="test_input_data_insert.php">
        <?= $form_item_view ?>
      <div class="text-center">
        <input type="submit" class="btn btn-info" value="送信" disabled>
      </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>

<?php include("../template/footer.html") ?>
</body>
</html>
