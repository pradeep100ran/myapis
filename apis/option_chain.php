<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/web.php';
?>
<?php
//$instrument_token=$_GET['instrument_token'];
$instrument_token='9b74e1eb887fd632b809fae88777ac96';
$data=option_chain($instrument_token);
header('Content-Type: application/json; charset=utf-8');
echo(json_encode($data));