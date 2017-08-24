<?php session_start();

$html_title = '無料から使えるクラウド採用管理、面接システム InterFree';
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head_for_login.php") ?>
<style>
body {
  /*padding-top:70px;*/
  height:100%;
}
html{
  height:100%;
}
.main{
  height:100%;
  /*background:#fff;*/
  background-image:url("./img/top12.jpg");
  background-size:cover;
  background-repeat:no-repeat;
  background-position: center center;
}


.navbar-default {
background-color: rgba(51,122,183,0.8);
border-color:;

}
.navbar-default .navbar-nav > li > a {
color: #ffffff;
}
.navbar-default .navbar-brand {
color: #ffffff;
}
.navbar-right{
  padding-right:10px;
}
h2#top_text{
  font-size:3em;
  margin-top:50px;
  text-shadow:1px 1px 5px #fff;

}
div.main{

}
p#top_p_text{
  font-size:1.2em;
  margin-top:30px;
  margin-bottom:30px;
  text-shadow:2px 2px 5px #fff;
}
#top_img{
  width:70%;
}
.intro_sub_title{
  font-size: 1.2em;
  font-weight: bold;
  margin-top:20px;
}
.intro_img{
  width:90%;
}
.features,.plice_lists{
  background:#efefef;
  padding:20px;
}
.functions {
  padding:20px;
}
h3.features_title{

}
h4.muryo{
  /*background:#fff;*/
  color:#fff;
  padding:20px;
  font-size:2.0em;
  /*color:SkyBlue;*/
  line-height:1.5em;
}

.intro_text{
  width:90%;
  margin-top:25px;
}

.features_item_row{
  margin-bottom:40px;
}
/*.functions{
  background:rgba(60,100,200,1);
}*/
.functions_item_row h4.intro_sub_title{
  color:#fff;
}
.functions_item_row p.intro_text{
  color:#fff;
}

.plice_row{
  margin-bottom:40px;
}
span.plice_detail_text{
  font-size:3em;
}
.plice_item_content{
  padding:15px;
  background:#fff;
}
.plice_item_content ul{
  line-height: 1.8em;
}
.plice_item_content div.plice{
padding:15px;

}
.plice_item_title{
  color:#fff;
  padding:10px;
  font-size:2.0em;
}
#top_button{
  margin-bottom:40px;
}
footer{
background-color: rgba(51,122,183,1);
  height:50px;
  width:100%;
}
.footer_text{
  padding-top:10px;
  padding-bottom:10px;
  color:#fff;
  font-size:1.2em
}


</style>
</head>
<body>
<nav class="navbar navbar-default bg-primary navbar-fixed-top">
<div class="navbar-header">
  <button class="navbar-toggle" data-toggle="collapse" data-target=".target">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
  <a class="navbar-brand" href="#" style="font-family:'Cabin+Condensed',serif;"><i class="fa fa-id-card" aria-hidden="true"></i> InterFree</a>
</div>

<div class="collapse navbar-collapse target">
  <ul class="nav navbar-nav navbar-left">
        <li class="dropdown">
					<a href="#features" role="button">特徴</a>
				</li>
        <li class="dropdown">
          <a href="#functions" role="button">機能紹介</a>
        </li>
        <li class="dropdown">
          <a href="#plice_lists" role="button">料金・プラン</a>
        </li>
        <!-- <li class="dropdown">
          <a href="#company_video" role="button">運営会社</a>
        </li> -->
  </ul>
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
        <a href="create.php" role="button">新規登録</a>
      </li>
      <li class="dropdown">
        <a class="" data-toggle="modal" data-target="#loginModal" role="button">ログイン </a>
      </li>
    </ul>

</div>
</nav>

<div class="container-fruid main">
  <div class="row">
    <div class="col-sm-2 hidden-xs"></div>
    <div class="col-sm-8 col-xs-12">
      <h1>&nbsp;</h1>
      <h1>&nbsp;</h1>
      <h1>&nbsp;</h1>
        <h2 id="top_text" class="text-center">面接に関する様々な負担を軽減</h2>
        <p id="top_p_text" class="text-center">ビデオチャット面接やアンケートフォーム機能で企業、応募者両方の負担を減らします。<br>無料からお使いいただけます。</p>
        <div class="text-center" id="top_button"><a type="button" class="btn btn-lg btn-warning" href="create.php">新規登録</a></div>
        <!-- <div class="text-center"><img id="top_img" class="img-rounded" src="./img/interview_img.png" alt=""></div> -->
        <!-- <div class="text-center"><img id="top_img" class="img-rounded" src="./img/top01.jpg" alt=""></div> -->
    </div>
    <div class="col-sm-2 hidden-xs"></div>
  </div>
