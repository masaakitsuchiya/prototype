<?php
session_start();
include("../function/function.php");
login_check();
//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成 該当の候補者情報の抽出
$stmt_form = $pdo->prepare("SELECT * FROM form WHERE life_flg=:life_flg");
$stmt_form->bindValue(':life_flg', 1, PDO::PARAM_INT);

$status_form = $stmt_form->execute();

$view_form_item = "";
//３．データ表示
if($status_form==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_form->errorInfo();
  exit("ErrorQuery_form:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_form = $stmt_form->fetch(PDO::FETCH_ASSOC)){
    $view_form_item .= '<tr>';
    $view_form_item .= '<td>';
    $view_form_item .= h($result_form["form_name"]);
    $view_form_item .= '</td>';
    $view_form_item .= '<td>';
    $view_form_item .= h($result_form["form_description"]);
    $view_form_item .= '</td>';
    $view_form_item .= '<td>';
    // $view_form_item .= '<a type="button" href="show.php?form_id='.h($result_form["form_id"]).'" class="btn btn-sm btn-primary">確認・修正</a> <a type="button" class="btn btn-sm btn-danger" href="delete.php?form_id='.h($result_form["form_id"]).'">削除</a>';
    $view_form_item .= '<a type="button" href="show.php?form_id='.h($result_form["form_id"]).'" class="btn btn-sm btn-primary">確認・修正</a> <a data-toggle="modal" href="#myModal_cancel_'.h($result_form["form_id"]).'" class="btn btn-danger btn-sm">削除</a>';
    // <!-- キャンセルモーダル -->
    $view_form_item .= '<div class="modal fade" id="myModal_cancel_'.h($result_form["form_id"]).'">';
    $view_form_item .= '<div class="modal-dialog">';
    $view_form_item .= '<div class="modal-content">';
    $view_form_item .= '<div class="modal-header">';
    $view_form_item .= '<button class="close" data-dismiss="modal">&times;</button>';
    $view_form_item .= '<h4 class="modal-title">削除確認</h4>';
    $view_form_item .= '</div>';
    $view_form_item .= '<div class="modal-body">本当に削除してよろしいでしょうか。削除すると元に戻せません。</div>';
    $view_form_item .= '<div class="modal-footer">';
    $view_form_item .= '<a class="btn btn-danger" href="delete.php?form_id='.h($result_form["form_id"]).'">削除</a>';
    $view_form_item .= '</div>';
    $view_form_item .= '</div>';
    $view_form_item .= '</div>';
    $view_form_item .= '</div>';

    $view_form_item .= '</td>';
    $view_form_item .= '</tr>';
  }
}

$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
.mb-30{
  margin-bottom:30px;
}

</style>
</head>
<body>
<?php include("../template/nav.php") ?>
<div class="container">

  <h2 class="text-center">フリーアンケート一覧</h2>
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="text-right mb-30">
        <a class="btn btn-default" type="button" href="input.php">新規作成</a>
      </div>

      <table class="table">
        <?=$view_form_item?>
      </table>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>


<?php include("../template/footer.html") ?>
</body>
<script>

</script>
</html>
