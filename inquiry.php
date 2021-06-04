<?php
include_once("SinaptIQPKCS7.php"); // เรียก function ที่ทาง 2c2p ส่งมาให้

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

$processType = "I";		
$invoiceNo = "1000166635";
$version = "1.0.0";
$timestamp = date('Y-m-d H:i:s:').round((microtime(true)-time())*1000);
$messageID = sha1($timestamp);
$amount = "990.00";
$amount = str_replace(".","", $amount);
$amount = str_pad($amount,12,'0',STR_PAD_LEFT);

$signData = hash_hmac('sha1', $MerchantID.$invoiceNo.$amount,$apikey, false);
$signData = strtoupper($signData);
$HashValue= urlencode($signData);
$msg= "<InquiryReq>
<Version>".$version."</Version>
<TimeStamp>".$timestamp."</TimeStamp>
<MessageID>".$messageID."</MessageID>
<MerchantID>".$MerchantID."</MerchantID>
<InvoiceNo>".$invoiceNo."</InvoiceNo>
<Amount>".$amount."</Amount>
<RefNo1>".$invoiceNo."</RefNo1>
<HashValue>".$HashValue."</HashValue>
<UserDefined1></UserDefined1>
<UserDefined2></UserDefined2>
<UserDefined3></UserDefined3>
<UserDefined4></UserDefined4>
<UserDefined5></UserDefined5>
</InquiryReq>";  


$key = file_get_contents($onetwothreefilecer); //public key for encrypt. This is 123's public key
$merchantkey = file_get_contents($merchantcer); //public key for encrypt. This is 123's public key
$key_private = array(file_get_contents($merchantprivate), $merchantpassword); //private key for decrypt. This is 

$OneTwoThreeReq=encrypt($key, $msg);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://secure.123.co.th/Payment/inquiryapi.aspx',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('InquiryReq' => $OneTwoThreeReq),
));

$response = curl_exec($curl);

curl_close($curl);

echo "<br/>request encrypt: ".$OneTwoThreeReq."<br/>"; 
echo "<br/>request hash: ".$HashValue."<br/>"; 

$OneTwoThreeRes = decrypt($response, $merchantkey, $key_private);

echo "<br/>respond encrypt : ".$response;
echo "<br/>respond decrypt : ".$OneTwoThreeRes;
?>