</div>
<div class="container-fruid">
<section class="features" id="features">
<div class="row  features_item_row">
  <div class="col-sm-1"></div>
  <h3 class="col-sm-10 features_title">クラウド採用管理・ウェブ面接システム InterFree</h3>
  <div class="col-sm-1"></div>
</div>
<div class="row features_item_row">
  <div class="col-sm-1"></div>
  <div class="col-sm-5 intro_item">
    <img class="intro_img img-responsive img-rounded" src="./img/top02.jpg" alt="">
  <h4 class="intro_sub_title">ウェブ面接で場所にしばられず面接できる！</h4>
  <p class="intro_text">ウェブ面接機能でオフィスに行かなくても面接できます。
    候補者、採用担当者双方の移動に関する時間、コスト、肉体的負担を軽減します。</p>
  </div>
  <div class="col-sm-5">
    <img class="intro_img img-responsive img-rounded" src="./img/top10.png" alt="">
    <h4 class="intro_sub_title">面接前のIDのやり取りが不要</h4>
    <p class="intro_text">ブラウザベースのウェブ面接機能のため、skypやHangOutのようにIDなどを候補者と共有する必要がありません。
    またウェブ面接機能はマルチチャンネルのため、複数の面接担当者が離れた場所から同時に面接に参加できます。</p>
  </div>
  <div class="col-sm-1"></div>
</div>
<div class="row features_item_row">
  <div class="col-sm-1"></div>
  <div class="col-sm-5">
    <img class="intro_img img-responsive img-rounded" src="./img/top03.jpg" alt="">
    <h4 class="intro_sub_title">簡単面接スケジュール調整</h4>
    <p class="intro_text">カレンダーから面接可能日時を選択するだけで、候補者に日時のリストが通知されます。
      候補者は日時リストから対応可能な日時を選択するだけで、面接日時が決定します。
    メールに空き時間を入力しててやり取りする手間が省けます。</p>
    </div>
  <div class="col-sm-5">
    <img class="intro_img img-responsive img-rounded" src="./img/top01.jpg" alt="">
    <h4 class="intro_sub_title">フリーアンケートフォーム機能で面接時間を節約</h4>
    <p class="intro_text">候補者に事前に質問を送る機能を搭載。
      履歴書に書いていないが面接前に聞いておきたいこと、必ず聞かなくてはならないことなどをアンケート形式にして候補者に送信できます。
      候補者に予め質問にこたえてもらうことで、限られた面接時間を
    有効に使えます。</p>
  </div>
  <div class="col-sm-1"></div>
</div>
<div class="row features_item_row">
  <div class="col-sm-1"></div>
  <div class="col-sm-5">
    <img class="intro_img img-responsive img-rounded" src="./img/top11.jpg" alt="">
    <h4 class="intro_sub_title">面接担当者紹介機能で候補者にやさしい面接を</h4>
    <p class="intro_text">事前に面接担当者のプロフィール（動画、紹介文、写真など）を候補者に提示できます。
      候補者、担当者双方がお互いのプロフィールを把握した状態で面接をおこなうことでコミュニケーションを潤滑にします。動画で社内の様子を紹介することもできます。
    </p>
  </div>
  <div class="col-sm-5">
    <img class="intro_img img-responsive img-rounded" src="./img/top07.jpg" alt="">
    <h4 class="intro_sub_title">採用サイトCMS機能</h4>
    <p class="intro_text">テンプレートを選んでテキストを入力するだけで簡単に自社採用サイトを作成できます。
      求人票も簡単に作成でき、ワンクリックで募集開始、募集休止の切替ができます。
    </p>
  </div>
  <div class="col-sm-1"></div>
</div>
<div class="row features_item_row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <h4 class="text-center">その他採用業務の負担を軽減する機能が盛りだくさん</h4>
    <div class="text-center"><a type="button" class="btn btn-lg btn-warning" href="create.php">新規登録</a></div>
  </div>
