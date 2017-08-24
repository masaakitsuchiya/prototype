<?php
session_start();
include("../function/function.php");
login_check();
//1.  DB接続します


$html_title = servise_name();
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

</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">アンケートフォーム作成</h3>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10 text-center">フォームを作成します。</div>
    <div class="col-sm-1"></div>
  </div>
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-8">
      <form class="form-horizontal" method="post" action="insert.php">
        <div id="myform">
          <div class="form-group">
            <label class="control-label" for="form_name">フォーム名</label>
            <input class="form-control" type="text" name="form_name" value="">
          </div>
          <div class="form-group">
            <label class="control-label" for="form_description">フォーム説明文</label>
            <textarea class="form-control" name="form_description" placeholder="フォームの説明を入力"></textarea>
          </div>
          <div class="row">
            <div class="col-xs-11">
              <div class="form-group" id="form_0">
                <label class="control-label" for="questions[form_0]">質問:</label>
                <textarea class="form-control" name="questions[form_0]" placeholder="質問文を入力してください。　　例）なぜ弊社の求人に興味をもっていただいたのでしょうか"></textarea>
                <label class="control-label" for="answer[form_0][]">回答欄:</label>
                <textarea class="form-control" name="answer[form_0][]" disabled></textarea>
                <input type=hidden name="form_types[form_0]" value="textarea">
                <!-- <input type=hidden name="select_items[form_0][]" value=""> --><!-- textareaのときはnullでよし -->
              </div>
            </div>
            <div class="col-xs-1 text-right">
              <!-- <input type="button" class="remove" value="remove"> -->
              <span class="remove"><i class="glyphicon glyphicon-trash"></i></span>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-11">
              <div class="form-group" id="form_1">
                <label class="control-label" for="questions[form_1]">質問:</label>
                <textarea class="form-control" name="questions[form_1]" placeholder="質問文を入力してください。　　例）なぜ弊社の求人に興味をもっていただいたのでしょうか"></textarea>
                <label class="control-label" for="answer[form_1][]">回答欄:</label>
                <textarea class="form-control" name="answer[form_1][]" disabled></textarea>
                <input type=hidden name="form_types[form_1]" value="textarea">
                <!-- <input type=hidden name="select_items[form_0][]" value=""> --><!-- textareaのときはnullでよし -->
              </div>
            </div>
            <div class="col-xs-1 text-right">
              <!-- <input type="button" class="remove" value="remove"> -->
              <span class="remove"><i class="glyphicon glyphicon-trash"></i></span>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-11">
              <div class="form-group" id="form_2">
                <label class="control-label" for="questions[form_2]">質問:</label>
                <textarea class="form-control" name="questions[form_2]" placeholder="質問文を入力してください。　　例）あなたの得意な科目はなんですか？複数選択可"></textarea>
                <div class="text-right">
                    <span class="add_checkbox_item"><i class="glyphicon glyphicon-plus"></i></span>
                </div>
                <div class="checkbox_area">
                  <label class="checkbox-inline"><input type="checkbox" name="answer[form_2][]" value="##" disabled><input type="text" name="select_items[form_2][]"> <span class="remove_item"><i class="glyphicon glyphicon-remove-circle"></i></span></label><!--フォームを表示するときにvalueにselect_itemの値を入れる。-->
                  <label class="checkbox-inline"><input type="checkbox" name="answer[form_2][]" value="##" disabled><input type="text" name="select_items[form_2][]"> <span class="remove_item"><i class="glyphicon glyphicon-remove-circle"></i></span></label>
                  <label class="checkbox-inline"><input type="checkbox" name="answer[form_2][]" value="##" disabled><input type="text" name="select_items[form_2][]"> <span class="remove_item"><i class="glyphicon glyphicon-remove-circle"></i></span></label>
                </div>
                <input type=hidden name="form_types[form_2]" value="checkbox">
              </div>
            </div>
            <div class="col-xs-1 text-right">
                <!-- <input type="button" class="remove" value="remove"> -->
                <span class="remove"><i class="glyphicon glyphicon-trash"></i></span>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-11">
              <div class="form-group" id="form_3">
                <label class="control-label" for="questions[form_3]">質問:</label>
                <textarea class="form-control" name="questions[form_3]" placeholder="質問文を入力してください。　　例）Javaの開発経験は何年くらいですか。一つ選んでください。"></textarea>
                <div class="text-right">
                    <span class="add_radio_item"><i class="glyphicon glyphicon-plus"></i></span>
                </div>
                <div class="radio_area">
                  <label class="radio-inline"><input type="radio" name="answer[form_3][]" value="##" disabled> <input type="text" name="select_items[form_3][]"> <span class="remove_item"><i class="glyphicon glyphicon-remove-circle"></i></span></label><!--フォームを表示するときにvalueにselect_itemの値を入れる。-->
                  <label class="radio-inline"><input type="radio" name="answer[form_3][]" value="##" disabled> <input type="text" name="select_items[form_3][]"> <span class="remove_item"><i class="glyphicon glyphicon-remove-circle"></i></span></label>
                  <label class="radio-inline"><input type="radio" name="answer[form_3][]" value="##" disabled> <input type="text" name="select_items[form_3][]"> <span class="remove_item"><i class="glyphicon glyphicon-remove-circle"></i></span></label>
                </div>
                <input type=hidden name="form_types[form_3]" value="radio">
              </div>
            </div>
            <div class="col-xs-1 text-right">
                <!-- <input type="button" class="remove" value="remove"> -->
                <span class="remove"><i class="glyphicon glyphicon-trash"></i></span>
            </div>
          </div>
        </div>
        <div class="text-center">
          <input type="submit" class="btn btn-info" value="登録">
        </div>
      </form>
    </div>
    <div class="col-sm-2">
      <ul class="list-unstyled">
      <li><button id="add_textarea" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> テキストフォーム</button></li>
      <li><button id="add_checkbox" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> チェックボックスフォーム</button></li>
      <li><button id="add_radio" class="btn btn-success"><i class="glyphicon glyphicon-plus-sign"></i> ラジオフォーム</button></li>
      </ul>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>



  <!-- フォームを固定で作成
  textarea radio checkbox
  データを格納できるようにする。
  データを編集できるようにする。
  フォームに入力して回答できるようにする。

  フォームの項目の追加削除をできるようにする。
  　ラヂオボタンやチェックボックスの追加削除をできるようにする。

  フォームの編集・削除をできるようにする。
  フォームのコピーをできるようにする。　できたら
  フォームの項目の順番を変えられるようにする。できたら

  フォームを候補者に送信できるようにする。 -->






