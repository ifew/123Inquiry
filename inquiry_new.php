<?php
include_once('SinaptIQPKCS7.php');

//Pleae change THIS For Production
$onetwothreefilecer="123Public_Prod.cer"; //123' Certificate file
$merchantcer="cert_pub_merchant.cer";//Merchant' Certificate file
$merchantprivate="cert_private_merchant.pem";//Merchant' Private Key file
$merchantpassword="123";//Merchant' Password for Private Key file
//END Pleae change THIS For Production

//Please change MERCHANT INFO
$MerchantID='xxx';//Merchant ID
$apikey="xxx";//API Secret Key
//END Please change MERCHANT INFO
	
$invoiceNo = "1000166635";

//Construct signature string
$stringToHash = $merchantID.$invoiceNo; 
$hash = hash_hmac('sha256', $stringToHash ,$apikey, false);
$hash = strtoupper($hash);
$hash= urlencode($hash);

$json = '{"payment_code": "'.$invoiceNo.'","merchant_reference": "'.$invoiceNo.'","merchant_id": "'.$merchantID.'","checksum": "'.$hash.'"}';

$key = file_get_contents($onetwothreefilecer); //public key for encrypt. This is 123's public key
$merchantkey = file_get_contents($merchantcer); //public key for encrypt. This is 123's public key
$key_private = array(file_get_contents($merchantprivate), $merchantpassword); //private key for decrypt. This is 

$payload = encrypt($key, $json);
		
include_once('HTTP.php');

//Send request to 2C2P PGW and get back response
// $http = new HTTP();
// $response = $http->post("https://mct123.2c2p.com/merchanttranslation/api/merchantenc/get-payment-status", '{"message": "'.$payload.'"}');
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://mct123.2c2p.com/merchanttranslation/api/merchantenc/get-payment-status',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => '{"message":"'.$payload.'"}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);

echo "<br/>request encrypt: ".$payload."<br/>"; 
echo "<br/>request hash: ".$hash."<br/>"; 

// //Decrypt response message and display  
$response_decrypt = decrypt($response, $merchantkey, $key_private);
echo "<br/>respond encrypt : ".$response;
echo "<br/>respond decrypt : ".$response_decrypt;
?>  
