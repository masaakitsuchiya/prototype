<?php
//つかってない
session_start();
include("../function/function.php");
login_check();
//1.  DB接続します
$form_id = $_GET["form_id"];

$pdo = db_con();
$stmt_form_title = $pdo->prepare("SELECT * FROM form WHERE form_id = :form_id");
$stmt_form_title->bindValue(':form_id', $form_id, PDO::PARAM_INT);
$status_form_title= $stmt_form_title->execute();

if($status_form_title==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_form_title->errorInfo();
  exit("ErrorQuery_form_title:".$error[2]);
}else{
  $res_form_title = $stmt_form_title->fetch();
}


//フォームの中身を出力
$stmt_form = $pdo->prepare("SELECT * FROM form_item WHERE form_id = :form_id ORDER BY form_order ASC");
$stmt_form->bindValue(':form_id', $form_id, PDO::PARAM_INT);
$status_form = $stmt_form->execute();

$form_item_view = "";
$form_order = array();

if($status_form==false){
 //execute（SQL実行時にエラーがある場合）
 $error = $stmt_form->errorInfo();
 exit("ErrorQuery_form:".$error[2]);

}else{
 //Selectデータの数だけ自動でループしてくれる
 while($result_form_item = $stmt_form->fetch(PDO::FETCH_ASSOC)){
    $form_order[] = $result_form_item["form_order"];
   if($result_form_item["form_type"] == "textarea"){
    $form_id_element = 'form_'.$result_form_item["form_order"];
    $form_item_view .= '<div class="row">';
    $form_item_view .= '<div class="col-xs-11">';
    $form_item_view .= '<div class="form-group" id="'.$form_id_element.'">';
    $form_item_view .= '<label class="control-label" for="questions['.$form_id_element.']">質問:</label>';
    $form_item_view .= '<textarea class="form-control" name="questions['.$form_id_element.']" placeholder="質問文を入力してください。　　例）なぜ弊社の求人に興味をもっていただいたのでしょうか">'.$result_form_item["question"].'</textarea>';
    $form_item_view .= '<label class="control-label" for="answer['.$form_id_element.'][]">回答欄:</label>';
    $form_item_view .= '<textarea class="form-control" name="answer['.$form_id_element.'][]" disabled></textarea>';
    $form_item_view .= '<input type=hidden name="form_types['.$form_id_element.']" value="textarea">';
    $form_item_view .= '</div>';//form-group
    $form_item_view .= '</div>';//col-xs-11
    $form_item_view .= '<div class="col-xs-1 text-right">';
    $form_item_view .= '<span class="remove"><i class="glyphicon glyphicon-trash"></i></span>';
    $form_item_view .= '</div>';//col-xs-1
    $form_item_view .= '</div>';//row
  }else if($result_form_item["form_type"] == "checkbox"){
    $form_order[] = $result_form_item["form_order"];
    $form_id_element = 'form_'.$result_form_item["form_order"];
    $form_item_view .= '<div class="row">';
    $form_item_view .= '<div class="col-xs-11">';
    $form_item_view .= '<div class="form-group" id="'.$form_id_element.'">';
    $form_item_view .= '<label class="control-label" for="questions['.$form_id_element.']">質問:</label>';
    $form_item_view .= '<textarea class="form-control" name="questions['.$form_id_element.']" placeholder="質問文を入力してください。　　例）あなたの得意な科目はなんですか？複数選択可">'.$result_form_item["question"].'</textarea>';
    $form_item_view .= '<div class="text-right">';
    $form_item_view .= '<span class="add_checkbox_item"><i class="glyphicon glyphicon-plus"></i></span>';
    $form_item_view .= '</div>';//text-right
    $form_item_view .= '<div class="checkbox_area">';
      //選択肢抽出出力
      $form_item_id = $result_form_item["form_item_id"];
      $stmt_select_item = $pdo->prepare("SELECT * FROM select_item WHERE form_item_id = :form_item_id");
      $stmt_select_item->bindValue(':form_item_id', $form_item_id, PDO::PARAM_INT);
      $status_select_item = $stmt_select_item->execute();

      if($status_select_item==false){
         //execute（SQL実行時にエラーがある場合）
         $error = $stmt_select_item ->errorInfo();
         exit("ErrorQuery_select_item:".$error[2]);
       }else{
         //Selectデータの数だけ自動でループしてくれる
         while( $result_select_item = $stmt_select_item->fetch(PDO::FETCH_ASSOC)){
           if($result_select_item["select_item_label"]){
             $form_item_view .= '<label class="checkbox-inline"><input type="checkbox" name="answer['.$form_id_element.'][]" disabled><input type="text" name="select_items['.$form_id_element.'][]" value="'.$result_select_item["select_item_label"].'"> <span class="remove_item"><i class="glyphicon glyphicon-remove-circle"></i></span></label>';
           }
         }
       }
    $form_item_view .= '</div>';//checkbox_area
    $form_item_view .= '<input type=hidden name="form_types['.$form_id_element.']" value="checkbox">';
    $form_item_view .= '</div>';//form-group
    $form_item_view .= '</div>';//col-xs-11
    $form_item_view .= '<div class="col-xs-1 text-right">';
    $form_item_view .= '<span class="remove"><i class="glyphicon glyphicon-trash"></i></span>';
    $form_item_view .= '</div>';//col-xs-1
    $form_item_view .= '</div>';//row
  }else if($result_form_item["form_type"] == "radio"){
    $form_order[] = $result_form_item["form_order"];
    $form_id_element = 'form_'.$result_form_item["form_order"];
    $form_item_view .= '<div class="row">';
    $form_item_view .= '<div class="col-xs-11">';
    $form_item_view .= '<div class="form-group" id="'.$form_id_element.'">';
    $form_item_view .= '<label class="control-label" for="questions['.$form_id_element.']">質問:</label>';
    $form_item_view .= '<textarea class="form-control" name="questions['.$form_id_element.']" placeholder="質問文を入力してください。　　例）あなたの血液型はなんですか？">'.$result_form_item["question"].'</textarea>';
    $form_item_view .= '<div class="text-right">';
    $form_item_view .= '<span class="add_radio_item"><i class="glyphicon glyphicon-plus"></i></span>';
    $form_item_view .= '</div>';//text-right
    $form_item_view .= '<div class="radio_area">';
      $form_item_id = $result_form_item["form_item_id"];//radio のアイテムを出力
      $stmt_select_item = $pdo->prepare("SELECT * FROM select_item WHERE form_item_id = :form_item_id");
      $stmt_select_item->bindValue(':form_item_id', $form_item_id, PDO::PARAM_INT);
      $status_select_item = $stmt_select_item->execute();
      if($status_select_item==false){//radio のアイテムを出力
         //execute（SQL実行時にエラーがある場合）
         $error = $stmt_select_item ->errorInfo();
         exit("ErrorQuery_select_item:".$error[2]);
       }else{
         //Selectデータの数だけ自動でループしてくれる
         while( $result_select_item = $stmt_select_item->fetch(PDO::FETCH_ASSOC)){
           if($result_select_item["select_item_label"]){
             $form_item_view .= '<label class="radio-inline"><input type="radio" name="answer['.$form_id_element.'][]" disabled><input type="text" name="select_items['.$form_id_element.'][]" value="'.$result_select_item["select_item_label"].'"> <span class="remove_item"><i class="glyphicon glyphicon-remove-circle"></i></span></label>';
           }
         }
       }
    $form_item_view .= '</div>';//radio_area
    $form_item_view .= '<input type=hidden name="form_types['.$form_id_element.']" value="radio">';
    $form_item_view .= '</div>';//form-group
    $form_item_view .= '</div>';//col-xs-11
    $form_item_view .= '<div class="col-xs-1 text-right">';
    $form_item_view .= '<span class="remove"><i class="glyphicon glyphicon-trash"></i></span>';
    $form_item_view .= '</div>';//col-xs-1
    $form_item_view .= '</div>';//row
}


 }
  $id_add_start_num = max($form_order) + 1;
}

