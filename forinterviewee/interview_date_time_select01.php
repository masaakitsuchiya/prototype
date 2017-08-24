<?php

// /10/my_kaday/setting/interview_date_time_select01.php?interviewee_id=*&interview_id=*

session_start();
include("../function/function.php");
if(isset($_GET["interview_id"]) || !$_GET["interview_id"] == ""){
$_SESSION["interview_id"] = $_GET["interview_id"];
}

// var_dump($_SESSION["interview_id"]);
$pdo = db_con();

//２．データ登録SQL作成
// $stmt = $pdo->prepare("SELECT interviewee_info.interviewee_name,interview.interview_style FROM interviewee_info,interview where interview.id= :interview_id AND interviewee_info.id = interview.interviewee_id");

$stmt = $pdo->prepare("SELECT * FROM interview INNER JOIN interviewee_info  ON interview.interviewee_id = interviewee_info.id WHERE interview.id = :interview_id");
$stmt->bindValue(':interview_id',$_SESSION["interview_id"],PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res = $stmt->fetch();
}
// var_dump($res["interview_style"]);
$html_title = servise_name();
$_SESSION["interview_style"] = $res["interview_style"];
if($res["stage_flg"] != 1){
  header("Location: please_waiting.php?stage_flg=".$res["stage_flg"]);
exit;
}

?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head_for_interviewee.php") ?>
<style>
  thead.scrollHead,tbody.scrollBody{
      display:block;
  }
  tbody.scrollBody{
    overflow-y:scroll;
    height:500px;
  }
.container-fruid{
  margin-bottom:30px;
}
h4.pg{
font-size:0.9em;
}
.gray{
  color:#aaa;
}
.thanks_message{
  border:dotted 1px #0000ff;
}

</style>

</head>
<body>
<?php include("../template/nav_for_interviewee.php") ?>
<div class="container-fruid">
  <div class="row">
      <div class="col-xs-2 hidden-xs"></div>
      <?php if($res["interview_style"]==1): ?>
        <h4 class="col-xs-2 pg text-center">1,規約同意</h4>
        <h4 class="col-xs-2 pg text-center gray">2,動作検証</h4>
        <h4 class="col-xs-2 pg text-center gray">3,面接日時選択</h4>
        <h4 class=" col-xs-2 pg text-center gray">4,返信完了</h4>
      <?php elseif($res["interview_style"]==2): ?>
        <div class="col-xs-8">
          <div class="row">
          <h4 class="col-xs-4 pg text-center">1,規約同意</h4>
          <h4 class="col-xs-4 pg text-center gray">2,面接日時選択</h4>
          <h4 class=" col-xs-4 pg text-center gray">3,返信完了</h4>
          </div>
        </div>
      <?php endif; ?>
      <div class="col-xs-2 hidden-xs"></div>
  </div>
  <div class="row">
    <div class="col-xs-2 hidden-xs"></div>
    <div class="col-xs-8 thanks_message">
      <h3 class="text-center"><?=h($res["interviewee_name"]);?>様</h3>
<?php if($res["interview_style"]==1): ?>
      <p class="text-left">
        この度は当社求人にご応募いただきありがとうございます。ウェブ面接機能を利用して面接を実施させていただきたいと思いますので,この後表示される候補日時からご対応可能な日時を選択して頂ますようお願い致します。
      </p>
      <p class="text-left">
      もし、ご都合の良い日時がなかったり、ウェブ面接をご希望されない場合は<a href="interview_reset.php">こちら</a>からご連絡ください。
      </p>
<?php elseif($res["interview_style"]==2): ?>
    <p class="text-left">
      この度は当社求人にご応募いただきありがとうございます。この後表示される候補日時からご対応可能な日時を選択して頂ますようお願い致します。
    </p>
    <p class="text-left">
    もし、ご都合の良い日時がない場合は<a href="interview_reset.php">こちら</a>からご連絡ください。
    </p>
<?php endif; ?>
    </div>
    <div class="col-xs-2 hidden-xs"></div>
  </div>
</div>
<div class="container-fruid">
  <div class="row">
    <div class="col-xs-2 hidden-xs"></div>
    <div class="col-xs-8">
    <h3 class="text-center">規約同意</h3>
    <p>
    面接日時の調整<?php if($res["interview_style"]==1): ?>とウェブ面接<?php endif;?>を行うにあたり InterFreeというサービスを利用します。<br>
    大変お手数ですが、利用に先立ちInterFreeの利用規約（以下）をご確認くださいますようお願い致します。</p>
    </div>
    <div class="col-xs-2 hidden-xs"></div>
  </div>
