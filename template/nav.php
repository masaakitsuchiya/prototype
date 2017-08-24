<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Cabin+Condensed&text=SmartInterview">
<script src="https://use.fontawesome.com/16c63c33a4.js"></script>
<nav class="navbar navbar-default navbar-fixed-top">
<div class="navbar-header">
  <button class="navbar-toggle" data-toggle="collapse" data-target=".target">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
  <a class="navbar-brand" href="../top/index.php" style="font-family:'Cabin+Condensed',serif;"><i class="fa fa-id-card" aria-hidden="true"></i> InterFree</a>
</div>

<div class="collapse navbar-collapse target">
  <ul class="nav navbar-nav navbar-right">
      <li>
        <a href="../top/index.php" role="button">Dash Board</a>
      </li>
      <?php if(isset($_SESSION["kanri_flg"]) AND $_SESSION["kanri_flg"] == 1){
          echo '
          <li>
            <a href="../setting/interviewee_select.php" role="button">候補者一覧</a>
          </li>
          <li class="dropdown">
  					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">設定<span class="caret"></a>
  					<ul class="dropdown-menu" role="menu">
  						<li><a href="../setting/interviewer_select.php">面接者登録</a></li>
              <li><a href="../job_post/job_post_select.php">求人管理</a></li>
              <li><a href="../apply/apply_index_input.php">求人サイト作成</a></li>
              <li><a href="../apply/index.php">求人サイト確認</a></li>
              <li><a href="../questionnaire/index.php">アンケートフォーマット登録・編集</a></li>
              <li><a href="../corp_info/index.php">候補者向け会社情報</a></li>
              <li><a href="../mail_template/to_interviewee_mail_show.php">メールテキスト（候補者宛）</a></li>
  					</ul>
				  </li>
                ';
            }
        ?>
        <?php if(isset($_SESSION["kanri_flg"])){
                  echo '
				<li>
					<a href="../result/input_data_select.php" role="button">面接・評価入力</a>
				</li>
                ';
            }
        ?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><?php if($_SESSION){echo $_SESSION["user_name"];}else{echo "アカウント";} ?><span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<?php if(!$_SESSION){echo '<li><a href="/dh/login_out/login.php">ログイン</a></li>';} ?>
                        <?php if($_SESSION){echo '
                        <li><a href="/dh/login_out/logout.php">ログアウト</a></li>
						<li><a href="/dh/login_out/my_interviewer_detail.php">情報更新</a></li>
                                ';
                            }
                        ?>
					</ul>
				</li>



      <!-- <li><a href="/login_out/login.php">login</a></li>
      <li><a href="interviewee_select.php">評価入力・確認</a></li>
      <li><a href="interviewee_setting.php">新規候補者登録</a></li>
      <li><a href="/10/my_kaday/login_out/logout.php">Logout</a></li> -->
  </ul>
</div>
</nav>
