<?php
if(
  !isset($_POST["csrf_token"]) || $_POST["csrf_token"] =="" ||
  $_POST["csrf_token"] != $_SESSION["csrf_token"]
){
  var_dump($_SESSION["csrf_token"]);
  exit('ParamError_csrf_token');
}
?>
