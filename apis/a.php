<?php
$userid='UT1881';
$root='https://'.getenv('HTTP_HOST');
echo($root);
$secret = 'BIFUJKENU7OFZRED4NDUA24NU7MSKPYE';
$temp_token=file_get_contents($root.'/apis/kite-otp.php?user_id='.$userid.'&password=Sheoran@%2312345@%23');
$totp =totp($secret);
$enctoken=file_get_contents($root.'/apis/kite-token.php?user_id='.$userid.'&token='.$temp_token.'&otp='.$totp);
echo($enctoken)
?>