<div class="col-sm-1"></div>
</div>
</section>

<section class="functions bg-primary" id="functions">
<div class="row functions_item_row">
  <div class="col-sm-1"></div>
  <h3 class="col-sm-10 features_title">InterFree 機能紹介</h3>
  <div class="col-sm-1"></div>
</div>
<div class="row functions_item_row">
  <div class="col-sm-1"></div>
  <div class="col-sm-2 intro_item">
    <img class="intro_img img-responsive img-rounded" src="./img/images.jpeg" alt="">
  <h4 class="intro_sub_title">ウェブ面接機能</h4>
  <p class="intro_text">ブラウザベースのビデオ面接機能。候補者とのIDの共有が不要なためセキュリティ的にも安心</p>
  </div>
  <div class="col-sm-2">
    <img class="intro_img img-responsive img-rounded" src="./img/images.jpeg" alt="">
    <h4 class="intro_sub_title">簡単スケジュール調整</h4>
    <p class="intro_text">カレンダーで空き時間を選択するだけで候補者に面接候補日時を送信。googleカレンダーとの連携（予定）。</p>
  </div>
  <div class="col-sm-2">
    <img class="intro_img img-responsive img-rounded" src="./img/images.jpeg" alt="">
    <h4 class="intro_sub_title">フリーアンケートフォーム</h4>
    <p class="intro_text">候補者へ事前にアンケートを送信できる。アンケートは何通りでも自由に可能。</p>
  </div>
  <div class="col-sm-2">
    <img class="intro_img img-responsive img-rounded" src="./img/images.jpeg" alt="">
    <h4 class="intro_sub_title">面接官プロフィール機能</h4>
    <p class="intro_text">候補者に対し、予め面接官のプロフィールを提示（表示、非表示切替可能）。</p>
  </div>
  <div class="col-sm-2">
    <img class="intro_img img-responsive img-rounded" src="./img/images.jpeg" alt="">
    <h4 class="intro_sub_title">会社案内共有機能</h4>
    <p class="intro_text">候補者に対して、会社案内等の資料を事前に共有。</p>
  </div>
  <div class="col-sm-1"></div>
</div>
<div class="row functions_item_row">
  <div class="col-sm-1"></div>
  <div class="col-sm-2 intro_item">
    <img class="intro_img img-responsive img-rounded" src="./img/images.jpeg" alt="">
  <h4 class="intro_sub_title">採用サイトCMS機能</h4>
  <p class="intro_text">テンプレートに入力するだけで自社採用サイトや求人票が簡単に作成できる。</p>
  </div>
  <!-- <div class="col-sm-2">
    <img class="intro_img img-responsive img-rounded" src="./img/images.jpeg" alt="">
    <h4 class="intro_sub_title">人材紹介会社管理機能</h4>
    <p class="intro_text">紹介会社管理、一括情報送信機能</p>
  </div> -->
  <div class="col-sm-2">
    <img class="intro_img img-responsive img-rounded" src="./img/images.jpeg" alt="">
    <h4 class="intro_sub_title">選考結果チャート機能</h4>
    <p class="intro_text">選考結果をレーダーチャートで可視化。</p>
  </div>
  <div class="col-sm-2">
    <img class="intro_img img-responsive img-rounded" src="./img/images.jpeg" alt="">
    <h4 class="intro_sub_title">チャットツールslackとの連携</h4>
    <p class="intro_text">様々な通知をslackに送信（予定）。</p>
  </div>
  <!-- <div class="col-sm-2">
    <img class="intro_img img-responsive img-rounded" src="./img/images.jpeg" alt="">
    <h4 class="intro_sub_title">内定から入社まで ヘルプツール</h4>
    <p class="intro_text">内定通知書や労働条件通知書など様々なテンプレートを用意。</p>
  </div> -->
  <div class="col-sm-1"></div>
</div>
  <div class="row functions_item_row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="text-center"><a type="button" class="btn btn-lg btn-warning" href="create.php">新規登録</a></div>
    </div>
    <div class="col-sm-1"></div>
  </div>
