<?php
session_start();
include("../template/csrf_confirm.php");

$nama = $_POST["nama"];

$hash = password_hash($nama,PASSWORD_DEFAULT);
var_dump($hash);

?>