<?php include("../template/footer.html") ?>
</body>
<script>
$(function(){
  var id_num = 4;
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
     console.log(id_num);
     console.log(form_id);
     id_num++;

  });
  //checkboxエリア追加
  $('#add_checkbox').click(function(){
     var form_id = 'form_' + id_num;
     var checkbox_original = '';
     checkbox_original += '<div class="row">';
     checkbox_original += '<div class="col-xs-11">';
     checkbox_original += '<div class="form-group" id="'+ form_id +'">';
     checkbox_original += '<label class="control-label" for="questions['+ form_id +']">質問:</label>';
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

    //  console.log(checkbox_original);
     $('#myform').append(checkbox_original);
     console.log(id_num);
     console.log(form_id);
     id_num++;


  });

  $('#add_radio').click(function(){
     var form_id = 'form_' + id_num;
     var radio_original = '';
     radio_original += '<div class="row">';
     radio_original += '<div class="col-xs-11">';
     radio_original += '<div class="form-group" id="' + form_id + '">';
     radio_original += '<label class="control-label" for="questions[' + form_id + ']">質問:</label>';
     radio_original += '<textarea class="form-control" name="questions[' + form_id + ']" placeholder="質問文を入力してください。　　例）あなたの得意な科目はなんですか？複数選択可"></textarea>';
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
     console.log(id_num);
     console.log(form_id);
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
