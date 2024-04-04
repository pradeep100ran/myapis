<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://kite.zerodha.com/api/captcha');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
//$headers[] = 'Authority: kite.zerodha.com';
//$headers[] = 'Accept: application/json, text/plain, */*';
//$headers[] = 'Accept-Language: en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7';
//$headers[] = 'Cookie: kf_session=1EcwoSauCUtIrYKRXZuTTg3yIjGQfsuN; _cfuvid=Nf2jWrrxHaZ3BLXt4g.dN4fHsT3immke1S7sk6R9YT8-1690705069330-0-604800000';
//$headers[] = 'Referer: https://kite.zerodha.com/';
//$headers[] = 'Sec-Ch-Ua: \"Chromium\";v=\"107\", \"Not=A?Brand\";v=\"24\"';
$headers[] = 'Sec-Ch-Ua-Mobile: ?1';
//$headers[] = 'Sec-Ch-Ua-Platform: \"Android\"';
//$headers[] = 'Sec-Fetch-Dest: empty';
//$headers[] = 'Sec-Fetch-Mode: cors';
//$headers[] = 'Sec-Fetch-Site: same-origin';
//$headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 11; M2101K7AI) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Mobile Safari/537.36';
//$headers[] = 'X-Csrftoken: hmb1qtF2Yz1HPegYk2yTbqEbGDkXYT2T';
//$headers[] = 'X-Kite-App-Uuid: 0139d067-e940-479b-9082-3e5c15da49a3';
//$headers[] = 'X-Kite-Userid: UT1881';
//$headers[] = 'X-Kite-Version: 3.0.0';
//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
echo($result);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);
?>