<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://kite.zerodha.com/oms/trusted/kitefront/user/UT1882/twofa/generate_otp');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "request_id=7xqLxqfNX28DUVN3pcaMNNRv2X5cciIVqxsxWT5TJ44zLcd6Oq6Ir4FuTbEI44at&twofa_type=sms");
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Authority: kite.zerodha.com';
$headers[] = 'Accept: application/json, text/plain, */*';
$headers[] = 'Accept-Language: en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7';
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
$headers[] = 'Cookie: kf_session=3dZ2gH3f0I6w25nOrTQilrwRzmpwYiME; _cfuvid=e2r4tOzt81hQZk6Q9jiEHOzz2asQZT3aaQ6imcDU7aM-1690701634639-0-604800000';
$headers[] = 'Origin: https://kite.zerodha.com';
$headers[] = 'Referer: https://kite.zerodha.com/';
$headers[] = 'Sec-Ch-Ua: \"Chromium\";v=\"107\", \"Not=A?Brand\";v=\"24\"';
$headers[] = 'Sec-Ch-Ua-Mobile: ?1';
$headers[] = 'Sec-Ch-Ua-Platform: \"Android\"';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Site: same-origin';
$headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 11; M2101K7AI) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Mobile Safari/537.36';
$headers[] = 'X-Kite-App-Uuid: 0139d067-e940-479b-9082-3e5c15da49a3';
$headers[] = 'X-Kite-Version: 3.0.0';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
echo($result);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
?>