$html_title = '無料から使えるクラウド採用管理、面接システム Smart Interview';
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
.remove{
  font-size:1.2em;
}
.remove:hover {
    cursor:pointer;

}
.add_radio_item:hover{
  cursor:pointer;
}
.add_checkbox_item:hover{
  cursor:pointer;
}
.form-group,.btn-group{
margin-bottom:60px;

}
</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">アンケートフォーム修正</h3>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10 text-center">フォームを修正します。</div>
    <div class="col-sm-1"></div>
  </div>
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form-horizontal" method="post" action="update.php?form_id=<?= $form_id ?>">
        <div id="myform">
              <div class="row">
                <div class="col-xs-11">
                  <div class="form-group">
                    <label class="control-label" for="form_name">フォーム名</label>
                    <input class="form-control" type="text" name="form_name" value="<?=$res_form_title["form_name"]?>">
                  </div>
                  <div class="form-group">
                    <label class="control-label" for="form_description">フォーム説明文</label>
                    <textarea class="form-control" name="form_description" placeholder="フォームの説明を入力"><?=$res_form_title["form_description"]?></textarea>
                  </div>
                </div>
                <div class="col-xs-1"></div>
              </div>
              <div class="btn-group text-center">
                <button id="add_textarea" class="btn btn-success btn-lg"><i class="glyphicon glyphicon-plus-sign"></i> テキスト</button>
                <button id="add_checkbox" class="btn btn-success btn-lg"><i class="glyphicon glyphicon-plus-sign"></i> チェックボックス</button>
                <button id="add_radio" class="btn btn-success btn-lg"><i class="glyphicon glyphicon-plus-sign"></i> ラジオボタン</button>
              </div>
          <?= $form_item_view ?>
        <div class="form-group text-center">
          <input type="submit" class="btn btn-info" value="更新">
        </div>
      </div>
    </form>
  </div>
  <div class="col-sm-1"></div>
  </div>
