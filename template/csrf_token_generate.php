<?php
$csrf_token = get_csrf_token();
$_SESSION["csrf_token"] = $csrf_token;
//<input type="hidden" name="csrf_token" value="<?= $csrf_token
?>
