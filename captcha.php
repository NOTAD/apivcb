<?php
/**
Curl with image captcha from AZcaptcha.com
document https://azcaptcha.com/document
*/

$file_name_with_full_path = $_GET['link'];

$api_url = "http://azcaptcha.com";
$key = '';//Điển key captcha vào đây



if (function_exists('curl_file_create')) { // php 5.5+
    $cFile = curl_file_create($file_name_with_full_path);
} else { // 
    $cFile = '@' . realpath($file_name_with_full_path);
}

//method base64
$fields = array(
    'key' => $key,
    'method' => 'base64',
    'regsense'=>0,
    'body' => base64_encode(@file_get_contents($file_name_with_full_path))
);

$c = curl_init();
curl_setopt($c, CURLOPT_URL, "$api_url/in.php");
curl_setopt($c, CURLOPT_POST, 1);
curl_setopt($c, CURLOPT_POSTFIELDS, $fields);
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($c);
curl_close($c);
//echo "<br> result: $result";




if (!preg_match('|OK|', $result)) {
    echo '<br>error';
} else {
    $id = explode("|", $result)[1];
    //echo "<br>Captcha id: $id";
}



do {
    $info = @file_get_contents($api_url.'/res.php?key=' . $key . '&action=get&id=' . $id);
    if ($info == 'CAPCHA_NOT_READY'){
        sleep($timeout);
    }
} while ($info == 'CAPCHA_NOT_READY');
if (!preg_match('|OK|', $info)) {
    echo '<br>error';
} else {
    $text = explode('|', $info)[1];
    $captcha = json_encode([
        'status' => '1',
        'captcha' => $text
        ]);
        echo $captcha;
}