</section>
<section class="plice_lists" id="plice_lists">
  <div class="row plice_row">
    <div class="col-sm-1"></div>
      <div class="col-sm-10 features_title">
        <h3>InterFreeの利用料金</h3>
      <p>初期費用は一切無し。誰でも簡単にはじめられます。</p>
    </div>
    <div class="col-sm-1"></div>
  </div>
  <div class="row plice_row">
    <div class="col-sm-1"></div>
      <div class="col-sm-10 features_title">
        <h4 class="text-center muryo bg-primary">無料でもずっと使える<br>クラウド採用管理・ウェブ面接システム　InterFree</h4>
    </div>
    <div class="col-sm-1"></div>
  </div>
  <div class="row plice_row">
      <div class="col-sm-4">
        <div class="plice_item_title bg-primary text-center">フリー</div>
        <div class="plice_item_content">
          <div class="plice text-center"><span class="text-primary plice_detail_text">無料</span></div>
          <div class="row">
          <div class="col-xs-2"></div>
          <ul class="list-unstyled col-xs-8">
            <li>まずは無料で試せます。</li>
            <li><i class="glyphicon glyphicon-ok"></i>管理者1名、面接担当者5名まで</li>
            <li><i class="glyphicon glyphicon-ok"></i>候補者10名/1ヶ月</li>
            <li><i class="glyphicon glyphicon-ok"></i>web面接・フリーアンケート</li>
            <li><a href="#" class="text-warning">詳細はこちら</a></li>
          </ul>
          <div class="col-xs-2"></div>
          </div>
          <div class="text-center">
            <a type="button" class="btn btn-warning" href="create.php?plan=1">新規登録</a>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="plice_item_title bg-primary text-center">ベーシック</div>
        <div class="plice_item_content">
          <div class="plice text-center"><span class="text-primary plice_detail_text">4,980</span><span class="text-primary yen">円</span>/月（税別)</span></div>
          <div class="row">
          <div class="col-xs-2"></div>
          <ul class="list-unstyled col-xs-8">
            <li>管理者が複数名いる企業向け</li>
            <li><i class="glyphicon glyphicon-ok"></i>管理者3名、面接担当者制限なし</li>
            <li><i class="glyphicon glyphicon-ok"></i>候補者30名/1ヶ月</li>
            <li><i class="glyphicon glyphicon-ok"></i>web面接・フリーアンケート</li>
            <li><a href="#" class="text-warning">詳細はこちら</a></li>
          </ul>
          <div class="col-xs-2"></div>
          </div>
          <div class="text-center">
            <a href="create.php?plan=2" type="button" class="btn btn-warning">新規登録</a>
          </div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="plice_item_title bg-primary text-center">エンタープライズ</div>
        <div class="plice_item_content">
          <div class="plice text-center"><span class="text-primary plice_detail_text">30,000</span><span class="text-primary yen">円</span>/月（税別)</div>
          <div class="row">
          <div class="col-xs-2"></div>
          <ul class="list-unstyled col-xs-8">
            <li>部署ごとに採用管理をする企業向け</li>
            <li><i class="glyphicon glyphicon-ok"></i>管理者・面接担当者数制限なし</li>
            <li><i class="glyphicon glyphicon-ok"></i>候補者100名/1ヶ月</li>
            <li><i class="glyphicon glyphicon-ok"></i>web面接・フリーアンケート</li>
            <li><a href="#" class="text-warning">詳細はこちら</a></li>
          </ul>
          <div class="col-xs-2"></div>
          </div>
          <div class="text-center">
            <a href="create.php?plan=3" type="button" class="btn btn-warning">新規登録</a>
          </div>
        </div>
      </div>

  </div>
</section>



  <!-- <div class="text-center">
    <button type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#loginModal">Log in</button>
  </div> -->
</div>
<!--ココからlogin modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="loginModalLabel">login</h4>
      </div>
      <div class="modal-body">
        <form action="login_act.php" method="post">
          <div class="form-group">
            <label for="lid" class="control-label">ID:</label>
            <input type="text" class="form-control" name="lid" id="lid">
          </div>
          <div class="form-group">
            <label for="lpw" class="control-label">PW:</label>
            <input type="password" class="form-control" name="lpw" id="lpw">
          </div>
          <div class="form-group">
          <input type="submit" class="btn btn-primary" value="Login">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div><!--modalおわり -->

<?php include("../template/footer.html") ?>

</body>
</html>
