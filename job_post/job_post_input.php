<?php
session_start();
include("../function/function.php");
login_check();
$html_title = servise_name();
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<script src="../ckeditor/ckeditor.js"></script>
<style>
h3{
  margin-bottom:30px;
}
</style>
</head>
<body>

<!-- Head[Start] -->
<?php include("../template/nav.php") ?>
<!-- Head[End] -->

<!-- Main[Start] -->

     <!-- <label>Newsタイトル：<input type="text" name="title"></label><br>
     <label>News記事：<br><textArea id="editor1" name="article" id="editor1" rows="10" cols="80">ワードみたいに使ってね  v（＊＾_＾＊）v</textArea></label><br>
     <script>
     CKEDITOR.replace('editor1');
   </scriptz1> -->




<h3 class="text-center">募集要項入力</h3>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form-group form-horizontal" action="job_post_insert.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label class="control-label col-sm-2" for="job_title">ポジション名</label><div class="col-sm-10"><input type="text" class="form-control" name="job_title" value=""></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="job_img_f">紹介画像</label>
          <div class="col-sm-5"><input id="up_image" type="file" class="form-control-file" name="job_img_f" accept=“image/*” capture=“camera”></div>
          <div class="col-sm-5"><img id="thumbnail" class="img-responsive" src=""></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="job_description">職務内容</label>
          <div class="col-sm-10">
            <textArea id="job_description" class="form-control" name="job_description" rows="10" cols="80"></textArea>
          </div>
          <script>
          CKEDITOR.replace('job_description');
          </script>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="requirement">応募要件</label>
          <div class="col-sm-10">
            <textArea id="requirement" class="form-control" name="requirement" rows="10" cols="80"></textArea>
          </div>
          <script>
          CKEDITOR.replace('requirement');
          </script>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="salary_sys">給与体系</label>
          <div class="col-sm-10">
            <textArea id="salary_system" class="form-control" name="salary_sys" rows="10" cols="80"></textArea>
          </div>
          <script>
          CKEDITOR.replace('salary_system');
          </script>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="estimate_income">想定給与</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="estimate_income" value="">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="welfare">福利厚生</label>
          <div class="col-sm-10">
            <textArea id="welfare" class="form-control" name="welfare" rows="10" cols="80"></textArea>
          </div>
          <script>
          CKEDITOR.replace('welfare');
          </script>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="location">勤務地</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="location" value="">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="work_hour">勤務時間</label>
          <div class="col-sm-10">
            <textArea id="work_hour" class="form-control" name="work_hour" rows="10" cols="80"></textArea>
          </div>
          <script>
          CKEDITOR.replace('work_hour');
          </script>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2 text-left" for="etc">備考</label>
          <div class="col-sm-10">
            <textArea id="etc" class="form-control" name="etc" rows="10" cols="80"></textArea>
          </div>
          <script>
          CKEDITOR.replace('etc');
          </script>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interview_id">募集/休止</label>
          <div class="col-sm-10">
            <select class="form-control" name="life_flg">
              <option value="0" selected>募集</option>
              <option value="1" selected>休止</option>
            </select>
          </div>
        </div>
        <div class="text-center">
          <input class="btn btn-default" type="submit" value="登録">
        </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<!-- Main[End] -->
<?php include("../template/footer.html") ?>
</body>
<script>
$('#up_image').change(function(){
  if(this.files.length > 0){
    var file = this.files[0];

    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(){
      $('#thumbnail').attr('src',reader.result);
    }
  }
});
</script>
</html>
