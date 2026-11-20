<?php 
$auth_basic = base64_encode("mtroncellito@plusdata.info:YRbjTIJKe10AJe7V4IqB0VCN1XNNkDG0");

$curl = curl_init();

$env = "verifApp";
$phone_number = "541150600593";
$code = "203516";

$url = 'https://api.labsmobile.com/otp/validateCode?';
            $url .= 'env='.  urlencode($env) .'&';
            $url .= 'phone_number=' .  urlencode($phone_number) .'&';
            $url .= 'code=' .  urlencode($code);

echo '<br>'.$url;

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic '.$auth_basic
  ),
));

$response = curl_exec($curl);
echo '<br>'.$response;

curl_close($curl);

            