</div>

  <div class="container-fruid">
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8">
        <table class="table table-responsive table-hover table-kiyaku">
          <thead class="scrollHead"><tr><th class="text-center"></th></tr></thead>
          <tbody class="scrollBody">
          <tr>
            <td>
              <div class="text-center">利用規約</div>
              <p>
                この利用規約（以下、「本規約」といいます。）は、当社がこのウェブサイト上で提供するサービス（以下、「本サービス」といいます。）の利用条件を定めるものです。利用者の皆さま（以下、「ユーザー」といいます。）には、本規約に従って、本サービスをご利用いただきます。
                <br>第1条（適用）
                <br>本規約は、ユーザーと当社との間の本サービスの利用に関わる一切の関係に適用されるものとします。
                <br>
                <br>第2条（禁止事項）
                <br>ユーザーは、本サービスの利用にあたり、以下の行為をしてはなりません。
                <br>（1）法令または公序良俗に違反する行為
                <br>（2）犯罪行為に関連する行為
                <br>（3）当社のサーバーまたはネットワークの機能を破壊したり、妨害したりする行為
                <br>（4）当社のサービスの運営を妨害するおそれのある行為
                <br>（5）他のユーザーに関する個人情報等を収集または蓄積する行為
                <br>（6）他のユーザーに成りすます行為
                <br>（7）当社のサービスに関連して、反社会的勢力に対して直接または間接に利益を供与する行為
                <br>（8）その他、当社が不適切と判断する行為
                <br>
                <br>第3条（本サービスの提供の停止等）
                <br>当社は、以下のいずれかの事由があると判断した場合、ユーザーに事前に通知することなく本サービスの全部または一部の提供を停止または中断することができるものとします。
                <br>（1）本サービスにかかるコンピュータシステムの保守点検または更新を行う場合
                <br>（2）地震、落雷、火災、停電または天災などの不可抗力により、本サービスの提供が困難となった場合
                <br>（3）コンピュータまたは通信回線等が事故により停止した場合
                <br>（4）その他、当社が本サービスの提供が困難と判断した場合
                <br>
                <br>第4条（免責事項）
                <br>1.当社は、前条に定める事由により発生したユーザーまたは第三者が被ったいかなる不利益または損害について、理由を問わず一切の賠償責任を負わないものとします。
                <br>2.当社は、本サービスに関して、ユーザーと他のユーザーまたは第三者との間において生じた取引、連絡または紛争等について一切責任を負いません。
                <br>3.当社は、本サービスによりユーザーが知り得た情報等について、完全性・確実性・正確性・有用性に関していかなる責任も負いません。
                <br>
                <br>第5条（利用制限および登録抹消）
                <br>1.当社は、以下の場合には、事前の通知なく、ユーザーに対して、本サービスの全部もしくは一部の利用を制限し、またはユーザーとしての登録を抹消することができるものとします。
                <br>（1）本規約のいずれかの条項に違反した場合
                <br>（2）登録事項に虚偽の事実があることが判明した場合
                <br>（3）その他、当社が本サービスの利用を適当でないと判断した場合
                <br>2.当社は、本条に基づき当社が行った行為によりユーザーに生じた損害について、一切の責任を負いません。
                <br>
                <br>第6条（守秘義務）
                <br>ユーザーは、本サービスの利用に関連して知り得た情報、その他相手方の機密に属すべき一切の事項を第三者に開示・漏洩させてはなりません。
                <br>第7条（サービス内容の変更等）
                <br>当社は、ユーザーに通知することなく、本サービスの内容を変更しまたは本サービスの提供を中止することができるものとし、これによってユーザーに生じた損害について一切の責任を負いません。
                <br>第8条（利用規約の変更）
                <br>1.当社は、必要と判断した場合には、ユーザーに通知することなくいつでも本規約を変更することができるものとします。
                <br>2.変更後の規約は、当社のウェブサイト上に掲載されたときから効力を生じるものとします。
                <br>
                <br>第9条（通知または連絡）
                <br>ユーザーと当社との間の通知または連絡は、当社の定める方法によって行うものとします。
                <br>第10条（権利義務の譲渡の禁止）
                <br>ユーザーは、当社の書面による事前の承諾なく、利用契約上の地位または本規約に基づく権利もしくは義務を第三者に譲渡し、または担保に供することはできません。
                <br>
                <br>第11条（準拠法・裁判管轄）
                <br>本規約の解釈にあたっては、日本法を準拠法とします。
                <br>本サービスに関して紛争が生じた場合には、当社の本店所在地を管轄する裁判所を専属的合意管轄とします。
                <br>2017年4月1日作成
              </p>
            </td>
          </tr>
        </tbody>
        </table>
      </div>
      <div class="col-sm-2"></div>
    </div>
    <?php if($res["interview_style"]==1):?>
      <form action="interview_date_time_select02.php" method="post">
        <div class="text-center">
          <input type="checkbox" id="check" />
          <label for="check"><!--<a href="#" target="_blank">利用規約</a>-->利用規約に同意します</label>
        </div>
         <div class="text-center">
          <a type="button" class="btn btn-info" id="submit" href="interview_date_time_select02.php?interview_id=<?=$_SESSION["interview_id"] ?>">次へ</a>
          <!-- <input type="submit" class="btn btn-info" id="submit" value="次へ" /> -->
        </div>
      </form>
    <?php elseif($res["interview_style"]==2):?>
      <form action="interview_date_time_select03.php" method="post">
        <div class="text-center">
          <input type="checkbox" id="check" />
          <label for="check"><!--<a href="#" target="_blank">利用規約</a>-->利用規約に同意します</label>
        </div>
         <div class="text-center">
             <a type="button" class="btn btn-info" id="submit" href="interview_date_time_select03.php?interview_id=<?=$_SESSION["interview_id"] ?>">次へ</a>
          <!-- <input type="submit" class="btn btn-info" id="submit" value="次へ" /> -->
        </div>
      </form>
    <?php endif; ?>
  </div>
<?php include("../template/footer_for_interviewee.html") ?>
<script>
  $(function() {
  	$('#submit').attr('disabled', 'disabled');

  	$('#check').click(function() {
  		if ($(this).prop('checked') == false) {
  			$('#submit').attr('disabled', 'disabled');
  		} else {
  			$('#submit').removeAttr('disabled');
  		}
  	});
  });
</script>
</body>
</html>
