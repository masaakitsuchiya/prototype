<?php
session_start();
include("../function/function.php");
login_check();
include("../template/csrf_token_generate.php");
$id = $_GET["target_interviewee_id"];

$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM interviewee_info where id=:id");
$stmt->bindValue(':id',$id,PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res = $stmt->fetch();
}

//名前を分割

$interviewee_name = explode("　",($res["interviewee_name"]));
$last_name = $interviewee_name[0];
$first_name = $interviewee_name[1];
//名前フリガナを分割
$interviewee_name_kana = explode("　",($res["interviewee_name_kana"]));
$last_name_kana = $interviewee_name_kana[0];
$first_name_kana = $interviewee_name_kana[1];
//誕生日を分割
$birthday = explode("-",($res["birthday"]));

$b_y = $birthday[0];
$b_m = $birthday[1];
$b_d = $birthday[2];



//postcodeを分割
if($res["postcode"]!=""){
$postcode = explode("-",($res["postcode"]));
$postcode0 = $postcode[0];
$postcode1 = $postcode[1];
}
//住所を分割

if($res["address"]!=""){
$address = explode("\n",($res["address"]));
$address1 = $address[0];
$address2 = $address[1];
$address3 = $address[2];
}



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
    if($result["id"]==$res["job_post_id"]){
      $view .='<option value="'.h($result["id"]).'" selected>'.h($result["job_title"]).'</option>';
    }else{
      $view .='<option value="'.h($result["id"]).'">'.h($result["job_title"]).'</option>';
    }
  }
}
$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
.file_icon{
  font-size:3em;
}
</style>
</head>
<body>
<?php include("../template/nav.php") ?>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <h3 class="text-center">候補者情報更新</h3>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form-horizontal" action="interviewee_update.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewee_name">氏名（漢字）</label>
          <div class="col-sm-5"><span>姓</span><input type="text" class="form-control" name="last_name" value="<?= h($last_name); ?>"></div>
          <div class="col-sm-5"><span>名</span><input type="text" class="form-control" name="first_name" value="<?= h($first_name); ?>"></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewee_name_kana">氏名（フリガナ）</label>
          <div class="col-sm-5"><span>セイ</span><input type="text" class="form-control" name="last_name_kana" value="<?= h($last_name_kana); ?>"></div>
          <div class="col-sm-5"><span>メイ</span><input type="text" class="form-control" name="first_name_kana" value="<?= h($first_name_kana); ?>"></div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="job_post_id">応募職種</label>
          <div class="col-sm-5"><select class="form-control" name="job_post_id">
              <?= $view ?>
          </select></div>
          <div class="col-sm-5"></div>
        </div>

        <div class="form-group">
          <label class="control-label col-sm-2" for="birthday">生年月日</label>
          <div class="col-sm-2"><span>年</span><select class="form-control" name="b_y">
            <option value=""<?php if($b_y == "00"){echo "selected";} ?>></option>
            <?php for($i = date("Y"); $i > 1900;$i--){
              if($i == $b_y){
                  echo '<option value="'.$i.'" selected>'.$i.'</option>';
                }else{
                    echo '<option value="'.$i.'">'.$i.'</option>';
                }
                  }
            ?>
          </select></div>
          <div class="col-sm-1"><span>月</span><select class="form-control" name="b_m">
            <option value=""<?php if($b_m == "00"){echo "selected";} ?>></option>
            <?php for($i = 1; $i < 13;$i++){
              if($i == $b_m){
                  echo '<option value="'.$i.'" selected>'.$i.'</option>';
                }else{
                  echo '<option value="'.$i.'">'.$i.'</option>';
                  }
                }
            ?>
          </select></div>
          <div class="col-sm-1"><span>日</span><select class="form-control" name="b_d">
            <option value=""<?php if($b_d == "00"){echo "selected";} ?>></option>
            <?php for($i = 1; $i < 32;$i++){
              if($i == $b_d){
                  echo '<option value="'.$i.'" selected>'.$i.'</option>';
                }else{
                  echo '<option value="'.$i.'">'.$i.'</option>';
                  }
                }
            ?>
          </select></div>
          <div class="col-sm-6"></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="sex">性別</label>
          <div class="col-sm-10">
            <label class="radio-inline"><input type="radio" <?php if($res["sex"]=="0"){echo "checked";}?> name="sex" value="0">男</label>
            <label class="radio-inline"><input type="radio" <?php if($res["sex"]=="1"){echo "checked";}?> name="sex" value="1">女</label>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="mail">メールアドレス</label>
          <div class="col-sm-5"><span>メールアドレス</span><input type="text" class="form-control" name="mail" value="<?= h($res["mail"]); ?>"></div>
          <!-- <div class="col-sm-5"><span>メールアドレス確認用</span><input type="text" class="form-control" name="mail_confirm" value=""></div> -->
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="address">住所情報</label>
          <div class="col-sm-10">
            <span>郵便番号</span>
            <div class="row">
              <div class="col-xs-2"><input type="text" class="form-control" name="postcode0" value="<?= h($postcode0); ?>"></div>
              <div class="col-xs-1">ー</div>
              <div class="col-xs-2"><input type="text" class="form-control" name="postcode1" value="<?= h($postcode1); ?>"></div>
            </div>
            <span>都道府県</span>
            <div class="row">
              <div class="col-xs-3">
                <select class="form-control" name="address0">
                  <option <?php if($address1==1){echo"selected";} ?>value='1'>北海道</option>
                  <option <?php if($address1==2){echo"selected";} ?>value='2'>青森県</option>
                  <option <?php if($address1==3){echo"selected";} ?>value='3'>岩手県</option>
                  <option <?php if($address1==4){echo"selected";} ?>value='4'>秋田県</option>
                  <option <?php if($address1==5){echo"selected";} ?>value='5'>山形県</option>
                  <option <?php if($address1==6){echo"selected";} ?>value='6'>宮城県</option>
                  <option <?php if($address1==7){echo"selected";} ?>value='7'>福島県</option>
                  <option <?php if($address1==8){echo"selected";} ?>value='8'>茨城県</option>
                  <option <?php if($address1==9){echo"selected";} ?>value='9'>栃木県</option>
                  <option <?php if($address1==10){echo"selected";} ?>value='10'>群馬県</option>
                  <option <?php if($address1==11){echo"selected";} ?>value='11'>埼玉県</option>
                  <option <?php if($address1==12){echo"selected";} ?>value='12'>千葉県</option>
                  <option <?php if($address1==13){echo"selected";} ?>value='13'>東京都</option>
                  <option <?php if($address1==14){echo"selected";} ?>value='14'>神奈川県</option>
                  <option <?php if($address1==15){echo"selected";} ?>value='15'>山梨県</option>
                  <option <?php if($address1==16){echo"selected";} ?>value='16'>長野県</option>
                  <option <?php if($address1==17){echo"selected";} ?>value='17'>新潟県</option>
                  <option <?php if($address1==18){echo"selected";} ?>value='18'>富山県</option>
                  <option <?php if($address1==19){echo"selected";} ?>value='19'>石川県</option>
                  <option <?php if($address1==20){echo"selected";} ?>value='20'>福井県</option>
                  <option <?php if($address1==21){echo"selected";} ?>value='21'>岐阜県</option>
                  <option <?php if($address1==22){echo"selected";} ?>value='22'>静岡県</option>
                  <option <?php if($address1==23){echo"selected";} ?>value='23'>愛知県</option>
                  <option <?php if($address1==24){echo"selected";} ?>value='24'>三重県</option>
                  <option <?php if($address1==25){echo"selected";} ?>value='25'>滋賀県</option>
                  <option <?php if($address1==26){echo"selected";} ?>value='26'>京都府</option>
                  <option <?php if($address1==27){echo"selected";} ?>value='27'>大阪府</option>
                  <option <?php if($address1==28){echo"selected";} ?>value='28'>兵庫県</option>
                  <option <?php if($address1==29){echo"selected";} ?>value='29'>奈良県</option>
                  <option <?php if($address1==30){echo"selected";} ?>value='30'>鳥取県</option>
                  <option <?php if($address1==31){echo"selected";} ?>value='31'>和歌山県</option>
                  <option <?php if($address1==32){echo"selected";} ?>value='32'>島根県</option>
                  <option <?php if($address1==33){echo"selected";} ?>value='33'>岡山県</option>
                  <option <?php if($address1==34){echo"selected";} ?>value='34'>広島県</option>
                  <option <?php if($address1==35){echo"selected";} ?>value='35'>山口県</option>
                  <option <?php if($address1==36){echo"selected";} ?>value='36'>徳島県</option>
                  <option <?php if($address1==37){echo"selected";} ?>value='37'>香川県</option>
                  <option <?php if($address1==38){echo"selected";} ?>value='38'>愛媛県</option>
                  <option <?php if($address1==39){echo"selected";} ?>value='39'>高知県</option>
                  <option <?php if($address1==40){echo"selected";} ?>value='40'>福岡県</option>
                  <option <?php if($address1==41){echo"selected";} ?>value='41'>佐賀県</option>
                  <option <?php if($address1==42){echo"selected";} ?>value='42'>長崎県</option>
                  <option <?php if($address1==43){echo"selected";} ?>value='43'>熊本県</option>
                  <option <?php if($address1==44){echo"selected";} ?>value='44'>大分県</option>
                  <option <?php if($address1==45){echo"selected";} ?>value='45'>宮崎県</option>
                  <option <?php if($address1==46){echo"selected";} ?>value='46'>鹿児島県</option>
                  <option <?php if($address1==47){echo"selected";} ?>value='47'>沖縄県</option>
                  <option <?php if($address1==48){echo"selected";} ?>value='48'>海外</option>
                </select>
            </div>
          </div>
          <p><span>市区町村・番地<span><input type="text" class="form-control" name="address1" value="<?php if($address2){echo h($address2);}?>"></p>
          <p><span>マンション・アパート名<span><input type="text" class="form-control" name="address2" value="<?php if($address3){echo h($address3);}?>"></p>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="github">githubアカウント</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="github" value="<?= h($res["github"]); ?>" placeholder="githubのアカウントをお持ちであればURLを記載してください。">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="portfolio">ポートフォリオなど</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="portfolio" value="<?= h($res["portfolio"]); ?>" placeholder="ポートフォリオサイトなどをお持ちであればURLを記載してください。">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="motivation">志望動機</label>
          <div class="col-sm-10">
            <textArea class="form-control" name="motivation" rows="10" cols="80" placeholder="チャレンジしたいことなどをお書きください。（1000文字以内）"><?= h($res["motivation"]); ?></textArea>
          </div>
        </div>
        <div class="col-sm-2"></div>
        <p id="attached" class="help-block col-sm-10">履歴書や職務経歴書を添付してください。pdfまたはtxt形式。最大3つまで</p>
        <div class="form-group">
          <label class="control-label col-sm-2" for="resume0">履歴書・経歴書</label>
          <div class="col-sm-10">
            <div class="row">
              <div class="col-sm-6">
                <input type="file" class="form-control" name="resume0" accept="application/pdf,text/plain" value="<?= h($res["resume0"]);?>" aria-describedby="attached">
              </div>
              <div class="col-sm-6">
                <?php if(!isset($res["resume0"])||$res["resume0"] ==""): ?>
                <span>ファイルなし</span>
                <?php else : ?>
                <a href="<?= h($res["resume0"]); ?>"><span class="glyphicon glyphicon-book text-primary file_icon"></span></a>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="resume1">履歴書・経歴書</label>
          <div class="col-sm-10">
            <div class="row">
              <div class="col-sm-6">
                <input type="file" class="form-control" name="resume1" accept="application/pdf,text/plain" value="<?= h($res["resume1"]); ?>" aria-describedby="attached">
              </div>
              <div class="col-sm-6">
                <?php if(!isset($res["resume1"])||$res["resume1"] ==""): ?>
                <span>ファイルなし</span>
                <?php else : ?>
                <a href="<?= h($res["resume1"]); ?>"><span class="glyphicon glyphicon-book text-primary file_icon"></span></a>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="resume2">履歴書・経歴書</label>
          <div class="col-sm-10">
            <div class="row">
              <div class="col-sm-6">
                <input type="file" class="form-control" name="resume2" accept="application/pdf,text/plain" value="<?= h($res["resume2"]);?>" aria-describedby="attached">
              </div>
              <div class="col-sm-6">
                <?php if(!isset($res["resume2"])||$res["resume2"] ==""): ?>
                <span>ファイルなし</span>
                <?php else : ?>
                <a href="<?= h($res["resume2"]); ?>"><span class="glyphicon glyphicon-book text-primary file_icon"></span></a>
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>

        <input type="hidden" name="interviewee_id" value="<?=$res["id"]?>">
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
