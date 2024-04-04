<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/web.php';
?>
<?php
$data=expiry_maker('NIFTY');
header('Content-Type: application/json; charset=utf-8');
echo($data);