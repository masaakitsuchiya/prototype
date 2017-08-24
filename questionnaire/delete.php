<?php
session_start();
include("../function/function.php");
login_check();
kanri_check();
$form_id = $_GET["form_id"];

$pdo = db_con();
//form table 更新
$stmt_form = $pdo->prepare("UPDATE form SET life_flg=:life_flg WHERE form_id=:form_id");
$stmt_form->bindValue(':form_id', $form_id, PDO::PARAM_INT);
$stmt_form->bindValue(':life_flg', 2, PDO::PARAM_INT);
$status_form = $stmt_form->execute();

if($status_form ==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt_form->errorInfo();
    exit("QueryError_form:".$error[2]);
}

header("Location: index.php");
?>
