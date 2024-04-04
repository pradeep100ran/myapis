<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/web.php';
$token=direct_totp();
echo($token);
?>