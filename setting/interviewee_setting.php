<?php
session_start();
include("../function/function.php");
login_check();
include("../template/csrf_token_generate.php");


$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM job_post");
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view .='<option value="'.h($result["id"]).'">'.h($result["job_title"]).'</option>';
  }
}
$html_title = servise_name();

?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
</style>
<body>
<?php include("../template/nav.php") ?>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <h3 class="text-center">候補者登録</h3>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form-horizontal" action="interviewee_insert.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label class="control-label col-sm-2" for="job_title">氏名（漢字）※</label>
          <div class="col-sm-5"><span>姓</span><input type="text" class="form-control" name="last_name" value="" required></div>
          <div class="col-sm-5"><span>名</span><input type="text" class="form-control" name="first_name" value="" required></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="job_title">氏名（フリガナ）※</label>
          <div class="col-sm-5"><span>セイ</span><input type="text" class="form-control" name="last_name_kana" value="" required></div>
          <div class="col-sm-5"><span>メイ</span><input type="text" class="form-control" name="first_name_kana" value="" required></div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="job_post_id">応募職種※</label>
          <div class="col-sm-5"><select class="form-control" name="job_post_id">
              <?= $view ?>
          </select></div>
          <div class="col-sm-5"></div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="birthday">生年月日</label>
          <div class="col-sm-2"><span>年</span><select class="form-control" name="b_y">
            <option value=""></option>
            <?php for($i = date("Y"); $i > 1900;$i--){
                  echo '<option value="'.$i.'">'.$i.'</option>';
                  }
            ?>
          </select></div>
          <div class="col-sm-1"><span>月</span><select class="form-control" name="b_m">
            <option value=""></option>
            <?php for($i = 1; $i < 13;$i++){
                  echo '<option value="'.$i.'">'.$i.'</option>';
                  }
            ?>
          </select></div>
          <div class="col-sm-1"><span>日</span><select class="form-control" name="b_d">
            <option value=""></option>
            <?php for($i = 1; $i < 32;$i++){
                  echo '<option value="'.$i.'">'.$i.'</option>';
                  }
            ?>
          </select></div>
          <div class="col-sm-6"></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="sex">性別※</label>
          <div class="col-sm-10">
            <label class="radio-inline"><input type="radio" name="sex" value="0" required>男</label>
            <label class="radio-inline"><input type="radio" name="sex" value="1">女</label>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="mail">メールアドレス※</label>
          <div class="col-sm-5"><span>メールアドレス</span><input type="text" class="form-control" name="mail" value="" required></div>
          <!-- <div class="col-sm-5"><span>メールアドレス確認用</span><input type="text" class="form-control" name="mail_confirm" value=""></div> -->
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="address">住所情報</label>
          <div class="col-sm-10">
            <span>郵便番号</span>
            <div class="row">
              <div class="col-xs-2"><input type="text" class="form-control" name="postcode0" value=""></div>
              <div class="col-xs-1">ー</div>
              <div class="col-xs-2"><input type="text" class="form-control" name="postcode1" value=""></div>
            </div>
            <span>都道府県</span>
            <div class="row">
              <div class="col-xs-3">
                <select class="form-control" name="address0">
                  <option value='1'>北海道</option>
                  <option value='2'>青森県</option>
                  <option value='3'>岩手県</option>
                  <option value='4'>秋田県</option>
                  <option value='5'>山形県</option>
                  <option value='6'>宮城県</option>
                  <option value='7'>福島県</option>
                  <option value='8'>茨城県</option>
                  <option value='9'>栃木県</option>
                  <option value='10'>群馬県</option>
                  <option value='11'>埼玉県</option>
                  <option value='12'>千葉県</option>
                  <option value='13'>東京都</option>
                  <option value='14'>神奈川県</option>
                  <option value='15'>山梨県</option>
                  <option value='16'>長野県</option>
                  <option value='17'>新潟県</option>
                  <option value='18'>富山県</option>
                  <option value='19'>石川県</option>
                  <option value='20'>福井県</option>
                  <option value='21'>岐阜県</option>
                  <option value='22'>静岡県</option>
                  <option value='23'>愛知県</option>
                  <option value='24'>三重県</option>
                  <option value='25'>滋賀県</option>
                  <option value='26'>京都府</option>
                  <option value='27'>大阪府</option>
                  <option value='28'>兵庫県</option>
                  <option value='29'>奈良県</option>
                  <option value='30'>鳥取県</option>
                  <option value='31'>和歌山県</option>
                  <option value='32'>島根県</option>
                  <option value='33'>岡山県</option>
                  <option value='34'>広島県</option>
                  <option value='35'>山口県</option>
                  <option value='36'>徳島県</option>
                  <option value='37'>香川県</option>
                  <option value='38'>愛媛県</option>
                  <option value='39'>高知県</option>
                  <option value='40'>福岡県</option>
                  <option value='41'>佐賀県</option>
                  <option value='42'>長崎県</option>
                  <option value='43'>熊本県</option>
                  <option value='44'>大分県</option>
                  <option value='45'>宮崎県</option>
                  <option value='46'>鹿児島県</option>
                  <option value='47'>沖縄県</option>
                  <option value='48'>海外</option>
                </select>
            </div>
          </div>
          <p><span>市区町村・番地<span><input type="text" class="form-control" name="address1" value=""></p>
          <p><span>マンション・アパート名<span><input type="text" class="form-control" name="address2" value=""></p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="github">githubアカウント</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="github" value="" placeholder="githubのアカウントをお持ちであればURLを記載してください。">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="portfolio">ポートフォリオなど</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="portfolio" value="" placeholder="ポートフォリオサイトなどをお持ちであればURLを記載してください。">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="motivation">志望動機</label>
          <div class="col-sm-10">
            <textArea class="form-control" name="motivation" rows="10" cols="80" placeholder="チャレンジしたいことなどをお書きください。（1000文字以内）"></textArea>
          </div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="resume">履歴書・職務経歴書等</label>
          <div class="col-sm-10">
            <input type="file" class="form-control" name="resume0" accept="application/pdf,text/plain" aria-describedby="attached">
            <input type="file" class="form-control" name="resume1" accept="application/pdf,text/plain">
            <input type="file" class="form-control" name="resume2" accept="application/pdf,text/plain">
            <p id="attached" class="help-block">履歴書や職務経歴書を添付してください。pdfまたはtxt形式。最大3つまで</p>
          </div>
        </div>
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

        <div class="text-center">
          <input class="btn btn-default" type="submit" value="登録">
        </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>

<?php include("../template/footer.html") ?>

</body>
</html>