</div>
<?php include("../template/footer.html") ?>
</body>
<script>
$(function(){
  var id_num = <?php echo $id_add_start_num; ?>;
  console.log(id_num);
  //text_area 追加
  $('#add_textarea').click(function(){
     var form_id = 'form_' + id_num;
     var textarea_original = '';
     textarea_original += '<div class="row">';
     textarea_original += '<div class="col-xs-11">';
     textarea_original += '<div class="form-group" id="' + form_id + '">';
     textarea_original += '<label class="control-label" for="questions[' + form_id + ']">質問:</label>';
     textarea_original += '<textarea class="form-control" name="questions[' + form_id + ']" placeholder="質問文を入力してください。　　例）なぜ弊社の求人に興味をもっていただいたのでしょうか"></textarea>';
     textarea_original += '<label class="control-label" for="answer[' + form_id + '][]">回答欄:</label>';
     textarea_original += '<textarea class="form-control" name="answer['+ form_id + '][]" disabled></textarea>';
     textarea_original += '<input type=hidden name="form_types[' + form_id + ']" value="textarea">';
     textarea_original += '</div>';//form-group
     textarea_original += '</div>';//col-xs-11
     textarea_original += '<div class="col-xs-1 text-right">';
     textarea_original += '<span class="remove"><i class="glyphicon glyphicon-trash"></i></span>';
     textarea_original += '</div>';//col-xs-1
     textarea_original += '</div>';//row

     console.log(textarea_original);
     $('#myform').append(textarea_original);

     id_num++;
  });
  //checkboxエリア追加
  $('#add_checkbox').click(function(){
     var form_id = 'form_' + id_num;
     var checkbox_original = '';
     checkbox_original += '<div class="row">';
     checkbox_original += '<div class="col-xs-11">';
     checkbox_original += '<div class="form-group" id="' + form_id + '">';
     checkbox_original += '<label class="control-label" for="questions[' + form_id + ']">質問:</label>';
     checkbox_original += '<textarea class="form-control" name="questions[' + form_id + ']" placeholder="質問文を入力してください。　　例）あなたの得意な科目はなんですか？複数選択可"></textarea>';
     checkbox_original += '<div class="text-right">';
     checkbox_original += '<span class="add_checkbox_item"><i class="glyphicon glyphicon-plus"></i></span>';
     checkbox_original += '</div>';//text-right
     checkbox_original += '<div class="checkbox_area">';
     checkbox_original += '<label class="checkbox-inline"><input type="checkbox" name="answer['+ form_id + '][]" disabled><input type="text" name="select_items[' + form_id + '][]"> <span class="remove_item"><i class="glyphicon glyphicon-remove-circle"></i></span></label>';
     checkbox_original += '<label class="checkbox-inline"><input type="checkbox" name="answer['+ form_id + '][]" disabled><input type="text" name="select_items[' + form_id + '][]"> <span class="remove_item"><i class="glyphicon glyphicon-remove-circle"></i></span></label>';
     checkbox_original += '<label class="checkbox-inline"><input type="checkbox" name="answer['+ form_id + '][]" disabled><input type="text" name="select_items[' + form_id + '][]"> <span class="remove_item"><i class="glyphicon glyphicon-remove-circle"></i></span></label>';
     checkbox_original += '</div>';//checkbox_area
     checkbox_original += '<input type=hidden name="form_types[' + form_id + ']" value="checkbox">';
     checkbox_original += '</div>';//form-group
     checkbox_original += '</div>';//col-xs-11
     checkbox_original += '<div class="col-xs-1 text-right">';
     checkbox_original += '<span class="remove"><i class="glyphicon glyphicon-trash"></i></span>';
     checkbox_original += '</div>';//col-xs-1
     checkbox_original += '</div>';//row

     console.log(checkbox_original);
     $('#myform').append(checkbox_original);
     id_num++;
  });

  $('#add_radio').click(function(){
     var form_id = 'form_' + id_num;
     var radio_original = '';
     radio_original += '<div class="row">';
     radio_original += '<div class="col-xs-11">';
     radio_original += '<div class="form-group" id="' + form_id + '">';
     radio_original += '<label class="control-label" for="questions[' + form_id + ']">質問:</label>';
     radio_original += '<textarea class="form-control" name="questions[' + form_id + ']" placeholder="質問文を入力してください。　　例）あなたの血液型はなんですか？複数選択可"></textarea>';
     radio_original += '<div class="text-right">';
     radio_original += '<span class="add_radio_item"><i class="glyphicon glyphicon-plus"></i></span>';
     radio_original += '</div>';//text-right
     radio_original += '<div class="radio_area">';
     radio_original += '<label class="radio-inline"><input type="radio" name="answer['+ form_id + '][]" disabled><input type="text" name="select_items[' + form_id + '][]"><span class="remove_item"> <i class="glyphicon glyphicon-remove-circle"></i></span></label>';
     radio_original += '<label class="radio-inline"><input type="radio" name="answer['+ form_id + '][]" disabled><input type="text" name="select_items[' + form_id + '][]"><span class="remove_item"> <i class="glyphicon glyphicon-remove-circle"></i></span></label>';
     radio_original += '<label class="radio-inline"><input type="radio" name="answer['+ form_id + '][]" disabled><input type="text" name="select_items[' + form_id + '][]"><span class="remove_item"> <i class="glyphicon glyphicon-remove-circle"></i></span></label>';
     radio_original += '</div>';//radio_area
     radio_original += '<input type=hidden name="form_types[' + form_id + ']" value="radio">';
     radio_original += '</div>';//form-group
     radio_original += '</div>';//col-xs-11
     radio_original += '<div class="col-xs-1 text-right">';
     radio_original += '<span class="remove"><i class="glyphicon glyphicon-trash"></i></span>';
     radio_original += '</div>';//col-xs-1
     radio_original += '</div>';//row

     console.log(radio_original);
     $('#myform').append(radio_original);
     id_num++;
  });

  //form-group削除
  $(document).on('click','.remove',function(){
         $(this).closest(".row").remove();
         console.log("removed")
    });
    //選択肢削除
  $(document).on('click','.remove_item',function(){
         $(this).parent('label').remove();
         console.log("itemremoved")
    });

    //checkbox選択肢追加
  $(document).on('click','.add_checkbox_item',function(){
     var form_id = $(this).parents('.form-group').attr('id');
    console.log(form_id);
     var checkbox_item = '';
         checkbox_item += '<label class="checkbox-inline">';
         checkbox_item += '<input type="checkbox" name="answer[' + form_id + '][]" disabled>';
         checkbox_item += '<input type="text" name="select_items[' + form_id + '][]">';
         checkbox_item += '<span class="remove_item"> <span class="remove_item"><i class="glyphicon glyphicon-remove-circle"></i></span></span>';
         checkbox_item += '</label>';
     $(this).parents('.form-group').children('.checkbox_area').append(checkbox_item);
    });

    //radio選択肢
  $(document).on('click','.add_radio_item',function(){
     var form_id = $(this).parents('.form-group').attr('id');
     console.log(form_id);
     var radio_item = '';
         radio_item += '<label class="radio-inline">';
         radio_item += '<input type="radio" name="answer[' + form_id + '][]" disabled>';
         radio_item += '<input type="text" name="select_items[' + form_id + '][]">';
         radio_item += '<span class="remove_item"> <span class="remove_item"><i class="glyphicon glyphicon-remove-circle"></i></span></span>';
         radio_item += '</label>';
     $(this).parents('.form-group').children('.radio_area').append(radio_item);
    });

});
</script>
</html>
