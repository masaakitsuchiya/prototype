<?php
session_start();
include("../function/function.php");
login_check();
kanri_check();

//1. POSTデータ取得 from interview_setting
$form_name = $_POST["form_name"];
$form_description = $_POST["form_description"];
$questions =  $_POST["questions"];
// $answer =  $_POST["answer"];
$form_types = $_POST["form_types"];
$select_items = $_POST["select_items"];
// echo('<pre>');
// var_dump($questions);
// // var_dump($answer);
// var_dump($form_types);
// var_dump($select_items);
// echo('</pre>');
// exit;

$pdo = db_con();


//form テーブルに情報登録
$stmt_form = $pdo->prepare("INSERT INTO form(form_id,form_name,form_description,life_flg
)VALUES(NULL,:form_name,:form_description,:life_flg)");
$stmt_form->bindValue(':form_name', $form_name, PDO::PARAM_STR);
$stmt_form->bindValue(':form_description', $form_description, PDO::PARAM_STR);
$stmt_form->bindValue(':life_flg', 1, PDO::PARAM_STR);
$status_form = $stmt_form->execute();

  if($status_form ==false){
      //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
      $error = $stmt_form->errorInfo();
      exit("QueryError_form:".$error[2]);
  }

$form_id = $pdo->lastInsertId();
$stmt_form_item = $pdo->prepare("INSERT INTO form_item(form_item_id,form_id,form_type,question,form_order
)VALUES(NULL,:form_id,:form_type,:question,:form_order)");
$stmt_form_item->bindValue(':form_id', $form_id, PDO::PARAM_INT);
foreach($questions as $form_num => $question){//質問ごとに実行 質問の内容と型、順番をDBに登録
  $stmt_form_item->bindValue(':form_type', $form_types[$form_num], PDO::PARAM_INT);
  $stmt_form_item->bindValue(':question', $question, PDO::PARAM_STR);
  $form_order = intval(str_replace('form_','',$form_num));//keyのform_numからform_idを削除して数値だけをとりだし、数値型に変換
  $stmt_form_item->bindValue(':form_order', $form_order, PDO::PARAM_INT);
  $status_form_item = $stmt_form_item->execute();

    if($status_form_item ==false){
        //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
        $error = $stmt_form_item->errorInfo();
        exit("QueryError_form_item:".$error[2]);
    }

  $form_item_id = $pdo->lastInsertId();
    if(isset($select_items[$form_num])){//select_itemが素材していたらDBに登録
      $stmt_select_item = $pdo->prepare("INSERT INTO select_item(select_item_id,form_item_id,select_item_label
      )VALUES(NULL,:form_item_id,:select_item_label)");
      $stmt_select_item->bindValue(':form_item_id', $form_item_id, PDO::PARAM_INT);
      foreach($select_items[$form_num] as $select_item_lavel){
        $stmt_select_item->bindValue(':select_item_label', $select_item_lavel, PDO::PARAM_STR);
        $status_select_item = $stmt_select_item->execute();
        if($status_select_item ==false){
            //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
            $error = $stmt_select_item->errorInfo();
            exit("QueryError_select_item:".$error[2]);
        }
      }



  }

}


  header("Location: index.php");
  exit;
// }

?>
