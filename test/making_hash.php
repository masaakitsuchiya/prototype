<?php
session_start();
include("../function/function.php");
include("../template/csrf_token_generate.php");
?>
<form action="result_hash.php" method="post">
<input type="text" name="nama">
<input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
<input type="submit" value="generate">
</form>
