<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
require_once $_SERVER['DOCUMENT_ROOT'].'/apis/web.php';
?>
<?php
if (isset($_GET['find'])){
    $input = $_GET['find'];
$data=search_script2($input);
$data=json_encode($data);
echo($data);
}
?>